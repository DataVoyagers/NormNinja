#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Wait for database to be ready (if DB_HOST is set)
if [ -n "$DB_HOST" ]; then
    echo "⏳ Waiting for database to be ready..."
    timeout 60 bash -c 'until nc -z $DB_HOST ${DB_PORT:-5432}; do sleep 1; done' || echo "Warning: Could not connect to database"
fi

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force --no-interaction || echo "Warning: Migrations failed"

# Clear and cache configurations
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link || true

echo "✅ Application ready!"

# Execute the main command (Apache)
exec "$@"
