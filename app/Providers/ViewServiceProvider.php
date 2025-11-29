<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Menggunakan '*' berarti mengirimkan $settings ke SEMUA view
        View::composer('*', function ($view) {

            if (Schema::hasTable('settings')) {
                $settings = Cache::remember('site_settings', 3600, function () {
                    return Setting::pluck('value', 'key')->all();
                });
                $view->with('settings', $settings);
            } else {
                $view->with('settings', []);
            }
        });
    }
}