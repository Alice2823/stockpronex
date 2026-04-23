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
    libpq-dev   # ✅ IMPORTANT FOR POSTGRES

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql zip gd  # ✅ ADD THESE

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Clear cache
RUN php artisan config:clear \
 && php artisan route:clear \
 && php artisan cache:clear \
 && php artisan view:clear

# Generate key
RUN php artisan key:generate || true

# Expose port
EXPOSE 8080

# Start app
CMD sleep 20 && php artisan migrate --force && php -S 0.0.0.0:8080 -t public
