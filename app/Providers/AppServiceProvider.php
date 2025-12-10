<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Config; // <-- Tambahkan ini
use Illuminate\Support\Facades\Schema; // <-- Tambahkan ini
use App\Models\Setting;                 // <-- Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Biarkan kosong
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ==========================================================
        //         KODE BARU UNTUK MENGAMBIL SETTING MIDTRANS
        // ==========================================================
        try {
            // Cek apakah tabel settings ada sebelum mencoba mengambil data
            // Ini penting untuk mencegah error saat menjalankan migrasi pertama kali
            if (Schema::hasTable('settings')) {
                $midtransSettings = Setting::whereIn('key', [
                    'midtrans_merchant_id',
                    'midtrans_client_key',
                    'midtrans_server_key',
                    'midtrans_is_production'
                ])->pluck('value', 'key');

                // Hanya timpa konfigurasi jika data ditemukan di database
                if ($midtransSettings->isNotEmpty()) {
                    Config::set('midtrans.merchant_id', $midtransSettings->get('midtrans_merchant_id', env('MIDTRANS_MERCHANT_ID')));
                    Config::set('midtrans.client_key', $midtransSettings->get('midtrans_client_key', env('MIDTRANS_CLIENT_KEY')));
                    Config::set('midtrans.server_key', $midtransSettings->get('midtrans_server_key', env('MIDTRANS_SERVER_KEY')));
                    Config::set('midtrans.is_production', (bool) $midtransSettings->get('midtrans_is_production', env('MIDTRANS_IS_PRODUCTION', false)));
                }
            }
        } catch (\Exception $e) {
            // Jika terjadi error (misalnya saat database belum siap),
            // biarkan Laravel menggunakan konfigurasi default dari .env.
            // Tidak perlu melakukan apa-apa di sini.
        }
        // ==========================================================


        // KODE LAMA ANDA UNTUK NOTIFIKASI WHATSAPP (TETAP DIPERTAHANKAN)
        Notification::extend('whatsapp', function ($app) {
            return new \App\Notifications\Channels\WhatsAppChannel();
        });
    }
}