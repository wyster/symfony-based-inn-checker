#!/usr/bin/env bash
echo 'Run start.sh'

bash /wait-for.sh php:9000 --timeout=30 -- echo "Php fpm started"

nginx -g 'daemon off;'
