FROM php:8.3-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev libicu-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install symfony-cli -y

RUN docker-php-ext-configure intl
RUN docker-php-ext-install mysqli pdo pdo_mysql intl
RUN docker-php-ext-enable pdo_mysql

COPY php.ini /usr/local/etc/php/php.ini
