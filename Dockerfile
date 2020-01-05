FROM php:7.3.3-apache
RUN apt-get update \
 && apt-get install -y git zlib1g-dev \
 && docker-php-ext-install pdo pdo_mysql zip
 && a2enmod rewrite
EXPOSE 80
