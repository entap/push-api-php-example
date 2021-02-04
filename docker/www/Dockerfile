FROM php:7.3-apache

COPY ./apache/* /etc/apache2/sites-available/

RUN apt-get update -y \
    && apt-get install -y libgmp3-dev \
    && docker-php-ext-install gmp
