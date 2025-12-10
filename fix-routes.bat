@echo off
echo ========================================
echo Fixing Routes - Clearing All Caches
echo ========================================
echo.

echo [1/5] Clearing route cache...
php artisan route:clear

echo [2/5] Clearing config cache...
php artisan config:clear

echo [3/5] Clearing application cache...
php artisan cache:clear

echo [4/5] Clearing view cache...
php artisan view:clear

echo [5/5] Rebuilding config cache...
php artisan config:cache

echo.
echo ========================================
echo Done! Now test the URL again.
echo ========================================
echo.
echo Try accessing: http://127.0.0.1:8000/admin/api-sync
echo (Note: single slash, not double slash)
echo.
pause
