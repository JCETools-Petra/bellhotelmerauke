# 🚀 Quick Setup: Hoteliermarket API Integration

## Langkah 1: Update File .env

Tambahkan konfigurasi berikut di file `.env`:

```bash
# Hoteliermarket API Configuration
HOTELIERMARKET_API_KEY=htk_aUdCDfyacGPa2DY8oOSmPlKvj8xbf1oSibMmbQMlcN8CF71Y
HOTELIERMARKET_API_URL=https://hoteliermarket.my.id
HOTELIERMARKET_PROPERTY_ID=13
HOTELIERMARKET_TIMEOUT=30
```

## Langkah 2: Run Migration

```bash
php artisan migrate
```

## Langkah 3: Test API Connection

```bash
php artisan hoteliermarket:sync --test
```

Output yang diharapkan:
```
🔍 Testing API connection...

✅ Connection successful!
   Status Code: 200
   Message: Connection successful
```

## Langkah 4: Manual Sync (Optional)

```bash
php artisan hoteliermarket:sync
```

## Langkah 5: Setup Cron Job (Production Only)

Tambahkan di crontab:

```bash
* * * * * cd /path/to/bellhotelmerauke && php artisan schedule:run >> /dev/null 2>&1
```

## Akses Admin Dashboard

1. Login sebagai Admin
2. Buka: `/admin/api-sync`
3. Klik **Test Connection** untuk validasi
4. Klik **Sync Now** untuk manual sync

---

## 📖 Dokumentasi Lengkap

Lihat: `docs/HOTELIERMARKET_API_INTEGRATION.md`

---

## ✅ Checklist Setup

- [ ] Update `.env` dengan API key
- [ ] Run `php artisan migrate`
- [ ] Test koneksi: `php artisan hoteliermarket:sync --test`
- [ ] Manual sync pertama: `php artisan hoteliermarket:sync`
- [ ] Setup cron job (production)
- [ ] Akses admin dashboard untuk monitoring

---

**Last Updated:** 2025-12-10
