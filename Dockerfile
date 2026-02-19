# syntax = docker/dockerfile:1

# Adjust BUN_VERSION as desired
ARG PHP_VERSION=8.3
ARG NODE_VERSION=20
FROM php:${PHP_VERSION}-cli-alpine AS base

LABEL fly_launch_runtime="laravel"

# PHP and Node.js extensions
RUN apk add --no-cache \
    ca-certificates \
    curl \
    libpq-dev \
    build-base \
    git \
    linux-headers

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

# build stage
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

RUN npm install

RUN npm run build

# final stage
FROM base

# Install ca-certificates for HTTPS
RUN apk add --no-cache ca-certificates

# Ensure the storage directory exists and is writable
RUN mkdir -p /app/storage/logs
RUN mkdir -p /app/storage/framework/cache
RUN mkdir -p /app/storage/framework/sessions
RUN mkdir -p /app/storage/framework/views

COPY --from=build --chown=www-data:www-data /app /app

EXPOSE 8000

# Create .env file if it doesn't exist
RUN if [ ! -f /app/.env ]; then cp /app/.env.example /app/.env; fi

WORKDIR /app

CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"]
