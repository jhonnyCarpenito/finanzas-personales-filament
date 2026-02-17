# ============================================
# Stage 1: Build frontend assets
# ============================================
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json ./
RUN npm install

COPY vite.config.js ./
COPY resources ./resources
COPY public ./public

ARG VITE_APP_NAME=Laravel
RUN npm run build

# ============================================
# Stage 2: Composer dependencies
# ============================================
FROM composer:2 AS composer

WORKDIR /app

COPY composer.json composer.lock* ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --optimize --no-dev

# ============================================
# Stage 3: Production image
# ============================================
FROM php:8.2-fpm-bookworm

# System deps + PHP extensions for Laravel
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    default-libmysqlclient-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_sqlite \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Nginx runs as www-data; PHP-FPM also
RUN mkdir -p /var/www/html \
    && chown -R www-data:www-data /var/www/html \
    && chown -R www-data:www-data /var/lib/nginx

# Nginx config for Laravel
COPY docker/nginx/default.conf /etc/nginx/sites-available/default
RUN rm -f /etc/nginx/sites-enabled/default \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# PHP config
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" || true
COPY docker/php/php-production.ini /usr/local/etc/php/conf.d/99-production.ini

WORKDIR /var/www/html

# Copy application from composer stage
COPY --from=composer --chown=www-data:www-data /app /var/www/html

# Copy built assets from frontend stage
COPY --from=frontend --chown=www-data:www-data /app/public/build ./public/build

# Entrypoint runs migrations then starts services
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Laravel: directorios escribibles (storage, cache, SQLite)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
