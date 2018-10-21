FROM php:7.2-apache
RUN docker-php-ext-install pdo_mysql
MAINTAINER Luiz Eduardo Carneiro

RUN apt-get update && apt-get install -y zip unzip

COPY composer.lock /var/www/html
COPY composer.json /var/www/html
COPY public /var/www/html/public
WORKDIR /var/www/html


RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && php composer.phar install --no-dev --no-scripts --no-autoloader

COPY vhost.conf /etc/apache2/sites-enabled/000-default.conf

RUN a2enmod rewrite
RUN a2enmod headers
RUN service apache2 restart

COPY . /var/www/html

COPY .env.prod /var/www/html/.env
COPY start.sh /var/www/html/

RUN php composer.phar dump-autoload --optimize \
    && php composer.phar run-script post-root-package-install \
    && rm composer.phar

RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache

RUN php artisan optimize
CMD sh start.sh
EXPOSE 80