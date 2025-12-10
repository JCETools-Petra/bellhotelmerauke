<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Commission;
use App\Models\Affiliate;
use App\Models\Setting; // Tambahkan ini
use Illuminate\Support\Facades\Log;

class CommissionService
{
    /**
     * Create a commission for a booking if it has an affiliate.
     *
     * @param Booking $booking
     * @return Commission|null
     */
    public function createForBooking(Booking $booking): ?Commission
    {
        if (!$booking->affiliate_id) {
            return null;
        }

        // Check if commission already exists
        $existingCommission = Commission::where('booking_id', $booking->id)->first();
        if ($existingCommission) {
            return $existingCommission;
        }

        $affiliate = $booking->affiliate;
        
        // --- LOGIKA BARU UNTUK MEMBEDAKAN MICE & ROOM ---
        
        $rate = 0;
        $notes = '';
        
        // Cek apakah ini Booking MICE
        if ($booking->mice_kit_id) {
            // Ambil rate khusus MICE dari affiliate, jika 0 ambil dari Setting global
            $rate = ($affiliate->mice_commission_rate > 0) 
                    ? $affiliate->mice_commission_rate 
                    : (Setting::where('key', 'mice_commission_rate')->value('value') ?? 2.5);
            
            // Nama paket untuk notes
            $packageName = $booking->miceKit->title ?? 'MICE Package';
            $notes = "MICE Event: " . $booking->event_name . "\nPackage: " . $packageName;
        } 
        // Jika bukan, anggap Booking Kamar
        else {
            $rate = $affiliate->commission_rate;
            $notes = 'Commission from Booking ID #' . $booking->id;
        }

        // Validasi Rate
        if ($rate <= 0) {
            return null;
        }

        // Hitung Amount (Total Price * Rate)
        $commissionAmount = $booking->total_price * ($rate / 100);

        return Commission::create([
            'affiliate_id' => $affiliate->id,
            'booking_id' => $booking->id,
            'commission_amount' => $commissionAmount,
            'rate' => $rate,
            'status' => 'unpaid',
            'notes' => $notes,
        ]);
    }

    /**
     * Create a commission for a MICE inquiry (Manual / Non-Booking Model).
     */
    public function createForMice(Affiliate $affiliate, float $totalPayment, float $rate, string $notes): Commission
    {
        $commissionAmount = ($totalPayment * $rate) / 100;

        return Commission::create([
            'affiliate_id' => $affiliate->id,
            'booking_id' => null,
            'commission_amount' => $commissionAmount,
            'rate' => $rate,
            'status' => 'unpaid',
            'notes' => $notes,
        ]);
    }
}