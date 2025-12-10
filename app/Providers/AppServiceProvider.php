<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Config; 
use Illuminate\Support\Facades\Schema; 
use Illuminate\Pagination\Paginator; // Tambahan agar tampilan tabel rapi
use App\Models\Setting; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ==========================================================
        //  SOLUSI ERROR "Call to undefined function settings()"
        // ==========================================================
        // Kita paksa aplikasi membaca file Helper ini saat start
        $helperPath = app_path('Helpers/SettingsHelper.php');
        if (file_exists($helperPath)) {
            require_once $helperPath;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Konfigurasi standar
        Paginator::useBootstrapFive();
        Schema::defaultStringLength(191);

        // ==========================================================
        //    KONFIGURASI MIDTRANS DARI DATABASE (GLOBAL OVERRIDE)
        // ==========================================================
        try {
            // Cek apakah tabel settings ada (mencegah error saat migrate awal)
            if (Schema::hasTable('settings')) {
                // Ambil data yang dibutuhkan saja
                $midtransSettings = Setting::whereIn('key', [
                    'midtrans_merchant_id',
                    'midtrans_client_key',
                    'midtrans_server_key',
                    'midtrans_mode' // Kita gunakan 'midtrans_mode' sesuai pembahasan sebelumnya
                ])->pluck('value', 'key');

                if ($midtransSettings->isNotEmpty()) {
                    // 1. Set Merchant ID
                    if (isset($midtransSettings['midtrans_merchant_id'])) {
                        Config::set('midtrans.merchant_id', $midtransSettings['midtrans_merchant_id']);
                    }

                    // 2. Set Client Key
                    if (isset($midtransSettings['midtrans_client_key'])) {
                        Config::set('midtrans.client_key', $midtransSettings['midtrans_client_key']);
                    }

                    // 3. Set Server Key
                    if (isset($midtransSettings['midtrans_server_key'])) {
                        Config::set('midtrans.server_key', $midtransSettings['midtrans_server_key']);
                    }

                    // 4. Set Production Mode (Logika Cerdas)
                    // Cek key 'midtrans_mode'. Jika isinya 'production', set true. Selain itu false.
                    $mode = $midtransSettings['midtrans_mode'] ?? 'sandbox';
                    $isProduction = (strtolower(trim($mode)) === 'production');
                    
                    Config::set('midtrans.is_production', $isProduction);
                }
            }
        } catch (\Exception $e) {
            // Jika error (misal koneksi DB putus), diamkan saja & pakai default .env
        }

        // ==========================================================
        //          NOTIFIKASI WHATSAPP
        // ==========================================================
        Notification::extend('whatsapp', function ($app) {
            return new \App\Notifications\Channels\WhatsAppChannel();
        });
    }
}