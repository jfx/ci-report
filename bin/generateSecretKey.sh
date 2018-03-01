#!/bin/bash
PROJECT_DIR=`dirname $0`/..
cd $PROJECT_DIR

if [ "$#" -ne 1 ]; then
    LENGTH=40
    KEY=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w $LENGTH | head -n 1)
else
    KEY=$1
fi

sed -i "s/^\s*secret:.*/    secret: \"${KEY}\"/" $PROJECT_DIR/app/config/parameters.yml
