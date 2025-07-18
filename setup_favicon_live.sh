#!/bin/bash

# Favicon Setup Script for Live Server
# This script helps set up the favicon on shared hosting environments

echo "🚀 Setting up favicon for live server..."

# Check if we're in the Laravel project directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Please run this script from your Laravel project root directory"
    exit 1
fi

echo "📁 Creating storage directories..."

# Create storage directories
mkdir -p public/storage
mkdir -p storage/app/public/favicons
mkdir -p storage/app/public/logos
mkdir -p storage/app/public/sliders
mkdir -p storage/app/public/products
mkdir -p storage/app/public/categories
mkdir -p storage/app/public/gallery
mkdir -p storage/app/public/testimonials

echo "📋 Copying files from storage to public..."

# Copy all files from storage/app/public to public/storage
if [ -d "storage/app/public" ]; then
    cp -r storage/app/public/* public/storage/ 2>/dev/null || echo "⚠️  Some files may already exist"
else
    echo "⚠️  storage/app/public directory not found"
fi

echo "🔐 Setting permissions..."

# Set proper permissions
chmod -R 755 public/storage/
chmod -R 755 storage/

echo "🗄️  Running migrations..."

# Run migrations
php artisan migrate --force

echo "🧹 Clearing caches..."

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "✅ Setup complete!"
echo ""
echo "📝 Next steps:"
echo "1. Upload your favicon through the admin panel"
echo "2. Clear your browser cache (Ctrl+F5)"
echo "3. Test the favicon on different browsers"
echo ""
echo "🔧 If favicon still doesn't show:"
echo "1. Check if files exist in public/storage/favicons/"
echo "2. Verify file permissions (755)"
echo "3. Check browser developer tools for errors"
echo ""
echo "📁 File locations:"
echo "- Favicons: public/storage/favicons/"
echo "- Logos: public/storage/logos/"
echo "- Sliders: public/storage/sliders/"
echo "- Products: public/storage/products/"
echo "- Categories: public/storage/categories/"
echo "- Gallery: public/storage/gallery/"
echo "- Admin panel: yourdomain.com/admin → Settings"
echo ""
echo "🎯 What's Fixed:"
echo "- Dynamic favicon support"
echo "- Slider images now work properly"
echo "- About Us page uses dynamic sliders"
echo "- All images use proper storage paths" 