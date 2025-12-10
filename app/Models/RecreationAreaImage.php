<?php

 

namespace App\Models;

 

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

 

class RecreationAreaImage extends Model

{

    use HasFactory;

 

    protected $fillable = [

        'recreation_area_id',

        'path',

        'caption',

        'order',

    ];

 

    public function recreationArea()

    {

        return $this->belongsTo(RecreationArea::class);

    }

}