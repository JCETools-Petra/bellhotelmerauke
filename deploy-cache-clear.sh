#!/bin/bash

# Laravel Cache Clearing Script
# This script should be run on the production server to clear all cached files
# Usage: bash deploy-cache-clear.sh

echo "========================================"
echo "Laravel Cache Clearing Script"
echo "========================================"
echo ""

# Navigate to the application directory
cd /home/belg7925/public_html || { echo "Error: Could not navigate to application directory"; exit 1; }

echo "1. Clearing view cache..."
php artisan view:clear
echo "   ✓ View cache cleared"
echo ""

echo "2. Clearing route cache..."
php artisan route:clear
echo "   ✓ Route cache cleared"
echo ""

echo "3. Clearing config cache..."
php artisan config:clear
echo "   ✓ Config cache cleared"
echo ""

echo "4. Clearing application cache..."
php artisan cache:clear
echo "   ✓ Application cache cleared"
echo ""

echo "5. Optimizing application..."
php artisan optimize
echo "   ✓ Application optimized"
echo ""

echo "========================================"
echo "Cache clearing completed successfully!"
echo "========================================"
echo ""
echo "Note: The error 'Route [affiliate.special_mice.index] not defined'"
echo "should now be resolved as the view cache has been cleared."
echo ""
