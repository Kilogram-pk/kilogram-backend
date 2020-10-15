FROM php:7.4-fpm
MAINTAINER docker@ekito.fr

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update \
 && apt-get install -y --no-install-recommends \
    supervisor

RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

ADD . /var/www/html
RUN chown -R www-data:www-data /var/www/html

COPY schedule/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord"]

RUN apt-get update && apt-get -y install cron

# Add crontab file in the cron directory
ADD schedule/crontab /etc/cron.d/hello-cron

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/hello-cron

# Apply cron job
RUN crontab /etc/cron.d/hello-cron

# Create the log file to be able to run tail
RUN touch /var/log/cron.log