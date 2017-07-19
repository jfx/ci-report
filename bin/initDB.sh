#!/bin/bash
PROJECT_DIR=`dirname $0`/..
cd $PROJECT_DIR
php bin/console doctrine:database:drop --force --no-interaction
php bin/console doctrine:database:create --no-interaction
php bin/console doctrine:schema:create --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
