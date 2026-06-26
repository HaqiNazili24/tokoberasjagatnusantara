#!/bin/bash
set -e

# Use PORT from environment (Render sets this), default to 80
export PORT=${PORT:-80}

echo "==> Starting with PORT=$PORT"

# --- Fix Apache to listen on 0.0.0.0:$PORT ---
# Replace Listen 80 with Listen 0.0.0.0:$PORT (force IPv4+IPv6 all interfaces)
sed -i "s/^Listen 80$/Listen 0.0.0.0:${PORT}/" /etc/apache2/ports.conf

# Update VirtualHost port in all enabled site configs
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/*.conf

# Ensure ServerName is set to suppress warning
echo "ServerName localhost" >> /etc/apache2/apache2.conf

echo "==> Apache configured to listen on 0.0.0.0:${PORT}"

# --- Update Laravel .env for production ---
cd /var/www/html

# Override APP_URL if RENDER_EXTERNAL_URL is set (Render provides this)
if [ -n "$RENDER_EXTERNAL_URL" ]; then
    sed -i "s|APP_URL=.*|APP_URL=${RENDER_EXTERNAL_URL}|" .env
fi

# Set APP_ENV to production on Render
sed -i "s|APP_ENV=.*|APP_ENV=production|" .env

# Override DB settings from environment if provided (set these in Render dashboard)
if [ -n "$DB_HOST" ]; then
    sed -i "s|DB_HOST=.*|DB_HOST=${DB_HOST}|" .env
fi
if [ -n "$DB_DATABASE" ]; then
    sed -i "s|DB_DATABASE=.*|DB_DATABASE=${DB_DATABASE}|" .env
fi
if [ -n "$DB_USERNAME" ]; then
    sed -i "s|DB_USERNAME=.*|DB_USERNAME=${DB_USERNAME}|" .env
fi
if [ -n "$DB_PASSWORD" ]; then
    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" .env
fi

# --- Fix permissions ---
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# --- Clear Laravel caches ---
php artisan config:clear || true
php artisan view:clear  || true
php artisan route:clear || true

# --- Run migrations and seeders ---
php artisan migrate --force || true
php artisan db:seed --force  || true

echo "==> Starting Apache in foreground..."
exec apache2-foreground
