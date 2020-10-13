FROM php:7.4-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

ADD . /var/www/html
RUN chown -R www-data:www-data /var/www/html

COPY crontab /etc/crontabs/root

CMD ["crond", "-f"]
