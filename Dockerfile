# 1. Base image
FROM php:8.2-apache

# 2. Install system deps + cleanup in same layer
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libpng-dev libonig-dev libxml2-dev \
    nodejs npm \
    && docker-php-ext-install pdo pdo_mysql mbstring bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Enable mod_rewrite
RUN a2enmod rewrite

# 4. Set working directory
WORKDIR /var/www/html/

# 5. Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 6. Copy composer files first (better caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-autoloader

# 7. Copy package files and build frontend
COPY package*.json ./
RUN npm install --no-audit --no-fund && npm run build && rm -rf node_modules

# 8. Copy rest of project
COPY . .

# 9. Finish composer setup
RUN composer dump-autoload --optimize

# 10. Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 11. Fix Apache root
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# 12. Remove Node after build (saves ~100MB+ in final image)
RUN apt-get purge -y nodejs npm && apt-get autoremove -y

EXPOSE 80
CMD ["apache2-foreground"]

