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
    ];

    // Tambahkan relasi ke model Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}