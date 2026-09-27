# ==============================================================================
# Stage 1: Build Frontend Assets & PHP Dependencies
# ==============================================================================
FROM php:8.5-fpm-alpine AS builder

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo_mysql bcmath zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application files
COPY . .

# Ensure Laravel cache directories exist before Composer runs post-autoload scripts
RUN mkdir -p bootstrap/cache storage/framework/cache storage/framework/sessions storage/framework/views database && chmod -R 775 bootstrap/cache storage/framework database

# Install PHP production dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Create a default local environment if one is not supplied and generate the app key
RUN if [ ! -f .env ]; then cp .env.example .env; fi && php artisan key:generate --force

# Ensure the default SQLite database file exists for the app to boot with the bundled config
RUN if [ ! -f database/database.sqlite ]; then touch database/database.sqlite; fi && chmod 664 database/database.sqlite

# Install Node dependencies and compile frontend assets
RUN if [ -f package.json ]; then npm ci && npm run build; fi

# ==============================================================================
# Stage 2: Final Production Image
# ==============================================================================
FROM php:8.5-fpm-alpine

# Install production-only runtime dependencies (e.g., Nginx, Supervisor)
RUN apk add --no-cache nginx supervisor

# Install Composer for maintenance inside the runtime container
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP extensions needed at runtime
RUN docker-php-ext-install pdo_mysql bcmath

# Set up working directory
WORKDIR /var/www/html

# Copy built application files from the builder stage
COPY --from=builder --chown=nginx:nginx /app /var/www/html

# Laravel writes cached views, sessions, logs, and SQLite data as the PHP-FPM user
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Copy system configurations (Nginx & Supervisor)
COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose HTTP port
EXPOSE 80

# Start Supervisor to run both Nginx and PHP-FPM
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
