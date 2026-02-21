#!/bin/bash

echo "🚀 Preparing CMOS for cPanel Deployment..."

# Install/update dependencies
echo "📦 Installing production dependencies..."
composer install --optimize-autoloader --no-dev

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Preparation complete!"
echo ""
echo "📝 Next steps:"
echo "1. Create ZIP file with: zip -r cmos-deploy.zip . -x '*.git*' 'node_modules/*' '.env' 'storage/logs/*'"
echo "2. Upload to cPanel"
echo "3. Follow cpanel-deployment-guide.md"
