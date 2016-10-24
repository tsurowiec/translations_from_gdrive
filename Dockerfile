FROM php:5.6-alpine
MAINTAINER Suro "suro@tsh.io"

COPY . /src/translator

WORKDIR /src/translator

CMD [ "php", "./translate.php" ]