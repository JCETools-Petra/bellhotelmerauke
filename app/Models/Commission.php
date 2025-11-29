<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use App\Models\Affiliate;

class Commission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'affiliate_id',
        'booking_id',
        'commission_amount', // Diperbarui dari 'amount'
        'rate',              // Ditambahkan
        'status',
        'notes',             // Ditambahkan
    ];

    /**
     * Defines the relationship to the Booking model.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Defines the relationship to the Affiliate model.
     */
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
}