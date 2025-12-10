<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\SettingsHelper;
use Illuminate\Support\Facades\Artisan;

class ClearSettingsCache extends Command
{
    /**
     * Nama command yang akan diketik di terminal.
     * Contoh penggunaan: php artisan settings:clear
     */
    protected $signature = 'settings:clear';

    /**
     * Deskripsi command.
     */
    protected $description = 'Membersihkan cache setting database dan cache aplikasi Laravel';

    /**
     * Eksekusi command.
     */
    public function handle()
    {
        $this->info('Memulai pembersihan cache...');

        // 1. Bersihkan Cache Custom Settings (yang kita buat di SettingsHelper)
        if (class_exists(SettingsHelper::class)) {
            SettingsHelper::clear();
            $this->info('✅ Cache Database Settings berhasil dihapus.');
        } else {
            $this->error('❌ Class SettingsHelper tidak ditemukan.');
        }

        // 2. Bersihkan Cache Bawaan Laravel (Config, Route, View, dll)
        $this->info('Membersihkan cache aplikasi Laravel (optimize:clear)...');
        Artisan::call('optimize:clear');
        
        $this->info('------------------------------------------');
        $this->info('🎉 SUKSES! Semua cache telah dibersihkan.');
        $this->info('Silakan coba lakukan transaksi Midtrans lagi.');
    }
}