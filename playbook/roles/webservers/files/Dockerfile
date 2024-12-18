FROM php:8.2-fpm

RUN apt-get update \
    && docker-php-ext-install pdo pdo_mysql

WORKDIR /usr/share/nginx/html
EXPOSE 9000
ENTRYPOINT [ "php-fpm" ]
