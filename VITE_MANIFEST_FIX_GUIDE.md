# 🔧 Vite Manifest Fix Guide

## Problem
```
Vite manifest not found at: /home/u950187903/domains/rajamanispyrotech.com/public_html/public/build/manifest.json
```

This error occurs when the built assets (JavaScript and CSS files) are missing from the live server's `public/build` directory.

## Quick Solutions

### Solution 1: Upload Built Assets (Recommended)

1. **Download the built assets from your local machine:**
   ```bash
   # The file build-assets.tar.gz has been created for you
   # Upload this file to your live server
   ```

2. **On your live server, extract the assets:**
   ```bash
   # Navigate to your Laravel project root
   cd /home/u950187903/domains/rajamanispyrotech.com/public_html
   
   # Extract the built assets
   tar -xzf build-assets.tar.gz
   
   # Set proper permissions
   chmod -R 755 public/build/
   find public/build/ -type f -exec chmod 644 {} \;
   ```

3. **Clear Laravel caches:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan optimize:clear
   ```

### Solution 2: Use the Fix Script

1. **Upload the fix script to your live server:**
   ```bash
   # Upload fix_vite_manifest_live.sh to your server
   ```

2. **Run the script:**
   ```bash
   chmod +x fix_vite_manifest_live.sh
   ./fix_vite_manifest_live.sh
   ```

### Solution 3: Build Assets on Live Server

If you have Node.js and npm installed on your live server:

1. **Install dependencies:**
   ```bash
   npm install --production=false
   ```

2. **Build assets:**
   ```bash
   npm run build
   ```

3. **Set permissions:**
   ```bash
   chmod -R 755 public/build/
   find public/build/ -type f -exec chmod 644 {} \;
   ```

## Manual Fix Steps

### Step 1: Check Current State
```bash
# Check if build directory exists
ls -la public/build/

# Check if manifest.json exists
ls -la public/build/manifest.json

# Check if assets directory exists
ls -la public/build/assets/
```

### Step 2: Create Missing Directories
```bash
# Create build directory if missing
mkdir -p public/build
mkdir -p public/build/assets
```

### Step 3: Create Basic Manifest (if needed)
If the manifest.json is missing, create a basic one:

```json
{
  "resources/js/app.js": {
    "file": "assets/app-913581b1.js",
    "src": "resources/js/app.js",
    "isEntry": true,
    "css": ["assets/app-065913f1.css"]
  },
  "resources/js/Pages/HomePage.vue": {
    "file": "assets/HomePage-3b08bd8a.js",
    "src": "resources/js/Pages/HomePage.vue",
    "isEntry": true,
    "css": ["assets/HomePage-c402b234.css"]
  },
  "resources/js/Pages/AboutUs.vue": {
    "file": "assets/AboutUs-b5434a50.js",
    "src": "resources/js/Pages/AboutUs.vue",
    "isEntry": true,
    "css": ["assets/AboutUs-a08c2e8c.css"]
  },
  "resources/js/Pages/OrderList.vue": {
    "file": "assets/OrderList-177d1472.js",
    "src": "resources/js/Pages/OrderList.vue",
    "isEntry": true,
    "css": ["assets/OrderList-ed340bf2.css"]
  }
}
```

### Step 4: Set Permissions
```bash
chmod -R 755 public/build/
find public/build/ -type f -exec chmod 644 {} \;
```

### Step 5: Clear Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

## Verification

After applying any solution, verify it worked:

1. **Check if manifest.json exists:**
   ```bash
   ls -la public/build/manifest.json
   ```

2. **Check if assets exist:**
   ```bash
   ls -la public/build/assets/ | head -10
   ```

3. **Test the website:**
   - Visit your website
   - Check browser console for errors
   - Verify pages load correctly

## Common Issues and Solutions

### Issue: "npm not found"
**Solution:** Install Node.js and npm on your server, or use Solution 1 (upload pre-built assets).

### Issue: "Permission denied"
**Solution:** Set proper permissions:
```bash
chmod -R 755 public/build/
find public/build/ -type f -exec chmod 644 {} \;
```

### Issue: "Build failed"
**Solution:** Check if all dependencies are installed:
```bash
npm install --production=false
npm run build
```

### Issue: "Still getting manifest error"
**Solution:** 
1. Clear browser cache (Ctrl+F5)
2. Check if the manifest.json file is accessible via browser
3. Verify file permissions
4. Check web server configuration

## Prevention

To prevent this issue in the future:

1. **Always upload built assets** when deploying to live server
2. **Include build directory** in your deployment process
3. **Set up automated builds** if possible
4. **Keep a backup** of the build directory

## Files Created for You

1. **`build-assets.tar.gz`** - Compressed built assets ready for upload
2. **`fix_vite_manifest_live.sh`** - Automated fix script
3. **`VITE_MANIFEST_FIX_GUIDE.md`** - This guide

## Quick Commands Summary

```bash
# Upload and extract assets
tar -xzf build-assets.tar.gz
chmod -R 755 public/build/

# Or run the fix script
./fix_vite_manifest_live.sh

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

## Support

If you're still having issues:

1. Check the browser console for specific error messages
2. Verify file permissions on the server
3. Check if your web server is configured to serve static files
4. Ensure the `.env` file has correct settings for production

The most reliable solution is **Solution 1** - uploading the pre-built assets from your local machine. 

## 🔍 **DEBUG: Laravel Application Issue**

### **Upload and Run the Debug Script:**

**Step 1:** Upload `debug_laravel_app.sh` to your live server

**Step 2:** On your live server, run:
```bash
<code_block_to_apply_changes_from>
```

### **What This Script Will Check:**

✅ **Laravel setup** - Basic Laravel functionality
✅ **Environment configuration** - .env file settings
✅ **Database connection** - Database connectivity
✅ **File permissions** - Storage and cache permissions
✅ **Laravel logs** - Error logs and recent issues
✅ **Routes** - Route configuration
✅ **Inertia setup** - Frontend framework setup
✅ **PHP configuration** - Server settings

### **Test URLs After Running:**

1. **Debug page:** https://rajamanispyrotech.com/debug.html
2. **Laravel API test:** https://rajamanispyrotech.com/test-laravel
3. **Main site:** https://rajamanispyrotech.com/

### **Common Issues That Cause Blank Pages:**

1. **Database connection failed** - Laravel can't connect to database
2. **Missing .env configuration** - Environment variables not set
3. **File permission issues** - Laravel can't write to storage/cache
4. **PHP configuration problems** - Memory limits, execution time
5. **Route issues** - Routes not loading properly
6. **Inertia configuration** - Frontend framework not set up

### **Quick Manual Check:**

While the script runs, you can also check:

```bash
# Check Laravel logs
tail -20 storage/logs/laravel.log

# Check if .env exists and has database config
cat .env | grep -E "DB_|APP_"

# Test database connection
php artisan tinker --execute="DB::connection()->getPdo();"
```

**Run the debug script and let me know what it shows - this will identify exactly why the Laravel app is showing blank!** 