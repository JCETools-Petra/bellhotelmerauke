<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Commission;
use App\Models\Affiliate;
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
        if (!$affiliate || $affiliate->commission_rate <= 0) {
            return null;
        }

        // Calculate commission amount
        // Logic: (Room Price * Num Rooms) * Rate
        // Note: Booking total_price usually includes duration, but let's stick to the existing logic 
        // found in Admin/BookingController which was: ($room->price * $booking->num_rooms) * rate
        // However, MidtransCallbackController used: $booking->total_price * rate.
        // The most accurate base is usually the total transaction value (total_price).
        // Let's use total_price as it's the actual amount paid.
        
        $commissionAmount = $booking->total_price * ($affiliate->commission_rate / 100);

        return Commission::create([
            'affiliate_id' => $affiliate->id,
            'booking_id' => $booking->id,
            'commission_amount' => $commissionAmount, // Correct column name
            'rate' => $affiliate->commission_rate,
            'status' => 'unpaid',
            'notes' => 'Commission from Booking ID #' . $booking->id,
        ]);
    }

    /**
     * Create a commission for a MICE inquiry.
     *
     * @param Affiliate $affiliate
     * @param float $totalPayment
     * @param float $rate
     * @param string $notes
     * @return Commission
     */
    public function createForMice(Affiliate $affiliate, float $totalPayment, float $rate, string $notes): Commission
    {
        $commissionAmount = ($totalPayment * $rate) / 100;

        return Commission::create([
            'affiliate_id' => $affiliate->id,
            'booking_id' => null,
            'commission_amount' => $commissionAmount, // Correct column name
            'rate' => $rate,
            'status' => 'unpaid',
            'notes' => $notes,
        ]);
    }
}
