FROM php:7.0-fpm

MAINTAINER Taylor Silenzio <tsilenzio@gmail.com>

# Add the config files
ADD conf.d/* /tmp/

# Install configuration files
RUN rm -rf /usr/local/etc/php-fpm.d/www.conf.default \
    && rm -rf /usr/local/etc/php-fpm.conf \
    && mv /tmp/www.conf /usr/local/etc/php-fpm.d/www.conf \
    && mv /tmp/php-fpm.conf /usr/local/etc/php-fpm.conf \
    && mv /tmp/php.ini /usr/local/etc/php/php.ini

# Add persistant volume
VOLUME ["/srv/http"]
