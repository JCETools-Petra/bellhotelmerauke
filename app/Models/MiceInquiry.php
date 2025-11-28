<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiceInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'mice_room_id',
        'customer_name',
        'customer_phone',
        'event_type',
        'event_other_description',
        'status',
    ];

    public function miceRoom()
    {
        return $this->belongsTo(MiceRoom::class);
    }
}