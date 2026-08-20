FROM php:8.0-apache

# --- 系統套件 ---------------------------------------------------------------
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        zip \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# --- PHP extensions ---------------------------------------------------------
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        mbstring \
        pcntl \
        pdo_mysql \
        zip \
    && pecl install redis \
    && docker-php-ext-enable redis

# --- Apache -----------------------------------------------------------------
RUN a2enmod rewrite headers
COPY docker/php/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini

# --- Composer ---------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/tmp/composer
RUN mkdir -p /tmp/composer && chmod 777 /tmp/composer

WORKDIR /var/www/html

# 原始碼以 volume 掛進來，image 本身不 COPY 專案檔（本機開發用）
EXPOSE 80
