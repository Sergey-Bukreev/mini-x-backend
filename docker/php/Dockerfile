# Берём официальный PHP-образ с PHP-FPM
# PHP-FPM будет выполнять Laravel/PHP-код
FROM php:8.3-fpm

# Ставим системные зависимости:
# git/unzip/zip/curl — нужны Composer и пакетам
# libpq-dev — нужен для PostgreSQL extension
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Устанавливаем Composer из официального composer image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Рабочая папка внутри контейнера
WORKDIR /var/www/html
