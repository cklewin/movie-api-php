FROM php:7.2-apache

RUN apt-get update && apt-get install -y \
    libgmp-dev \
    && docker-php-ext-configure gmp \
    && docker-php-ext-install mysqli gmp

COPY apache/default-to-index.conf /etc/apache2/conf-available/default-to-index.conf

RUN ln -s ../conf-available/default-to-index.conf /etc/apache2/conf-enabled/default-to-index.conf \
    && ln -s ../mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load

EXPOSE 80

USER www-data
COPY src/ /var/www/
USER root
