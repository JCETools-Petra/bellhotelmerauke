<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bookings')->insert([
            [
                'id' => 1,
                'room_id' => 3,
                'guest_name' => 'Petra Yoshua Marturia',
                'guest_phone' => '123123',
                'guest_email' => 'petratech1830@gmail.com',
                'checkin_date' => '2025-08-17',
                'checkout_date' => '2025-08-18',
                'num_rooms' => 1,
                'status' => 'pending',
                'affiliate_id' => null,
                'booking_source' => 'Guest',
                'created_at' => '2025-08-16 18:48:18',
                'updated_at' => '2025-08-16 18:48:18',
            ],
            [
                'id' => 10,
                'room_id' => 3,
                'guest_name' => 'Petra Yoshua Marturia',
                'guest_phone' => '0851577881148',
                'guest_email' => 'petratech1830@gmail.com',
                'checkin_date' => '2025-08-18',
                'checkout_date' => '2025-08-25',
                'num_rooms' => 5,
                'status' => 'cancelled',
                'affiliate_id' => 1,
                'booking_source' => 'Affiliate',
                'created_at' => '2025-08-18 01:55:42',
                'updated_at' => '2025-08-18 02:02:16',
            ],
            [
                'id' => 11,
                'room_id' => 3,
                'guest_name' => 'Petra Yoshua Marturia',
                'guest_phone' => '0851577881148',
                'guest_email' => 'petratech1830@gmail.com',
                'checkin_date' => '2025-08-18',
                'checkout_date' => '2025-08-25',
                'num_rooms' => 10,
                'status' => 'confirmed',
                'affiliate_id' => 1,
                'booking_source' => 'Affiliate',
                'created_at' => '2025-08-18 02:01:54',
                'updated_at' => '2025-08-18 02:02:19',
            ],
            [
                'id' => 12,
                'room_id' => 3,
                'guest_name' => 'Petra Yoshua Marturia',
                'guest_phone' => '0851577881148',
                'guest_email' => 'petratech1830@gmail.com',
                'checkin_date' => '2025-08-19',
                'checkout_date' => '2025-08-26',
                'num_rooms' => 20,
                'status' => 'confirmed',
                'affiliate_id' => 1,
                'booking_source' => 'Affiliate',
                'created_at' => '2025-08-18 19:36:06',
                'updated_at' => '2025-08-18 19:37:05',
            ],
        ]);
    }
}