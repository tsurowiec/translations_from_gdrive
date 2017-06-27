FROM php:7-alpine
MAINTAINER Suro "suro@tsh.io"

COPY . /src/translator

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /src/translator

ARG CLIENT_ID
ARG PROJECT_ID
ARG CLIENT_SECRET

RUN composer install --no-dev

ADD docker-entrypoint.sh /
RUN chmod 755 /docker-entrypoint.sh
ENTRYPOINT ["/docker-entrypoint.sh"]

CMD [ "php", "./translate.php" ]
