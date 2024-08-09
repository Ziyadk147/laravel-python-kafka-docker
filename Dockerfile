FROM php:8.2-fpm

ARG user
ARG uid

RUN apt update && apt install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    librdkafka-dev \
    build-essential 

RUN apt install -y libzip-dev
RUN apt clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip
RUN pecl install rdkafka 
RUN docker-php-ext-enable rdkafka
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user




RUN composer require mateusjunges/laravel-kafka && composer require pusher/pusher-php-server


RUN composer update
RUN composer dump-autoload



WORKDIR /var/www

USER $user
