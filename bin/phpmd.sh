#!/bin/bash
PROJECT_DIR=`dirname $0`/..
cd $PROJECT_DIR
vendor/bin/phpmd src text standards/ruleset-pmd.xml  --exclude src/AppBundle/DataFixtures
