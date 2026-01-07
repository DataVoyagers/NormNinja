#!/usr/bin/env bash
# Render Build Script for Laravel Application

set -o errexit

echo "🚀 Starting Render build process..."

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Install Node dependencies
echo "📦 Installing Node dependencies..."
npm ci

# Build frontend assets
echo "🎨 Building frontend assets with Vite..."
npm run build

# Create .env if it doesn't exist (Render will use environment variables)
if [ ! -f .env ]; then
    echo "📝 Creating .env from .env.example..."
    cp .env.example .env
fi

# Generate application key if not set
echo "🔑 Generating application key..."
php artisan key:generate --force

# Cache configuration
echo "⚡ Caching configuration..."
php artisan config:cache

# Cache routes
echo "⚡ Caching routes..."
php artisan route:cache

# Cache views
echo "⚡ Caching views..."
php artisan view:cache

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link || true

# Set permissions
echo "🔒 Setting storage permissions..."
chmod -R 775 storage bootstrap/cache

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force --no-interaction

echo "✅ Build completed successfully!"
