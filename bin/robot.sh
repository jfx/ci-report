#!/bin/bash
PROJECT_DIR=`dirname $0`/..
cd $PROJECT_DIR
robot -d var/logs "$@" tests/RF
