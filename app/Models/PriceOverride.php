<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'room_id',
        'price',
        'source',
        'api_synced_at',
        'external_reference_id',
    ];

    protected $casts = [
        'api_synced_at' => 'datetime',
    ];

    // Tambahkan relasi ke model Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}