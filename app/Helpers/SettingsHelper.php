<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class SettingsHelper
{
    /**
     * Mengambil value setting dari database berdasarkan key.
     * Menggunakan Cache agar lebih ringan.
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key = null, $default = null)
    {
        // Ambil semua settings dari cache 'site_settings'
        // Jika cache kosong, ambil dari DB dan simpan selamanya (sampai di-clear)
        $settings = Cache::rememberForever('site_settings', function () {
            // Pastikan tabel settings ada dan model Setting terhubung
            try {
                return Setting::pluck('value', 'key')->all();
            } catch (\Exception $e) {
                return [];
            }
        });

        // Jika tidak ada key spesifik yang diminta, kembalikan array semua settings
        if (is_null($key)) {
            return $settings;
        }

        // Kembalikan value sesuai key, atau default jika tidak ditemukan
        return $settings[$key] ?? $default;
    }

    /**
     * Menghapus cache settings.
     * Panggil fungsi ini (SettingsHelper::clear()) setiap kali kamu mengupdate data di tabel settings.
     */
    public static function clear()
    {
        Cache::forget('site_settings');
    }
}

// --- FUNGSI GLOBAL (OPSIONAL) ---
// Ini ditambahkan agar jika ada kode lama/view yang pakai settings('key') tetap jalan.
if (!function_exists('settings')) {
    function settings($key = null, $default = null)
    {
        return \App\Helpers\SettingsHelper::get($key, $default);
    }
}