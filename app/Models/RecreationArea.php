<?php

 

namespace App\Models;

 

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

 

class RecreationArea extends Model

{

    use HasFactory;

 

    protected $fillable = [

        'name',

        'slug',

        'description',

        'is_active',

        'order',

    ];

 

    protected $casts = [

        'is_active' => 'boolean',

    ];

 

    // Relationship dengan images

    public function images()

    {

        return $this->hasMany(RecreationAreaImage::class)->orderBy('order');

    }

 

    // Auto generate slug dari name

    protected static function boot()

    {

        parent::boot();

 

        static::creating(function ($recreationArea) {

            if (empty($recreationArea->slug)) {

                $recreationArea->slug = Str::slug($recreationArea->name);

            }

        });

 

        static::updating(function ($recreationArea) {

            if ($recreationArea->isDirty('name') && empty($recreationArea->slug)) {

                $recreationArea->slug = Str::slug($recreationArea->name);

            }

        });

    }

}