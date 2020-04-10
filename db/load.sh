#!/bin/sh

BASE_DIR=$(dirname "$(readlink -f "$0")")
if [ "$1" != "test" ]; then
    psql -h localhost -U yiicomment -d yiicomment < $BASE_DIR/yiicomment.sql
fi
psql -h localhost -U yiicomment -d yiicomment_test < $BASE_DIR/yiicomment.sql
