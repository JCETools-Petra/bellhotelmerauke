<?php

namespace Tests\Unit;

use App\Models\Room;
use App\Models\PriceOverride;
use App\Services\PricingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_nightly_price_without_discount_or_override()
    {
        $service = new PricingService();
        $room = Room::factory()->make(['price' => 100000]);
        $date = Carbon::today();

        $price = $service->calculateNightlyPrice($room, $date);

        $this->assertEquals(100000, $price);
    }

    public function test_calculate_nightly_price_with_discount()
    {
        $service = new PricingService();
        $room = Room::factory()->make(['price' => 100000]);
        $date = Carbon::today();

        $price = $service->calculateNightlyPrice($room, $date, 10); // 10% discount

        $this->assertEquals(90000, $price);
    }

    public function test_calculate_total_room_price()
    {
        $service = new PricingService();
        $room = Room::factory()->create(['price' => 100000, 'discount_percentage' => 10]);
        
        $checkin = Carbon::today();
        $checkout = Carbon::today()->addDays(2); // 2 nights

        // Normal price: 100,000 * 2 = 200,000
        // Affiliate price (10% off): 90,000 * 2 = 180,000
        
        $totalPrice = $service->calculateTotalRoomPrice($room, $checkin, $checkout, 1, true);

        $this->assertEquals(180000, $totalPrice);
    }
}
