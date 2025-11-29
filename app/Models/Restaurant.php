<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    // Tambahkan metode ini untuk mendefinisikan hubungan
    public function images()
    {
        return $this->hasMany(RestaurantImage::class);
    }
}