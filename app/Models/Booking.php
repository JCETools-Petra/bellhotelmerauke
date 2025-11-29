<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Commission;
use App\Models\Affiliate;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id',
        'booking_source',
        'room_id',
        'guest_name',
        'guest_phone',
        'guest_email',
        'checkin_date',
        'checkout_date',
        'num_rooms',
        'status',
        'total_price',
        'access_token',
        'payment_method',// Tambahkan ini
        'snap_token', 
    ];

    /**
     * Mendefinisikan bahwa sebuah booking dimiliki oleh satu room.
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function commission()
    {
        return $this->hasOne(Commission::class);
    }
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
}