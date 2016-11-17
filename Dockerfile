FROM php:5.6-alpine
MAINTAINER Suro "suro@tsh.io"

COPY . /src/translator

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /src/translator

RUN composer install

CMD [ "php", "./translate.php" ]