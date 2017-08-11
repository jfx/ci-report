#!/bin/bash
PROJECT_DIR=`dirname $0`/..
cd $PROJECT_DIR
vendor/bin/phpcs src --standard=standards/ruleset-cs.xml "$@"
