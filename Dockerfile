# 1. Base image with PHP
FROM php:8.2-cli

# 2. Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# 3. Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring bcmath gd

# 4. Set working directory
WORKDIR /var/www

# 5. Copy composer first (for caching)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader

# 6. Copy project files
COPY . .

# 7. Expose port (Render will set $PORT)
EXPOSE 10000

# 8. Start Laravel (use Render’s $PORT env variable)
CMD php artisan serve --host=0.0.0.0 --port=$PORT
