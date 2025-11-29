<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'imageable_id', 'imageable_type'];

    /**
     * Get the parent imageable model (room or mice_room).
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}