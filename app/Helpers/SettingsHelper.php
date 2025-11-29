<?php

use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

if (!function_exists('settings')) {
    /**
     * Get a setting value from the database.
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function settings($key = null, $default = null)
    {
        // Ambil semua settings dari cache, atau dari database jika cache kosong
        $settings = Cache::rememberForever('site_settings', function () {
            return Setting::pluck('value', 'key')->all();
        });

        // Jika tidak ada key spesifik, kembalikan semua settings
        if (is_null($key)) {
            return $settings;
        }

        // Jika ada key, kembalikan nilainya atau nilai default
        return $settings[$key] ?? $default;
    }
}