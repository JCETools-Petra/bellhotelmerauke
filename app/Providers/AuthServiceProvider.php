<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Daftarkan policy Anda di sini jika ada
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        /**
         * Mendefinisikan Gate untuk manajemen komisi.
         * Mengembalikan true jika peran pengguna adalah 'admin' atau 'accounting'.
         */
        Gate::define('manage-commissions', function ($user) {
        return in_array($user->role, ['admin', 'accounting']);
        });

        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });
    }
}