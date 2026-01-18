#!/bin/bash
set -e

echo "Starting Kairo Backend..."

git config --global --add safe.directory /var/www/html

if [ ! -f ".env" ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

if [ ! -z "$DB_CONNECTION" ]; then
    echo "Updating database configuration in .env..."
    sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=${DB_CONNECTION}/" .env
    sed -i "s/DB_HOST=.*/DB_HOST=${DB_HOST}/" .env
    sed -i "s/DB_PORT=.*/DB_PORT=${DB_PORT}/" .env
    sed -i "s/DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE}/" .env
    sed -i "s/DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME}/" .env
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/" .env
    if grep -q "DB_SOCKET=" .env; then
        sed -i "s/DB_SOCKET=.*/DB_SOCKET=/" .env
    else
        echo "DB_SOCKET=" >> .env
    fi
fi

echo "Installing composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "Generating application key..."
php artisan key:generate --force

echo "Running migrations..."
php artisan migrate:fresh --seed --force

echo "Linking storage..."
php artisan storage:link --force

echo "Installing pnpm dependencies..."
if [ -d "node_modules" ]; then
    echo "Removing existing node_modules..."
    rm -rf node_modules 2>/dev/null || {
        echo "Could not remove node_modules directory (busy). Removing contents instead..."
        rm -rf node_modules/* node_modules/.[!.]* node_modules/..?* 2>/dev/null || true
    }
fi
pnpm install --no-frozen-lockfile

echo "Building assets..."
pnpm run build

echo "Starting application..."
exec "$@"
