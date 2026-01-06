# -------------------------
# 1. Base image with PHP
# -------------------------
FROM php:8.2-cli

# -------------------------
# 2. Install system deps
# -------------------------
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev \
    nodejs npm

# -------------------------
# 3. Install PHP extensions
# -------------------------
RUN docker-php-ext-install pdo pdo_mysql mbstring bcmath gd

# -------------------------
# 4. Set working directory
# -------------------------
WORKDIR /var/www

# -------------------------
# 5. Copy project files
# -------------------------
COPY . .

# -------------------------
# 6. Install Composer
# -------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

# -------------------------
# 7. Build frontend (Vite)
# -------------------------
RUN npm install && npm run build

# -------------------------
# 8. Expose Render port
# -------------------------
EXPOSE 10000

# -------------------------
# 9. Start Laravel
# -------------------------
CMD php artisan serve --host=0.0.0.0 --port=10000
