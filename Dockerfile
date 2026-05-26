FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    zip \
    gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# App directory
WORKDIR /app

# Copy files
COPY . .

# Install Laravel packages
RUN composer install --no-dev --optimize-autoloader

# Create storage links and permissions
RUN chmod -R 777 storage bootstrap/cache || true

# Expose port
EXPOSE 10000


# Start app
CMD sh -c "php artisan config:clear && php artisan cache:clear && php artisan key:generate --force && php artisan migrate --force || true && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"