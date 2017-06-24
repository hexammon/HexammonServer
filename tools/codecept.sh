#!/usr/bin/env bash
echo 'wait for mysql is up'
until ./tools/mysql-is-up.sh; do printf .; sleep 1; done;
echo "restart server with actual code base:"
docker restart server
printf "run codecept----------------------------------------------\n\n"
docker exec server vendor/bin/codecept $@