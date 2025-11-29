<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_role',
        'action',
        'model_type',
        'model_id',
        'description',
        'changes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper method to log an activity
     */
    public static function log($action, $description, $modelType = null, $modelId = null, $changes = null)
    {
        $user = auth()->user();

        // Only log for affiliate and frontoffice roles
        if (!$user || !in_array($user->role, ['affiliate', 'frontoffice'])) {
            return;
        }

        return self::create([
            'user_id' => $user->id,
            'user_role' => $user->role,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
