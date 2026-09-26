# ==============================================================================
# Stage 1: Build Frontend Assets & PHP Dependencies
# ==============================================================================
FROM php:8.3-fpm-alpine AS builder

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

# Install PHP production dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Install Node dependencies and compile frontend assets
RUN if [ -f package.json ]; then npm ci && npm run build; fi

# ==============================================================================
# Stage 2: Final Production Image
# ==============================================================================
FROM php:8.3-fpm-alpine

# Install production-only runtime dependencies (e.g., Nginx, Supervisor)
RUN apk add --no-cache nginx supervisor

# Install PHP extensions needed at runtime
RUN docker-php-ext-install pdo_mysql bcmath

# Set up working directory
WORKDIR /var/www/html

# Copy built application files from the builder stage
COPY --from=builder --chown=nginx:nginx /app /var/www/html

# Copy system configurations (Nginx & Supervisor)
COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose HTTP port
EXPOSE 80

# Start Supervisor to run both Nginx and PHP-FPM
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
