#!/usr/bin/env sh
set -e

mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

if [ "${DB_CONNECTION:-}" = "sqlite" ] && [ -z "${DB_DATABASE:-}" ]; then
    touch database/database.sqlite
fi

chown -R www-data:www-data storage bootstrap/cache database/database.sqlite 2>/dev/null || true

php artisan storage:link --force >/dev/null 2>&1 || true
php artisan config:cache >/dev/null 2>&1 || true
php artisan view:cache >/dev/null 2>&1 || true

exec "$@"
