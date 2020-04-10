ARG PHP_VERSION=7.4

FROM php:${PHP_VERSION}-fpm


RUN apt update && apt install -y \
    git \
    curl \
    # zip
    zip \
    libzip-dev

# XDebug
RUN pecl install xdebug

# MySQL
RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql

# Other
RUN docker-php-ext-install zip

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD ./start.sh /start.sh
RUN chmod +x /start.sh
CMD /start.sh
