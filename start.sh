#!/usr/bin/env bash
# Render Start Script for Laravel Application

set -o errexit

echo "🌐 Starting web server..."

# Run any pending migrations (in case new ones were added)
php artisan migrate --force --no-interaction || true

# Clear and cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP built-in server on the port Render provides
# Render sets the PORT environment variable
PORT="${PORT:-8000}"
php artisan serve --host=0.0.0.0 --port="$PORT" --no-reload
