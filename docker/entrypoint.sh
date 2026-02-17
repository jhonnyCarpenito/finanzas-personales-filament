#!/bin/bash
set -e

cd /var/www/html

# Si no hay .env, usar .env.example como base (CapRover sobrescribe con sus env vars)
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Migraciones (--force para producción sin confirmación)
php artisan migrate --force --no-interaction || true

# Seeders: admin, tags globales y (solo la primera vez) transacciones de ejemplo
php artisan db:seed --force --no-interaction || true

# Cache config and routes (optional; uncomment if env is fully set at build time)
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

# Start PHP-FPM in background, then Nginx in foreground
php-fpm &
exec nginx -g "daemon off;"
