# NormNinja - Deployment Guide

## Quick Setup Instructions

### Step 1: Prerequisites
Ensure you have:
- PHP 8.1+ installed
- Composer installed
- MySQL/PostgreSQL database
- Web server (Apache/Nginx)

### Step 2: Project Setup

1. **Navigate to project directory:**
```bash
cd normninja
```

2. **Install Composer dependencies:**
```bash
composer install
```

3. **Copy environment file:**
```bash
cp .env.example .env
```

4. **Generate application key:**
```bash
php artisan key:generate
```

### Step 3: Database Configuration

Edit `.env` file with your database credentials:

```env
APP_NAME=NormNinja
APP_ENV=local
APP_KEY=base64:... (generated automatically)
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=normninja
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 4: Database Setup

1. **Create database:**
```sql
CREATE DATABASE normninja;
```

2. **Run migrations:**
```bash
php artisan migrate
```

3. **Create admin user:**
```bash
php artisan tinker
```

Then in tinker:
```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@normninja.com',
    'password' => bcrypt('admin123'),
    'role' => 'admin',
    'is_active' => true
]);
```

### Step 5: Storage Setup

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public`.

### Step 6: File Permissions

Set proper permissions:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Step 7: Logo Setup

Place your NormNinja logo:
```bash
cp /path/to/your/logo.png public/images/logo.png
```

### Step 8: Start Development Server

```bash
php artisan serve
```

Access the application at: `http://localhost:8000`

## Production Deployment

### Apache Configuration

Create a virtual host file:

```apache
<VirtualHost *:80>
    ServerName normninja.yourdomain.com
    DocumentRoot /var/www/normninja/public

    <Directory /var/www/normninja/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/normninja-error.log
    CustomLog ${APACHE_LOG_DIR}/normninja-access.log combined
</VirtualHost>
```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name normninja.yourdomain.com;
    root /var/www/normninja/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Production Environment

Update `.env` for production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://normninja.yourdomain.com

# Use secure session and cache drivers
SESSION_DRIVER=database
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Optimization Commands

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

## Testing

### Create Test Users

#### Create Teacher:
```bash
php artisan tinker
```
```php
\App\Models\User::create([
    'name' => 'Test Teacher',
    'email' => 'teacher@normninja.com',
    'password' => bcrypt('teacher123'),
    'role' => 'teacher',
    'is_active' => true
]);
```

#### Create Student:
```php
\App\Models\User::create([
    'name' => 'Test Student',
    'email' => 'student@normninja.com',
    'password' => bcrypt('student123'),
    'role' => 'student',
    'student_id' => 'STU001',
    'is_active' => true
]);
```

## Default Login Credentials

After creating test users:

**Admin:**
- Email: admin@normninja.com
- Password: admin123

**Teacher:**
- Email: teacher@normninja.com
- Password: teacher123

**Student:**
- Email: student@normninja.com
- Password: student123

## File Upload Limits

Edit `php.ini` to increase upload limits:

```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
```

Restart your web server after changes.

## Backup

### Database Backup
```bash
php artisan backup:run
```

### Manual Backup
```bash
mysqldump -u username -p normninja > normninja_backup_$(date +%Y%m%d).sql
```

## Troubleshooting

### Issue: 500 Error
**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Issue: Storage link not working
**Solution:**
```bash
rm public/storage
php artisan storage:link
```

### Issue: Permission denied
**Solution:**
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Issue: Class not found
**Solution:**
```bash
composer dump-autoload
```

## Maintenance Mode

Enable maintenance mode:
```bash
php artisan down --message="Scheduled maintenance" --retry=60
```

Disable maintenance mode:
```bash
php artisan up
```

## Security Checklist

- [ ] Change all default passwords
- [ ] Set `APP_DEBUG=false` in production
- [ ] Use HTTPS (SSL certificate)
- [ ] Configure CORS properly
- [ ] Enable rate limiting
- [ ] Set secure session configuration
- [ ] Regular database backups
- [ ] Keep Laravel and dependencies updated
- [ ] Configure firewall rules
- [ ] Use environment variables for sensitive data

## Monitoring

### Check Logs
```bash
tail -f storage/logs/laravel.log
```

### Clear Logs
```bash
echo "" > storage/logs/laravel.log
```

## Updates

### Update Laravel
```bash
composer update
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Support

For issues or questions:
- Check logs: `storage/logs/laravel.log`
- Review documentation
- Contact Data Voyagers Team

---

**Developed by Data Voyagers Team**
