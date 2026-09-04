FROM php:8.3-apache

# System packages + PHP extensions required by Symfony/SQLite
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libsqlite3-dev \
    && docker-php-ext-install \
        intl \
        pdo_sqlite \
        zip \
    && rm -rf /var/lib/apt/lists/*

# Apache: enable URL rewriting
RUN a2enmod rewrite

# Symfony public/ is the web root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri \
    -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install \
    --optimize-autoloader \
    --no-interaction

# Symfony needs write access to var/
RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data var

# Render provides the port through $PORT
CMD ["sh", "-c", "sed -i \"s/Listen 80/Listen ${PORT:-10000}/\" /etc/apache2/ports.conf && sed -i \"s/:80>/:${PORT:-10000}>/\" /etc/apache2/sites-available/000-default.conf && apache2-foreground"]