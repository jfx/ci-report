#!/bin/bash
PROJECT_DIR=`dirname $0`/..
cd $PROJECT_DIR
vendor/bin/phpunit -c phpunit.xml
