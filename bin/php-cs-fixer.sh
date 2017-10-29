#!/bin/bash
PROJECT_DIR=`dirname $0`/..
cd $PROJECT_DIR
vendor/bin/php-cs-fixer fix --using-cache=no --rules=@Symfony tests/phpunit
vendor/bin/php-cs-fixer fix --config=standards/.php_cs
