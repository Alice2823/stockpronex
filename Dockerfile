FROM php:8.2-cli

# Install system dependencies

RUN apt-get update && apt-get install -y 
libpng-dev 
libjpeg-dev 
libfreetype6-dev 
zip 
unzip 
git 
curl 
libzip-dev

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

# Laravel setup

RUN php artisan key:generate || true
RUN php artisan config:cache || true

# IMPORTANT: Run migrations automatically

RUN php artisan migrate --force || true

# Expose port

EXPOSE 8080

# Start Laravel server

CMD php -S 0.0.0.0:8080 -t public
