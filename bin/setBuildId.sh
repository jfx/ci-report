#!/bin/bash
PROJECT_DIR=`dirname $0`/..
cd $PROJECT_DIR

if [ "$#" -ne 1 ]; then
    BUILD=BR/CID/BID
else
    BUILD=$1
fi
BUILD_ESCAPED="${BUILD//\//\\/}"

sed -i "s/^.*app.build:.*/    app.build: ${BUILD_ESCAPED}/" $PROJECT_DIR/app/config/parameters.yml
