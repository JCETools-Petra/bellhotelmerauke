<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CommissionService;
use App\Models\Booking;
use App\Models\Affiliate;
use App\Models\Commission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommissionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $commissionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->commissionService = new CommissionService();
    }

    public function test_create_for_booking_creates_commission_correctly()
    {
        $affiliate = Affiliate::factory()->create(['commission_rate' => 10]);
        $booking = Booking::factory()->create([
            'affiliate_id' => $affiliate->id,
            'total_price' => 1000000,
        ]);

        $commission = $this->commissionService->createForBooking($booking);

        $this->assertNotNull($commission);
        $this->assertEquals($affiliate->id, $commission->affiliate_id);
        $this->assertEquals($booking->id, $commission->booking_id);
        $this->assertEquals(100000, $commission->commission_amount); // 10% of 1,000,000
        $this->assertEquals('unpaid', $commission->status);
    }

    public function test_create_for_booking_returns_null_if_no_affiliate()
    {
        $booking = Booking::factory()->create(['affiliate_id' => null]);

        $commission = $this->commissionService->createForBooking($booking);

        $this->assertNull($commission);
    }

    public function test_create_for_booking_returns_existing_commission()
    {
        $affiliate = Affiliate::factory()->create(['commission_rate' => 10]);
        $booking = Booking::factory()->create([
            'affiliate_id' => $affiliate->id,
            'total_price' => 1000000,
        ]);

        $commission1 = $this->commissionService->createForBooking($booking);
        $commission2 = $this->commissionService->createForBooking($booking);

        $this->assertEquals($commission1->id, $commission2->id);
        $this->assertCount(1, Commission::all());
    }

    public function test_create_for_mice_creates_commission_correctly()
    {
        $affiliate = Affiliate::factory()->create();
        $totalPayment = 5000000;
        $rate = 5;
        $notes = "MICE Event Test";

        $commission = $this->commissionService->createForMice($affiliate, $totalPayment, $rate, $notes);

        $this->assertNotNull($commission);
        $this->assertEquals($affiliate->id, $commission->affiliate_id);
        $this->assertNull($commission->booking_id);
        $this->assertEquals(250000, $commission->commission_amount); // 5% of 5,000,000
        $this->assertEquals('unpaid', $commission->status);
        $this->assertEquals($notes, $commission->notes);
    }
}
