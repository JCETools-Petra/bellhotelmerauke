<?php

namespace App\Services;

use App\Models\Room;
use Carbon\Carbon;

class PricingService
{
    /**
     * Calculate the total price for a room booking.
     *
     * @param Room $room
     * @param Carbon $checkin
     * @param Carbon $checkout
     * @param int $numRooms
     * @param bool $isAffiliate
     * @return float
     */
    public function calculateTotalRoomPrice(Room $room, Carbon $checkin, Carbon $checkout, int $numRooms, bool $isAffiliate): float
    {
        $totalPrice = 0;
        $discountPercentage = $isAffiliate ? $room->discount_percentage : 0;

        for ($date = $checkin->copy(); $date->lt($checkout); $date->addDay()) {
            $nightlyPrice = $this->calculateNightlyPrice($room, $date, $discountPercentage);
            $totalPrice += $nightlyPrice;
        }

        return $totalPrice * $numRooms;
    }

    /**
     * Calculate the price for a single night, considering overrides and discounts.
     *
     * @param Room $room
     * @param Carbon $date
     * @param float $discountPercentage
     * @return float
     */
    public function calculateNightlyPrice(Room $room, Carbon $date, float $discountPercentage = 0): float
    {
        $override = $room->priceOverrides()->where('date', $date->format('Y-m-d'))->first();
        $basePrice = $override ? $override->price : $room->price;

        if ($discountPercentage > 0) {
            $discountAmount = $basePrice * ($discountPercentage / 100);
            return $basePrice - $discountAmount;
        }

        return $basePrice;
    }

    /**
     * Apply affiliate discount to a room object (for display purposes).
     *
     * @param Room $room
     * @return void
     */
    public function applyAffiliateDiscount(Room $room): void
    {
        if ($room->discount_percentage > 0) {
            // Assuming we want to show the discounted base price
            // Note: This modifies the room instance's price attribute temporarily
            $originalPrice = $room->price;
            $discountAmount = $originalPrice * ($room->discount_percentage / 100);
            $room->price = $originalPrice - $discountAmount;
            
            // Optionally store original price if needed for display
            $room->original_price = $originalPrice;
        }
    }
}
