# Dynamic Favicon Setup Guide

## Overview
Your Laravel application now supports dynamic favicon management through the admin panel. You can upload and change your website's favicon without touching any code.

## Features Added

### 1. **Favicon Upload**
- Upload favicon in PNG, ICO, or SVG format
- Maximum file size: 1MB
- Recommended size: 32x32 pixels

### 2. **Apple Touch Icon**
- Upload icon for Apple devices
- Maximum file size: 1MB
- Recommended size: 180x180 pixels

### 3. **Company Logo**
- Upload company logo in multiple formats
- Support for light and dark versions
- Maximum file size: 2MB

## How to Use

### Step 1: Access Admin Panel
1. Go to your admin panel: `yourdomain.com/admin`
2. Navigate to **Admin** → **Settings**

### Step 2: Upload Favicon
1. In the **Website Branding** section
2. Click on **Favicon (32x32)** upload field
3. Select your favicon file (PNG, ICO, or SVG)
4. Click **Save**

### Step 3: Upload Apple Touch Icon (Optional)
1. In the same section, click on **Apple Touch Icon (180x180)**
2. Upload a 180x180 pixel PNG or JPG image
3. Click **Save**

### Step 4: Upload Company Logo (Optional)
1. Upload your company logo in the **Company Logo** field
2. Optionally upload a light version for dark backgrounds
3. Click **Save**

## File Requirements

### Favicon
- **Format**: PNG, ICO, SVG
- **Size**: 32x32 pixels (recommended)
- **Max Size**: 1MB

### Apple Touch Icon
- **Format**: PNG, JPG
- **Size**: 180x180 pixels (recommended)
- **Max Size**: 1MB

### Company Logo
- **Format**: PNG, JPG, SVG, WebP
- **Size**: 180x60 pixels (recommended)
- **Max Size**: 2MB

## Technical Details

### Storage Location
- Favicons: `storage/app/public/favicons/`
- Logos: `storage/app/public/logos/`

### Database Fields Added
- `general.favicon`
- `general.favicon_32x32`
- `general.favicon_16x16`
- `general.apple_touch_icon`
- `general.company_logo`
- `general.company_logo_light`

### Helper Methods
```php
$settings = app(\App\Settings\GeneralSettings::class);

// Get favicon URL
$faviconUrl = $settings->getFaviconUrl();

// Get Apple touch icon URL
$appleIconUrl = $settings->getAppleTouchIconUrl();

// Get company logo URL
$logoUrl = $settings->getCompanyLogoUrl();
```

## Browser Cache
After uploading a new favicon:
1. **Clear your browser cache** (Ctrl+F5 or Cmd+Shift+R)
2. **Hard refresh** the page to see the new favicon
3. It may take a few minutes for the favicon to update across all browsers

## Troubleshooting

### Favicon Not Showing on Live Server
This is the most common issue on shared hosting environments.

#### Quick Fix for Live Server:
```bash
# On your live server, run these commands:
cd /path/to/your/laravel/project

# Create storage directory manually
mkdir -p public/storage

# Copy files from storage to public
cp -r storage/app/public/* public/storage/

# Set permissions
chmod -R 755 public/storage/
chmod -R 755 storage/

# Clear caches
php artisan config:clear
php artisan cache:clear
```

#### Alternative: Use the Setup Script
```bash
# Upload the setup_favicon_live.sh script to your server
# Then run:
chmod +x setup_favicon_live.sh
./setup_favicon_live.sh
```

#### Check File Structure:
```bash
# Verify files exist
ls -la public/storage/favicons/
ls -la storage/app/public/favicons/

# Check if storage link exists
ls -la public/storage
```

### Favicon Not Showing (General)
1. Check if the file was uploaded successfully
2. Verify the file format is supported
3. Clear browser cache (Ctrl+F5 or Cmd+Shift+R)
4. Check browser developer tools for any errors
5. Verify the favicon URL is accessible in browser

### File Upload Issues
1. Ensure file size is within limits
2. Check file format is supported
3. Verify storage directory permissions
4. Check Laravel storage link is created

### Storage Link Issues
If `symlink()` is disabled on your hosting:

#### Manual Setup (Recommended for Shared Hosting):
```bash
# Instead of php artisan storage:link, use:
mkdir -p public/storage
cp -r storage/app/public/* public/storage/
chmod -R 755 public/storage/
```

#### Update .env File:
```bash
# Change this in your .env file:
FILESYSTEM_DISK=public_direct
# or
FILESYSTEM_DISK=shared_hosting
```

## Migration
The favicon settings migration has been automatically run. If you need to run it manually:
```bash
php artisan migrate
```

## Benefits
- **No Code Changes**: Change favicon without touching code
- **Multiple Formats**: Support for various image formats
- **Responsive**: Different sizes for different devices
- **User-Friendly**: Simple admin interface
- **Fallback**: Default favicon if none is uploaded 