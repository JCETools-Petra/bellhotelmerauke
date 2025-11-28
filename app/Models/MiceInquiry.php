<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiceInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id',      // Tambahan
        'mice_kit_id',       // Tambahan
        'mice_room_id',
        'customer_name',
        'customer_phone',
        'event_type',
        'event_date',        // Tambahan
        'pax',               // Tambahan
        'total_price',       // Tambahan
        'event_other_description',
        'status',
    ];

    public function miceRoom()
    {
        return $this->belongsTo(MiceRoom::class);
    }
    
    // Tambahkan relasi ke MiceKit agar bisa dipanggil di Controller/View
    public function miceKit()
    {
        return $this->belongsTo(MiceKit::class);
    }

    // Tambahkan relasi ke Affiliate
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
}