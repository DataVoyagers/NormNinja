# 1. Base image
FROM php:8.2-apache

# 2. Install system deps
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev \
    nodejs npm \
    && docker-php-ext-install pdo pdo_mysql mbstring bcmath gd

# 3. Enable mod_rewrite for Laravel
RUN a2enmod rewrite

# 4. Set working directory
WORKDIR /var/www/html/

# 5. Copy project files
COPY . .

# 6. Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 7. Build frontend if exists
RUN npm install && npm run build || echo "No frontend build"

# 8. Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 9. Fix Apache root to Laravel public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# 10. Expose port 80 (Render uses $PORT automatically)
EXPOSE 80

# 11. Start Apache
CMD ["apache2-foreground"]
