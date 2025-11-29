# Deployment Cache Fix

## Issue Description

**Error:** `Route [affiliate.special_mice.index] not defined`

**Location:** `/resources/views/frontend/affiliate/dashboard.blade.php`

## Root Cause

This error occurs when the Laravel view cache on the production server contains an outdated reference to a route that no longer exists or has been renamed. The production server's compiled view cache references `affiliate.special_mice.index`, but the current codebase correctly uses `affiliate.mice-kit.index`.

## Solution

Clear the Laravel cache on the production server to force recompilation of views with the current code.

### Option 1: Using the Deployment Script (Recommended)

1. Upload `deploy-cache-clear.sh` to the production server
2. SSH into the production server
3. Run the script:
   ```bash
   bash deploy-cache-clear.sh
   ```

### Option 2: Manual Cache Clearing

SSH into the production server and run these commands:

```bash
cd /home/belg7925/public_html

# Clear all caches
php artisan view:clear
php artisan route:clear
php artisan config:clear
php artisan cache:clear

# Optimize the application
php artisan optimize
```

### Option 3: Using the Deployment Endpoint (If Available)

If you have set up a deployment webhook or endpoint, trigger it to automatically clear caches.

## Verification

After clearing the cache, visit the affiliate dashboard to verify the error is resolved:
- URL: `https://yourdomain.com/affiliate/dashboard`
- The "Digital MICE Kit" button should work without errors

## Prevention

To prevent this issue in future deployments:

1. **Always clear cache after deployment:**
   ```bash
   php artisan optimize:clear
   ```

2. **Add cache clearing to your deployment process:**
   - If using Git hooks, add cache clearing commands
   - If using CI/CD, include cache clearing in the pipeline

3. **Consider using zero-downtime deployment tools** that handle cache clearing automatically

## Current Route Definition

The correct route is defined in `routes/web.php`:

```php
Route::get('/mice-kit', [App\Http\Controllers\Affiliate\MiceKitController::class, 'index'])
    ->name('mice-kit.index');
```

With the `affiliate.` prefix from the route group, the full route name is:
- **Route Name:** `affiliate.mice-kit.index`
- **URL:** `/affiliate/mice-kit`

## Related Files

- View file: `resources/views/frontend/affiliate/dashboard.blade.php` (line 12)
- Route definition: `routes/web.php` (line 94)
- Controller: `app/Http/Controllers/Affiliate/MiceKitController.php`
