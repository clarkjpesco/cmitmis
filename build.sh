#!/usr/bin/env bash

# Exit on error
set -o errexit

echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "Creating storage directories..."
mkdir -p storage/uploads
mkdir -p /tmp/sessions
chmod -R 755 storage
chmod -R 755 /tmp/sessions

echo "Setting up database..."
# Only run database setup if not already set up
php cli.php || echo "Database setup completed or skipped"

echo "Build completed successfully!"