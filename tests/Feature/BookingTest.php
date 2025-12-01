<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_create_booking_with_valid_data()
    {
        $room = Room::factory()->create([
            'price' => 100000,
            'is_available' => true,
        ]);

        $checkin = Carbon::tomorrow()->format('d-m-Y');
        $checkout = Carbon::tomorrow()->addDay()->format('d-m-Y');

        $response = $this->post(route('bookings.store'), [
            'room_id' => $room->id,
            'guest_name' => 'John Doe',
            'guest_phone' => '08123456789',
            'guest_email' => 'john@example.com',
            'checkin' => $checkin,
            'checkout' => $checkout,
            'num_rooms' => 1,
        ]);

        $booking = Booking::where('guest_email', 'john@example.com')->first();
        $response->assertRedirect(route('booking.payment', ['booking' => $booking->access_token]));

        $this->assertDatabaseHas('bookings', [
            'guest_email' => 'john@example.com',
            'room_id' => $room->id,
        ]);
    }

    public function test_booking_fails_with_past_date()
    {
        $room = Room::factory()->create();

        $checkin = Carbon::yesterday()->format('d-m-Y');
        $checkout = Carbon::today()->format('d-m-Y');

        $response = $this->post(route('bookings.store'), [
            'room_id' => $room->id,
            'guest_name' => 'John Doe',
            'guest_phone' => '08123456789',
            'guest_email' => 'john@example.com',
            'checkin' => $checkin,
            'checkout' => $checkout,
            'num_rooms' => 1,
        ]);

        $response->assertSessionHasErrors(['checkin']);
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_fails_if_checkout_before_checkin()
    {
        $room = Room::factory()->create();

        $checkin = Carbon::tomorrow()->addDay()->format('d-m-Y');
        $checkout = Carbon::tomorrow()->format('d-m-Y');

        $response = $this->post(route('bookings.store'), [
            'room_id' => $room->id,
            'guest_name' => 'John Doe',
            'guest_phone' => '08123456789',
            'guest_email' => 'john@example.com',
            'checkin' => $checkin,
            'checkout' => $checkout,
            'num_rooms' => 1,
        ]);

        $response->assertSessionHasErrors(['checkout']);
        $this->assertDatabaseCount('bookings', 0);
    }
}
