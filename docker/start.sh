#!/usr/bin/env bash
set -e

# Render inyecta variables de entorno en runtime; Laravel las usa para produccion.
cd /var/www/html

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# El enlace de storage no debe romper el arranque si ya existe.
php artisan storage:link || true

# Ejecuta migraciones en Render cuando RUN_MIGRATIONS=true.
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

apache2-foreground
