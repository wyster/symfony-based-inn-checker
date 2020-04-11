#!/bin/bash

if [ $COMPOSER_INSTALL == "1" ]; then
    composer global require hirak/prestissimo
    composer install --prefer-dist --no-progress --no-suggest
fi

if [ $ENABLE_XDEBUG == "1" ]; then
    docker-php-ext-enable xdebug
fi

echo $MYSQL_DATABASE

bash /wait-for.sh --timeout=30 mysql:3306 -- echo "Mysql started"

php ./bin/console doctrine:migrations:migrate --no-interaction

docker-php-entrypoint php-fpm
