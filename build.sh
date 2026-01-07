#!/usr/bin/env bash
# exit on error
set -o errexit

echo "🚀 Starting NormNinja build process..."

# Install Composer dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Install Node dependencies
echo "📦 Installing Node dependencies..."
npm ci

# Build frontend assets
echo "🎨 Building frontend assets..."
npm run build

# Clear and cache configuration
echo "⚙️ Caching configuration..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force --no-interaction

# Create sessions table if using database sessions
echo "📊 Setting up sessions table..."
php artisan session:table --force 2>/dev/null || true
php artisan migrate --force --no-interaction

echo "✅ Build completed successfully!"