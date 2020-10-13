FROM php:7.4-fpm

RUN docker-php-ext-install pdo pdo_mysql

#RUN apt-get update \
# && apt-get install -y --no-install-recommends \
#    supervisor

RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

ADD . /var/www/html
RUN chown -R www-data:www-data /var/www/html

RUN apt-get update && apt-get install -y openssh-server apache2 supervisor
RUN mkdir -p /var/lock/apache2 /var/run/apache2 /var/run/sshd /var/log/supervisor

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 22 80
CMD ["/usr/bin/supervisord"]
