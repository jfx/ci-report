#!/bin/bash
PROJECT_DIR=`dirname $0`/..
cd $PROJECT_DIR

if [ "$#" -ne 3 ]; then
    echo "Arguments should be:"
    echo "   - -d for dump or -i for import dump"
    echo "   - DB URL example: mysql://username:password@127.0.0.1:3306/database"
    echo "   - dump file path"
    exit 1
fi

if [[ $2 =~ :\/\/(.+):(.+)@(.+):(.+)\/(.+) ]]; then
    user=${BASH_REMATCH[1]}
    password=${BASH_REMATCH[2]}
    host=${BASH_REMATCH[3]}
    port=${BASH_REMATCH[4]}
    database=${BASH_REMATCH[5]}
    if [ "$1" == "-d" ]; then
       mysqldump -P $port -h $host -u $user -p$password $database > $3
    elif [ "$1" == "-i" ]; then
       mysql -P $port -h $host -u $user -p$password $database < $3
    else
       echo "First parameter should be -d for dump or -i for import"
       exit 1
    fi
else
    echo "URL format should be mysql://username:password@127.0.0.1:3306/database"
    exit 1
fi
