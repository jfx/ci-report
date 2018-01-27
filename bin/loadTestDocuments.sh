#!/bin/bash
PROJECT_DIR=`dirname $0`/..
DOC_DIR=$PROJECT_DIR/var/documents
cd $PROJECT_DIR

rm -rf $DOC_DIR
mkdir -p $DOC_DIR/1/1
mkdir -p $DOC_DIR/1/4
cp $PROJECT_DIR/tests/files/zipfile-ok.zip $DOC_DIR/1/1/d8329fc1cc938780ffdd9f94e0d364e0ea74f579
cp $PROJECT_DIR/tests/files/zipfile-ok.zip $DOC_DIR/1/4/1f7a7a472abf3dd9643fd615f6da379c4acb3e3a
