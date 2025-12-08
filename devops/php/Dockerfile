FROM php:8.2-apache

RUN a2enmod rewrite

COPY php.ini /usr/local/etc/php/
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
RUN apt-get update \
  && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev git mariadb-client libzip-dev zip \
  && docker-php-ext-install pdo_mysql mysqli gd iconv \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug

RUN apt-get install -y zlib1g-dev libicu-dev g++ \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-enable intl

 RUN apt-get update && apt-get install -y zlib1g-dev && apt-get install -y libzip-dev \
    && docker-php-ext-install zip

RUN apt-get update -y \
  && apt-get install -y \
     libxml2-dev \
  && apt-get clean -y \
  && docker-php-ext-install soap

RUN docker-php-ext-install exif

RUN docker-php-ext-install opcache \
    && docker-php-ext-enable opcache


RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"

RUN mv /var/www/html/composer.phar /usr/local/bin/composer

ADD ./start.sh /
RUN chmod +x /start.sh

ENTRYPOINT [ "bash" ]
CMD ["/start.sh"]
