FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    default-mysql-client

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# ✅ IMPORTANT FIX (ADD THIS)
RUN php artisan config:clear && php artisan cache:clear

# Laravel setup
RUN php artisan key:generate || true

# Expose port
EXPOSE 8080

# START (WAIT FOR DB + RUN MIGRATION + START SERVER)
CMD sleep 20 && php artisan config:clear && php artisan cache:clear && php artisan config:cache && php artisan migrate --force && php -S 0.0.0.0:8080 -t public
