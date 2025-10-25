#!/usr/bin/env bash
set -o errexit

echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "Creating directories..."
mkdir -p storage/uploads /tmp/sessions
chmod -R 755 storage /tmp/sessions

echo "Setting up database..."
# Run database setup (will fail on build but that's okay - it'll run on startup)
php cli.php || echo "Database setup will run on application startup"

echo "Build completed successfully!"