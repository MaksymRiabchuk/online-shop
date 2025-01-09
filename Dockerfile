FROM php:8.2-fpm

ARG user=defaultuser
ARG uid=1000

RUN apt update && apt install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev

RUN apt clean && rm -rf /var/lib/apt/lists/*


RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN echo "Creating user $user with UID $uid" && \
    useradd -G www-data,root -u $uid -d /home/$user $user && \
    mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www

RUN chown -R $user:$user /var/www

USER $user


