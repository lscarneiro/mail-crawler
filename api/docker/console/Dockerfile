FROM php:7.2
RUN docker-php-ext-install pdo_mysql
MAINTAINER Luiz Eduardo Carneiro

RUN apt-get update && apt-get install zip unzip

WORKDIR /usr/src/myapp

COPY composer.lock /usr/src/myapp/
COPY composer.json /usr/src/myapp/

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && php composer.phar install --no-dev --no-scripts --no-autoloader

COPY . /usr/src/myapp

COPY .env.prod /usr/src/myapp/.env

RUN php composer.phar dump-autoload --optimize \
    && php composer.phar run-script post-root-package-install \
    && rm composer.phar

RUN php artisan optimize

CMD ["php", "artisan", "crawl:now"]
