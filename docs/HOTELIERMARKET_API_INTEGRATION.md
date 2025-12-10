# Integrasi API Hoteliermarket - Bell Hotel Merauke

## 📋 Daftar Isi

1. [Gambaran Umum](#gambaran-umum)
2. [Fitur](#fitur)
3. [Instalasi & Konfigurasi](#instalasi--konfigurasi)
4. [Penggunaan](#penggunaan)
5. [Arsitektur Sistem](#arsitektur-sistem)
6. [Troubleshooting](#troubleshooting)

---

## 🎯 Gambaran Umum

Sistem integrasi ini memungkinkan Bell Hotel Merauke untuk **secara otomatis** menyinkronkan harga kamar dari **Hoteliermarket API** ke sistem lokal.

### Yang Bisa Dilakukan:

✅ **Auto-sync harga harian** dari API Hoteliermarket
✅ **Manual sync** melalui admin dashboard
✅ **Test koneksi API** untuk memastikan kredensial benar
✅ **Tracking sumber data** (manual vs API)
✅ **Fallback system** jika API tidak tersedia
✅ **Scheduled sync** otomatis setiap hari pukul 02:00 WIB

---

## 🚀 Fitur

### 1. **Automatic Daily Sync**
- Berjalan otomatis setiap hari pukul **02:00 WIB**
- Update harga kamar dari API ke database lokal
- Logging lengkap untuk monitoring

### 2. **Admin Dashboard**
- Interface untuk monitoring status sync
- Tombol **Test Connection** untuk validasi API key
- Tombol **Sync Now** untuk manual sync
- Statistik lengkap (total rooms, price overrides, etc.)
- Lihat hasil sync terakhir

### 3. **Source Tracking**
- Setiap price override diberi label sumber: `manual` atau `hoteliermarket`
- Timestamp kapan data di-sync dari API
- External reference ID untuk tracking

### 4. **Error Handling & Logging**
- Semua error dicatat di log file
- Alert di dashboard jika sync gagal
- Retry mechanism dengan timeout

---

## ⚙️ Instalasi & Konfigurasi

### 1. **Setup Environment Variables**

Tambahkan konfigurasi berikut di file `.env`:

```bash
# Hoteliermarket API Configuration
HOTELIERMARKET_API_KEY=htk_aUdCDfyacGPa2DY8oOSmPlKvj8xbf1oSibMmbQMlcN8CF71Y
HOTELIERMARKET_API_URL=https://hoteliermarket.my.id
HOTELIERMARKET_PROPERTY_ID=13
HOTELIERMARKET_TIMEOUT=30
```

**Keterangan:**
- `HOTELIERMARKET_API_KEY`: API key dari Hoteliermarket
- `HOTELIERMARKET_API_URL`: Base URL API (default: https://hoteliermarket.my.id)
- `HOTELIERMARKET_PROPERTY_ID`: ID properti di Hoteliermarket (Bell Hotel = 13)
- `HOTELIERMARKET_TIMEOUT`: Timeout request dalam detik (default: 30)

### 2. **Run Database Migration**

```bash
php artisan migrate
```

Migration akan menambahkan field berikut ke tabel `price_overrides`:
- `source` (enum: 'manual', 'hoteliermarket', 'api')
- `api_synced_at` (timestamp)
- `external_reference_id` (string)

### 3. **Setup Cron Job (Production)**

Untuk menjalankan scheduled task, tambahkan cron job berikut di server:

```bash
* * * * * cd /path/to/bellhotelmerauke && php artisan schedule:run >> /dev/null 2>&1
```

Cron job ini akan mengecek setiap menit, dan Laravel akan menjalankan task sesuai jadwal yang dikonfigurasi.

---

## 📖 Penggunaan

### **1. Manual Sync via Admin Dashboard**

1. Login sebagai **Admin**
2. Buka menu **Admin** → **API Sync**
3. Klik tombol **Test Connection** untuk validasi API key
4. Klik tombol **Sync Now** untuk sinkronisasi manual

**Rate Limiting:**
Manual sync hanya bisa dilakukan **sekali per jam** untuk menghindari overload API.

---

### **2. Manual Sync via Artisan Command**

```bash
# Sync biasa
php artisan hoteliermarket:sync

# Test koneksi API tanpa sync
php artisan hoteliermarket:sync --test

# Force sync (abaikan rate limiting)
php artisan hoteliermarket:sync --force
```

**Output:**
```
🚀 Starting Hoteliermarket sync...

📡 Fetching data from Hoteliermarket API...

✅ Sync completed successfully!

┏━━━━━━━━━━━━━━━━━━┳━━━━━━━┓
┃ Metric           ┃ Count ┃
┣━━━━━━━━━━━━━━━━━━╋━━━━━━━┫
┃ Rooms Updated    ┃ 5     ┃
┃ Prices Created   ┃ 120   ┃
┃ Prices Updated   ┃ 30    ┃
┗━━━━━━━━━━━━━━━━━━┻━━━━━━━┛

🕐 Synced at: 2025-12-10 06:00:00
```

---

### **3. Automatic Scheduled Sync**

Scheduled task sudah dikonfigurasi di `routes/console.php`:

```php
Schedule::command('hoteliermarket:sync')
    ->dailyAt('02:00')
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping()
    ->onSuccess(function () {
        Log::info('Hoteliermarket sync completed successfully');
    })
    ->onFailure(function () {
        Log::error('Hoteliermarket sync failed');
    });
```

**Keterangan:**
- Berjalan setiap hari pukul **02:00 WIB**
- `withoutOverlapping()`: Mencegah sync berjalan bersamaan
- Success/Failure callback untuk logging

---

## 🏗️ Arsitektur Sistem

### **Files yang Dibuat:**

```
app/
├── Services/
│   └── HotelierMarketService.php        # Core service untuk API integration
├── Console/Commands/
│   └── SyncHotelierPrices.php           # Artisan command untuk sync
└── Http/Controllers/Admin/
    └── ApiSyncController.php            # Admin dashboard controller

database/migrations/
└── 2025_12_10_060000_add_api_sync_fields_to_price_overrides_table.php

resources/views/admin/api-sync/
└── index.blade.php                      # Admin dashboard view

routes/
├── web.php                              # Web routes (admin API sync routes)
└── console.php                          # Scheduled tasks

config/
└── services.php                         # API configuration
```

---

### **Flow Diagram:**

```
┌──────────────────┐
│  Hoteliermarket  │
│       API        │
└────────┬─────────┘
         │
         │ 1. Fetch Data
         │
         ▼
┌─────────────────────┐
│ HotelierMarket      │
│    Service          │
│ - fetchRoomPricing()│
│ - syncRoomPricing() │
└──────────┬──────────┘
           │
           │ 2. Process & Map
           │
           ▼
┌──────────────────────┐
│  PriceOverride Model │
│  - updateOrCreate()  │
│  - source tracking   │
└──────────┬───────────┘
           │
           │ 3. Save to DB
           ▼
┌──────────────────────┐
│  price_overrides     │
│       Table          │
└──────────────────────┘
```

---

### **Response API yang Diharapkan:**

```json
{
  "success": true,
  "data": {
    "rooms": [
      {
        "id": 1,
        "name": "Superior Room",
        "base_price": 500000,
        "pricing": [
          {
            "id": "price_001",
            "date": "2025-12-10",
            "price": 550000
          },
          {
            "id": "price_002",
            "date": "2025-12-25",
            "price": 750000
          }
        ]
      },
      {
        "id": 2,
        "name": "Deluxe Room",
        "base_price": 750000,
        "pricing": [
          {
            "id": "price_003",
            "date": "2025-12-10",
            "price": 800000
          }
        ]
      }
    ]
  }
}
```

**Mapping:**
- `rooms[].name` → Cari room di database lokal berdasarkan name
- `rooms[].base_price` → Update `rooms.price`
- `rooms[].pricing[].date` → `price_overrides.date`
- `rooms[].pricing[].price` → `price_overrides.price`
- `rooms[].pricing[].id` → `price_overrides.external_reference_id`

---

## 🐛 Troubleshooting

### **1. API Connection Failed**

**Gejala:**
```
❌ Connection failed!
Status Code: 403
Message: Forbidden
```

**Solusi:**
- Periksa `HOTELIERMARKET_API_KEY` di `.env`
- Pastikan API key masih aktif di Hoteliermarket
- Cek allowed origins di dashboard Hoteliermarket

---

### **2. Room Not Found**

**Gejala:**
```
⚠️ Warnings/Errors:
- Room not found or created: Superior Room
```

**Solusi:**
- Pastikan nama room di API sama persis dengan nama di database lokal
- Atau buat room baru di admin dashboard dengan nama yang sama
- Periksa mapping logic di `HotelierMarketService::findOrCreateRoom()`

---

### **3. Scheduled Task Tidak Berjalan**

**Gejala:**
Sync otomatis tidak berjalan di jam yang ditentukan.

**Solusi:**
1. Pastikan cron job sudah disetup:
   ```bash
   crontab -e
   # Tambahkan:
   * * * * * cd /path/to/bellhotelmerauke && php artisan schedule:run >> /dev/null 2>&1
   ```

2. Test schedule:
   ```bash
   php artisan schedule:list
   ```

3. Force run untuk testing:
   ```bash
   php artisan schedule:run
   ```

---

### **4. Rate Limiting Error**

**Gejala:**
```json
{
  "success": false,
  "message": "Sync terakhir 30 menit yang lalu. Tunggu 60 menit atau gunakan force sync.",
  "minutes_remaining": 30
}
```

**Solusi:**
- Tunggu hingga 60 menit sejak sync terakhir
- Atau gunakan force sync:
  ```bash
  php artisan hoteliermarket:sync --force
  ```

---

## 📊 Monitoring & Logs

### **Check Logs:**

```bash
tail -f storage/logs/laravel.log | grep -i hoteliermarket
```

### **Log Format:**

**Success:**
```
[2025-12-10 06:00:00] local.INFO: HotelierMarket API: Successfully fetched room pricing {"status":200}
[2025-12-10 06:00:05] local.INFO: HotelierMarket Sync: Completed {"success":true,"rooms_updated":5,...}
```

**Error:**
```
[2025-12-10 06:00:00] local.ERROR: HotelierMarket API: Failed to fetch room pricing {"status":403}
[2025-12-10 06:00:00] local.ERROR: HotelierMarket Sync: Exception occurred {"message":"Connection timeout"}
```

---

## 📝 Notes

1. **Data Override:**
   Harga dari API akan **menimpa** harga manual jika tanggal sama. Untuk mencegah ini, ubah logic di `syncPriceOverride()` untuk hanya update jika `source != 'manual'`.

2. **Room Mapping:**
   Saat ini matching room berdasarkan **name** (case-insensitive). Jika perlu matching lebih akurat, tambahkan field `external_id` di tabel `rooms`.

3. **API Response Structure:**
   Struktur response di dokumentasi ini adalah **asumsi**. Sesuaikan dengan actual response dari API Hoteliermarket.

4. **Security:**
   API key disimpan di `.env` dan tidak pernah di-commit ke Git. Pastikan `.env` ada di `.gitignore`.

---

## 🔗 Related Files

- Service: `app/Services/HotelierMarketService.php`
- Command: `app/Console/Commands/SyncHotelierPrices.php`
- Controller: `app/Http/Controllers/Admin/ApiSyncController.php`
- View: `resources/views/admin/api-sync/index.blade.php`
- Routes: `routes/web.php` (line 158-162)
- Schedule: `routes/console.php` (line 11-22)
- Config: `config/services.php` (line 38-43)
- Migration: `database/migrations/2025_12_10_060000_add_api_sync_fields_to_price_overrides_table.php`

---

## 📞 Support

Jika ada pertanyaan atau masalah, hubungi tim development Bell Hotel Merauke.

**Last Updated:** 2025-12-10
