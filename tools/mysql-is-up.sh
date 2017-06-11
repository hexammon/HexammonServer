#!/usr/bin/env bash

docker logs db 2>&1 | grep -n1 'mysqld: ready for connections' | grep -q 'port: 3306'
exit $?