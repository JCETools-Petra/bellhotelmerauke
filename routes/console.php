<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule: Daily Hoteliermarket API Sync
// Runs every day at 02:00 WIB (UTC+7)
Schedule::command('hoteliermarket:sync')
    ->dailyAt('02:00')
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping()
    ->onSuccess(function () {
        \Illuminate\Support\Facades\Log::info('Hoteliermarket sync completed successfully via scheduler');
    })
    ->onFailure(function () {
        \Illuminate\Support\Facades\Log::error('Hoteliermarket sync failed via scheduler');
    });
