#!/bin/bash
PROJECT_DIR=`dirname $0`/..
cd $PROJECT_DIR
bin/php-cs-fixer.sh
bin/console lint:yaml app/config
bin/console lint:twig app/Resources/views
bin/console security:check
composer validate --strict
bin/console doctrine:schema:validate --skip-sync -vvv --no-interaction
bin/phpcs.sh
bin/phpmd.sh
bin/phpunit.sh
