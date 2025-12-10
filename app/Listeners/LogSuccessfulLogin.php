<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        // Only log for affiliate and frontoffice
        if (in_array($user->role, ['affiliate', 'frontoffice'])) {
            ActivityLog::create([
                'user_id' => $user->id,
                'user_role' => $user->role,
                'action' => 'login',
                'model_type' => null,
                'model_id' => null,
                'description' => "User {$user->name} logged in",
                'changes' => null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }
}
