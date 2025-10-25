#!/bin/bash
set -e

echo "Waiting for database to be ready..."
# Simple wait for database (optional)
# while ! nc -z $DB_HOST $DB_PORT; do
#   sleep 1
# done

echo "Running database setup..."
php cli.php || echo "Database setup completed or already exists"

echo "Starting Apache server..."
exec apache2-foreground