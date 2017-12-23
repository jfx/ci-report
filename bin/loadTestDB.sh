#!/bin/bash
PROJECT_DIR=`dirname $0`/..
cd $PROJECT_DIR
bin/console doctrine:database:drop --force --no-interaction
bin/console doctrine:database:create --no-interaction
bin/console doctrine:schema:create --no-interaction --quiet
bin/console doctrine:fixtures:load --no-interaction
