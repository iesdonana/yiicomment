#!/bin/sh

if [ "$1" = "travis" ]; then
    psql -U postgres -c "CREATE DATABASE yiicomment_test;"
    psql -U postgres -c "CREATE USER yiicomment PASSWORD 'yiicomment' SUPERUSER;"
else
    sudo -u postgres dropdb --if-exists yiicomment
    sudo -u postgres dropdb --if-exists yiicomment_test
    sudo -u postgres dropuser --if-exists yiicomment
    sudo -u postgres psql -c "CREATE USER yiicomment PASSWORD 'yiicomment' SUPERUSER;"
    sudo -u postgres createdb -O yiicomment yiicomment
    sudo -u postgres psql -d yiicomment -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    sudo -u postgres createdb -O yiicomment yiicomment_test
    sudo -u postgres psql -d yiicomment_test -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    LINE="localhost:5432:*:yiicomment:yiicomment"
    FILE=~/.pgpass
    if [ ! -f $FILE ]; then
        touch $FILE
        chmod 600 $FILE
    fi
    if ! grep -qsF "$LINE" $FILE; then
        echo "$LINE" >> $FILE
    fi
fi
