# syntax = docker/dockerfile:1

ARG PHP_VERSION=8.3
ARG NODE_VERSION=20

FROM php:${PHP_VERSION}-fpm-alpine AS base

LABEL runtime="laravel"

# Install system dependencies
RUN apk add --no-cache \
    ca-certificates \
    curl \
    libpq-dev \
    build-base \
    git \
    linux-headers \
    nginx

# Install Node.js
RUN apk add --no-cache nodejs npm

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pdo_sqlite \
    bcmath \
    ctype \
    fileinfo \
    json \
    mbstring \
    tokenizer \
    xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
ENV PATH="/app/vendor/bin:$PATH"

# Build stage
FROM base as build

COPY composer.* ./
RUN composer install \
    --prefer-dist \
    --no-dev \
    --no-progress \
    --no-interaction \
    --no-scripts \
    --no-autoloader

COPY . .

RUN composer dump-autoload --optimize --no-dev

RUN npm install && npm run build

# Final stage
FROM base

# Install supervisor for running both PHP-FPM and Nginx
RUN apk add --no-cache supervisor

# Create necessary directories
RUN mkdir -p /app/storage/logs \
    && mkdir -p /app/storage/framework/cache \
    && mkdir -p /app/storage/framework/sessions \
    && mkdir -p /app/storage/framework/views \
    && mkdir -p /run/php \
    && mkdir -p /var/log/supervisor

COPY --from=build --chown=www-data:www-data /app /app

# Copy Nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/conf.d/default.conf

# Copy supervisor configuration
COPY docker/supervisord.conf /etc/supervisord.conf

# Copy PHP-FPM configuration
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Create .env file from .env.example if needed
RUN if [ ! -f /app/.env ]; then cp /app/.env.example /app/.env; fi

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
