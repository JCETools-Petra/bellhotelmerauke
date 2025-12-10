<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Commission;
use App\Models\Affiliate;
use App\Models\Room;     // Pastikan diimport
use App\Models\MiceKit;  // Pastikan diimport

class Booking extends Model
{
    use HasFactory;

    /**
     * Daftar kolom yang diizinkan untuk diisi secara massal (create/update).
     */
    protected $fillable = [
        // Field Standar / Lama
        'affiliate_id',
        'user_id',          // Tambahkan user_id jika ada relasi ke user login
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
        'snap_token', 
        'payment_method',
        'payment_status',   // Penting untuk status pembayaran

        // --- TAMBAHAN WAJIB UNTUK MICE (Sesuai Controller Revisi) ---
        'booking_code',     // Agar kode booking muncul
        'mice_kit_id',      // Agar relasi paket MICE terbaca
        'event_name',       // Agar nama event muncul
        'pax',              // Jumlah orang
        'note',             // Catatan
    ];

    /**
     * Relasi ke Room (Kamar Reguler)
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relasi ke MiceKit (Paket MICE) - TAMBAHAN BARU
     * Agar dashboard bisa memanggil $booking->miceKit->title
     */
    public function miceKit()
    {
        return $this->belongsTo(MiceKit::class, 'mice_kit_id');
    }

    /**
     * Relasi ke Komisi
     */
    public function commission()
    {
        return $this->hasOne(Commission::class);
    }

    /**
     * Relasi ke Affiliate
     */
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
    
    /**
     * Relasi ke User (Pembuat Booking)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}