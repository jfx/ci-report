#!/bin/bash
PROJECT_DIR=`dirname $0`/..
cd $PROJECT_DIR
PARAMETERS_FILE_PATH=$PROJECT_DIR/app/config/parameters.yml

if [ ! -e "$PARAMETERS_FILE_PATH" ]; then
   cp $PARAMETERS_FILE_PATH.dist $PARAMETERS_FILE_PATH
   echo "$PARAMETERS_FILE_PATH generated!"
fi
