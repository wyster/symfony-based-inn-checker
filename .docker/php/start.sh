#!/bin/bash

if [ $COMPOSER_INSTALL == "1" ]; then
    composer global require hirak/prestissimo
    composer install --prefer-dist --no-progress --no-suggest
fi

if [ $ENABLE_XDEBUG == "1" ]; then
    docker-php-ext-enable xdebug
fi

bash /wait-for.sh mysql:3306 -t 0 -- echo "Mysql started"

php ./bin/console doctrine:migrations:migrate --no-interaction

docker-php-entrypoint php-fpm
