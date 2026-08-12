#!/bin/bash

# Vercel Build Script for Laravel

echo "Starting Laravel build..."

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Create necessary directories
echo "Creating storage directories..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Clear and cache Laravel config
echo "Optimizing Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Build completed successfully!"
