FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
        libicu-dev \
        libpq-dev

RUN docker-php-ext-install pdo pdo_mysql pgsql pdo_pgsql intl
