
EXEC_PHP    = php

SYMFONY     = $(EXEC_PHP) bin/console
COMPOSER    = composer
YARN        = yarn

## Clean
## -----

clean: ## Remove generated files
clean: clean-log
	rm -rf .env vendor node_modules

clean-log: ## Remove log and tmp files
clean-log:
	@rm -rf var/log
	@mkdir var/log
	@rm -rf var/tmp
	@mkdir var/tmp

clean-cache: ## Clean Symfony cache
clean-cache:
	$(SYMFONY) cache-clear

clean-test-files: ## Remove test attached documents
clean-test-files:
	@rm -rf var/documents

.PHONY: clean clean-log clean-cache clean-test-files

## Update
## ------

update-composer: ## Update composer packages
update-composer:
	$(COMPOSER) update

update-yarn: ## Update nodejs packages with yarn
update-yarn:
	$(YARN) upgrade

.PHONY: update-composer update-yarn

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
