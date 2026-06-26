#!/bin/bash
set -e

# Use PORT from environment (Render sets this), default to 80
export PORT=${PORT:-80}

echo "==> Starting with PORT=$PORT"

# --- Write .env from Render environment variables ---
cd /var/www/html

echo "==> Writing .env from environment variables..."
cat > .env <<EOF
APP_NAME="${APP_NAME:-Toko Beras Jagat Nusantara}"
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-https://tokoberasjagatnusantara.onrender.com}

LOG_CHANNEL=${LOG_CHANNEL:-stderr}
LOG_LEVEL=${LOG_LEVEL:-error}

DB_CONNECTION=${DB_CONNECTION:-mysql}
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

BROADCAST_DRIVER=${BROADCAST_DRIVER:-log}
CACHE_DRIVER=${CACHE_DRIVER:-file}
FILESYSTEM_DISK=${FILESYSTEM_DISK:-public}
QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}
SESSION_DRIVER=${SESSION_DRIVER:-file}
SESSION_LIFETIME=${SESSION_LIFETIME:-120}

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@tokoberasjagat.test"
MAIL_FROM_NAME="\${APP_NAME}"

STORE_NAME="${STORE_NAME:-Toko Beras Jagat Nusantara}"
STORE_BANK_NAME="${STORE_BANK_NAME:-Bank BCA}"
STORE_BANK_ACCOUNT="${STORE_BANK_ACCOUNT:-7112578865}"
STORE_BANK_HOLDER="${STORE_BANK_HOLDER:-Rifki Maulana}"
SHIPPING_FLAT_RATE=${SHIPPING_FLAT_RATE:-10000}

ASSET_URL=${ASSET_URL:-https://tokoberasjagatnusantara.onrender.com}
EOF

echo "==> .env written successfully"

# --- Fix Apache to listen on 0.0.0.0:$PORT ---
sed -i "s/^Listen 80$/Listen 0.0.0.0:${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/*.conf
echo "ServerName localhost" >> /etc/apache2/apache2.conf

echo "==> Apache configured to listen on 0.0.0.0:${PORT}"

# --- Fix permissions ---
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# --- Clear Laravel caches ---
php artisan config:clear || true
php artisan view:clear   || true
php artisan route:clear  || true

# --- Run migrations and seeders ---
echo "==> Running migrations..."
php artisan migrate --force || true

echo "==> Running seeders..."
php artisan db:seed --force || true

# --- Create storage symlink ---
php artisan storage:link || true

echo "==> Starting Apache in foreground..."
exec apache2-foreground
