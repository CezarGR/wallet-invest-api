FROM php:8.1-apache

ARG NEWUSER_UID=1000
ARG NEWUSER_GID=1000
ARG NEWUSER_NAME=wallet_invest_user

# Instalando o composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --version=2.0.9 && \
    rm composer-setup.php && \
    chmod +x composer.phar && \
    mv composer.phar /usr/local/bin/composer

RUN	apt-get update

RUN apt install zip unzip libzip-dev curl -y

RUN	apt-get update && \
    # Adiciona um novo usuário para resolver os problemas com permissões de arquivos entre o host e o container
    groupadd -g $NEWUSER_GID -o $NEWUSER_NAME; \
    useradd -m -u $NEWUSER_UID -g $NEWUSER_GID -o -s /bin/bash $NEWUSER_NAME; \
    mkdir -p /home/$NEWUSER_NAME/.git /home/$NEWUSER_NAME/.composer /home/$NEWUSER_NAME/.config;

# Instalando extensões do php
RUN docker-php-ext-install pdo pdo_mysql zip && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    rm -rf /tmp/pear

## App configurations
COPY ./docker/php.ini /usr/local/etc/php/php.ini
WORKDIR /app

## Apache configuration
RUN a2enmod rewrite; \
    rm -rf /var/www/html && \
    ln -s /app/public /var/www/html

## Dando permissão para o usuário
RUN chown $NEWUSER_NAME:$NEWUSER_NAME /app -R