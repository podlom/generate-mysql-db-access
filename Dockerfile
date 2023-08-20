FROM php:7.4-apache

RUN apt-get update && apt-get install -y default-mysql-client \
    vim zip unzip \
    && docker-php-ext-install pdo_mysql

COPY src/ /var/www/html/

EXPOSE 80

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN composer install
