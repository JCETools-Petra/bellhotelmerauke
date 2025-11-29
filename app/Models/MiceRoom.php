<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiceRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'dimension', // TAMBAHKAN
        'size_sqm', // TAMBAHKAN
        'rate_details',
        'capacity_classroom', // TAMBAHKAN
        'capacity_theatre', // TAMBAHKAN
        'capacity_ushape', // TAMBAHKAN
        'capacity_round', // TAMBAHKAN
        'capacity_board', // TAMBAHKAN
        'description',
        'facilities',
        'image',
        'is_available',
        'seo_title',
        'meta_description',
    ];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}