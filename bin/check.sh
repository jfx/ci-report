#!/bin/bash
PROJECT_DIR=`dirname $0`/..
cd $PROJECT_DIR
bin/php-cs-fixer.sh
bin/phpcs.sh
bin/phpmd.sh
bin/phpunit.sh
