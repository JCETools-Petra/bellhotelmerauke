<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Pastikan rute API juga dimuat
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // Pengecualian untuk Maintenance Mode
        $middleware->preventRequestsDuringMaintenance($except = [
            'admin/*',
            'login',
            'logout',
        ]);
        
        // Pengecualian untuk CSRF Token (Webhook Midtrans)
        $middleware->validateCsrfTokens(except: [
            'midtrans/callback',
        ]);

        // Middleware Grup
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\AffiliateMiddleware::class,
            \App\Http\Middleware\TrackWebsiteVisit::class,
        ]);

        // Middleware Alias
        $middleware->alias([
            'affiliate.active' => \App\Http\Middleware\EnsureUserIsActiveAffiliate::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,

            // ==========================================================
            // TAMBAHKAN BARIS INI UNTUK MEMPERBAIKI ERROR
            // ==========================================================
            'maintenance' => \App\Http\Middleware\MaintenanceMiddleware::class,
        ]);

    })
    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\ViewServiceProvider::class,
        App\Providers\NotificationServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();