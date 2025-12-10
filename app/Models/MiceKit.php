<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiceKit extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'path_or_link',
        'original_filename',
    ];
}