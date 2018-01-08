#!/bin/bash
PROJECT_DIR=`dirname $0`/..
cd $PROJECT_DIR
echo "To filter tests to run : --filter <pattern>"
bin/loadDBFixtures.sh
vendor/bin/phpunit -c phpunit.xml "$@"
