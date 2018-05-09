
EXEC_PHP    = php

SYMFONY     = $(EXEC_PHP) bin/console
COMPOSER    = composer

YARN        = yarn

DOCKER         = docker
DOCKER-COMPOSE = docker-compose

## Clean
## -----

clean: ## Remove generated files
clean: clean-log clean-assets
	rm -rf .env vendor node_modules var
	mkdir var

clean-assets: ## Remove web assets
clean-assets:
	cd public && ls | grep -v index.php | xargs rm -rf

clean-log: ## Remove log and tmp files
clean-log:
	@rm -rf var/log
	@mkdir var/log
	@rm -rf var/tmp
	@mkdir var/tmp

clean-cache: ## Clean Symfony cache
clean-cache:
	$(SYMFONY) cache:clear

clean-test-files: ## Remove test attached documents
clean-test-files:
	@rm -rf var/documents

.PHONY: clean clean-log clean-assets clean-cache clean-test-files

## Install
## ------

install-prod: ## Production installation
install-prod: clean composer-install-prod yarn-install assets-install

composer-install-prod: ## Composer install for production
composer-install-prod:
	export APP_ENV=prod; \
	$(COMPOSER) install --no-dev --optimize-autoloader

composer-install: ## Composer install with dev dependencies
composer-install:
	$(COMPOSER) install

yarn-install: ## Yarn install
yarn-install:
	$(YARN) install

assets-install: ## Deploy web assets
assets-install: clean-assets
	@cp -r assets/static/* public
	$(SYMFONY) assets:install public
	$(YARN) run encore production

.PHONY: install-prod composer-install-prod composer-install yarn-install assets-install

## Docker
## -----

docker-build: ## Build application docker image
docker-build: docker-rmi
	docker build --target builder-app -t builder-app:latest -f Dockerfile-php-fpm . 2>&1 | tee docker-builder-app.log
	docker build --target ci-report-app -t ci-report-app:latest -f Dockerfile-php-fpm . 2>&1 | tee docker-ci-report-app.log
	docker build --target builder-web -t builder-web:latest -f Dockerfile-nginx . 2>&1 | tee docker-builder-web.log
	docker build --target ci-report-web -t ci-report-web:latest -f Dockerfile-nginx . 2>&1 | tee docker-ci-report-web.log

docker-rm: ## Remove all unused containers
docker-rm:
	docker container prune -f

docker-rmi: ## Remove all untagged images
docker-rmi: docker-rm
	docker image prune -f

dc-up: ## Docker compose up
dc-up:
	docker-compose up -d

dc-down: ## Docker compose shutdown
dc-down:
	docker-compose down

.PHONY: docker-build docker-rm docker-rmi dc-up dc-down

## Update
## ------

update-composer: ## Update composer packages
update-composer:
	$(COMPOSER) update

update-yarn: ## Update nodejs packages with yarn
update-yarn:
	$(YARN) upgrade

commit: ## Commit with Commitizen command line
commit:
	$(YARN) commit

.PHONY: update-composer update-yarn commit

## Data
## ----

db: ## Reset the database and load fixtures
db:
	-$(SYMFONY) doctrine:database:drop --if-exists --force --no-interaction
	-$(SYMFONY) doctrine:database:create --if-not-exists --no-interaction
	$(SYMFONY) doctrine:schema:create --no-interaction --quiet
	$(SYMFONY) doctrine:fixtures:load --no-interaction

db-dump: ## Dump database in a file. Arguments: url=mysql://username:password@127.0.0.1:3306/database, file=file_path
db-dump:
	$(eval user := $(shell echo ${url} | sed 's/mysql:\/\/\(.*\):.*@.*/\1/'))
	$(eval password := $(shell echo ${url} | sed 's/mysql:\/\/.*:\(.*\).*@.*/\1/'))
	$(eval host := $(shell echo ${url} | sed 's/mysql:\/\/.*:.*@\(.*\):.*/\1/'))
	$(eval port := $(shell echo ${url} | sed 's/mysql:\/\/.*:.*@.*:\(.*\)\/.*/\1/'))
	$(eval database := $(shell echo ${url} | sed 's:.*/::'))
	@mysqldump -P $(port) -h $(host) -u $(user) -p$(password) $(database) > $(file)
	@echo mysql dump saved in file: $(file) !

db-import: ## Import a mysql dump in database. Arguments: url=mysql://username:password@127.0.0.1:3306/database, file=file_path
db-import:
	$(eval user := $(shell echo ${url} | sed 's/mysql:\/\/\(.*\):.*@.*/\1/'))
	$(eval password := $(shell echo ${url} | sed 's/mysql:\/\/.*:\(.*\).*@.*/\1/'))
	$(eval host := $(shell echo ${url} | sed 's/mysql:\/\/.*:.*@\(.*\):.*/\1/'))
	$(eval port := $(shell echo ${url} | sed 's/mysql:\/\/.*:.*@.*:\(.*\)\/.*/\1/'))
	$(eval database := $(shell echo ${url} | sed 's:.*/::'))
	@mysql -P $(port) -h $(host) -u $(user) -p$(password) $(database) < $(file)
	@echo mysql dump imported !

test-files: ## Initialize test attached documents
test-files: clean-test-files
	@mkdir -p var/documents/1/1
	@mkdir -p var/documents/1/4
	@cp tests/files/zipfile-ok.zip var/documents/1/1/d8329fc1cc938780ffdd9f94e0d364e0ea74f579
	@cp tests/files/zipfile-ok.zip var/documents/1/4/1f7a7a472abf3dd9643fd615f6da379c4acb3e3a

.PHONY: db db-dump db-import test-files

## QA
## --

php-cs-fixer: ## Run PHP Coding Standards Fixer
php-cs-fixer:
	vendor/bin/php-cs-fixer fix --using-cache=no --rules=@Symfony tests/phpunit
	vendor/bin/php-cs-fixer fix --config=standards/.php_cs

lint: ## Check syntax of files
lint:
	$(SYMFONY) lint:yaml config
	$(SYMFONY) lint:twig templates
	$(SYMFONY) security:check --end-point=http://security.sensiolabs.org/check_lock
	$(COMPOSER) validate --strict
	$(SYMFONY) doctrine:schema:validate --skip-sync -vvv --no-interaction

phpcs: ## Run PHP CodeSniffer
phpcs:
	vendor/bin/phpcs src --standard=standards/ruleset-cs.xml

phpmd: ## Run PHPMD
phpmd:
	vendor/bin/phpmd src text standards/ruleset-pmd.xml  --exclude src/DataFixtures

unit-test: ## Run unit tests with phpunit
unit-test:
	bin/phpunit

check: ## Run all QA checks
check: php-cs-fixer lint phpcs phpmd unit-test

.PHONY: php-cs-fixer lint phpcs phpmd unit-test check

.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help
