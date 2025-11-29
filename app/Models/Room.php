<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// TAMBAHKAN USE STATEMENT INI
use App\Models\PriceOverride; 

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'discount_percentage',
        'description',
        'facilities',
        'image',
        'is_available',
        'meta_description',
        'seo_title',
    ];
    
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    // ======================= AWAL PERBAIKAN =======================
    /**
     * Mendefinisikan relasi one-to-many ke PriceOverride.
     * Sebuah kamar bisa memiliki banyak harga khusus.
     */
    public function priceOverrides()
    {
        return $this->hasMany(PriceOverride::class);
    }
    // ======================== AKHIR PERBAIKAN =======================
}