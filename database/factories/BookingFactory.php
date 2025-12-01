<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Affiliate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        $checkin = $this->faker->dateTimeBetween('now', '+1 month');
        $checkout = (clone $checkin)->modify('+2 days');

        return [
            'room_id' => Room::factory(),
            'affiliate_id' => null, // Default null, override in tests
            'guest_name' => $this->faker->name,
            'guest_email' => $this->faker->safeEmail,
            'guest_phone' => $this->faker->phoneNumber,
            'checkin_date' => $checkin->format('Y-m-d'),
            'checkout_date' => $checkout->format('Y-m-d'),
            'num_rooms' => 1,
            'total_price' => $this->faker->numberBetween(500000, 2000000),
            'status' => 'pending',
            'payment_status' => 'pending',
            'access_token' => Str::random(64),
        ];
    }
}
