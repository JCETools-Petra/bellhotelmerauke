# 🔧 Troubleshooting: API 403 Error

## Problem
```
❌ Connection failed!
Status Code: 403
Message: Connection failed: {"error":"Unauthorized","message":"This API key is not authorized for this property"}
```

## Solution Steps

### Step 1: Clear Laravel Cache
Laravel mungkin masih menggunakan config lama yang ter-cache.

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild config cache
php artisan config:cache
```

### Step 2: Verify .env Configuration
Pastikan API key di `.env` sudah benar (tanpa spasi atau karakter hidden):

```bash
HOTELIERMARKET_API_KEY=htk_aUdCDfyacGPa2DY8oOSmPlKvj8xbf1oSibMmbQMlcN8CF71Y
HOTELIERMARKET_API_URL=https://hoteliermarket.my.id
HOTELIERMARKET_PROPERTY_ID=13
HOTELIERMARKET_TIMEOUT=30
```

### Step 3: Test Again
```bash
php artisan hoteliermarket:sync --test
```

---

## Alternative Debugging

### Check What API Key is Actually Being Used

Create a temporary debug script `check-config.php`:

```php
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "API Key: " . config('services.hoteliermarket.api_key') . "\n";
echo "API URL: " . config('services.hoteliermarket.api_url') . "\n";
echo "Property ID: " . config('services.hoteliermarket.property_id') . "\n";
echo "Timeout: " . config('services.hoteliermarket.timeout') . "\n";
```

Run:
```bash
php check-config.php
```

Expected output:
```
API Key: htk_aUdCDfyacGPa2DY8oOSmPlKvj8xbf1oSibMmbQMlcN8CF71Y
API URL: https://hoteliermarket.my.id
Property ID: 13
Timeout: 30
```

---

## Possible Causes

### 1. **Config Cache Issue** ⭐ Most Likely
Laravel cached old config before you added the API key.

**Solution:** Run `php artisan config:clear` then test again.

---

### 2. **Wrong API Key in .env**
API key mungkin memiliki spasi atau karakter tersembunyi.

**Solution:**
- Copy-paste ulang API key dari source
- Pastikan tidak ada spasi di awal/akhir
- Pastikan tidak ada tanda kutip yang salah

---

### 3. **Allowed Origins Restriction**
API key mungkin dibatasi hanya untuk domain tertentu.

From your API key details:
```
Allowed Origins: All domains
```

This should NOT be the issue since "All domains" is allowed.

---

### 4. **API Key vs Property Mismatch**
Anda memiliki 2 API keys:

**API Key #1 (Bell Hotel):**
```
htk_V4lW7Ys2VJkaTbXXOQxwPK8tIScpCJbXakkC3tRbm8NjozlY
```

**API Key #2 (Sunny Day Inn - Property 13):** ✅
```
htk_aUdCDfyacGPa2DY8oOSmPlKvj8xbf1oSibMmbQMlcN8CF71Y
```

Make sure you're using API Key #2 for Property ID 13.

---

### 5. **Production vs Local Environment**
Cek apakah ada perbedaan behavior antara local dan production.

---

## Quick Fix Command

Run all these commands in sequence:

```bash
# 1. Clear all caches
php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear

# 2. Test connection
php artisan hoteliermarket:sync --test

# 3. If successful, try sync
php artisan hoteliermarket:sync
```

---

## If Still Not Working

### Manual API Test using cURL

Test directly without Laravel:

```bash
curl -H "X-API-Key: htk_aUdCDfyacGPa2DY8oOSmPlKvj8xbf1oSibMmbQMlcN8CF71Y" \
     https://hoteliermarket.my.id/api/properties/13/room-pricing
```

If this works but Laravel doesn't, then it's a Laravel config issue.
If this also fails, then it's an API key/server issue.

---

## Contact Hoteliermarket Support

If none of the above works, contact Hoteliermarket support with:

1. API Key: `htk_aUdCDfyacGPa2DY8oOSmPlKvj8xbf1oSibMmbQMlcN8CF71Y`
2. Property ID: `13`
3. Error: "This API key is not authorized for this property"
4. Question: "Is this API key correctly associated with Property ID 13?"

---

## Last Updated
2025-12-10
