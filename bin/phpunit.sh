#!/bin/bash
PROJECT_DIR=`dirname $0`/..
cd $PROJECT_DIR
bin/loadTestDB.sh
vendor/bin/phpunit -c phpunit.xml
