<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomsAndImagesSeeder extends Seeder
{
    public function run(): void
    {
        // Data Kamar
        DB::table('rooms')->insert([
            [
                'id' => 1,
                'name' => 'Superior',
                'seo_title' => null,
                'meta_description' => null,
                'slug' => 'superior',
                'price' => 450000.00,
                'description' => 'Kamar Standard di Bell Hotel Merauke dirancang untuk memberikan kenyamanan dengan fasilitas modern yang sesuai untuk perjalanan bisnis maupun liburan.',
                'facilities' => "Wifi\r\nParking\r\nAC\r\nShower\r\nAir Panas\r\nFree antar jemput Bandara Mopa Merauke",
                'image' => null,
                'is_available' => 1,
                'created_at' => '2025-08-15 21:47:19',
                'updated_at' => '2025-08-17 19:12:19',
            ],
            [
                'id' => 3,
                'name' => 'Deluxe',
                'seo_title' => null,
                'meta_description' => null,
                'slug' => 'deluxe',
                'price' => 600000.00,
                'description' => 'Kamar Standard di Bell Hotel Merauke dirancang untuk memberikan kenyamanan dengan fasilitas modern yang sesuai untuk perjalanan bisnis maupun liburan.',
                'facilities' => "Wifi\r\n Parking\r\n AC\r\n Shower\r\n Air Panas",
                'image' => null,
                'is_available' => 1,
                'created_at' => '2025-08-16 07:28:05',
                'updated_at' => '2025-08-16 07:28:05',
            ],
        ]);

        // Data Gambar Kamar
        DB::table('images')->insert([
            ['id' => 1, 'path' => 'rooms/sDG9QwVFKEvFXLqWdEFqdp7zGSZyOS1vW8y5SRC8.jpg', 'imageable_id' => 1, 'imageable_type' => 'App\\Models\\Room', 'created_at' => '2025-08-15 21:47:19', 'updated_at' => '2025-08-15 21:47:19'],
            ['id' => 2, 'path' => 'rooms/7Q5AZn9if2ZyGV23JAVm9ChrKzRd7Ced4TxGidsJ.jpg', 'imageable_id' => 1, 'imageable_type' => 'App\\Models\\Room', 'created_at' => '2025-08-15 21:47:19', 'updated_at' => '2025-08-15 21:47:19'],
            ['id' => 3, 'path' => 'rooms/0yX1RzAd5HvhXZI1cznq8cuSFHsx1aqNjq4uygr8.jpg', 'imageable_id' => 1, 'imageable_type' => 'App\\Models\\Room', 'created_at' => '2025-08-15 21:47:19', 'updated_at' => '2025-08-15 21:47:19'],
            ['id' => 4, 'path' => 'rooms/k3McUSgu06vM2JQCdwSHzoALTpXQmAdIo8OYqIec.jpg', 'imageable_id' => 1, 'imageable_type' => 'App\\Models\\Room', 'created_at' => '2025-08-15 21:47:19', 'updated_at' => '2025-08-15 21:47:19'],
            ['id' => 8, 'path' => 'rooms/wnrHYDb2kdq7Jl3NC5staJGD64YlQd5QFNUn0AXJ.jpg', 'imageable_id' => 3, 'imageable_type' => 'App\\Models\\Room', 'created_at' => '2025-08-16 07:28:05', 'updated_at' => '2025-08-16 07:28:05'],
            ['id' => 9, 'path' => 'rooms/oW3pX8N9b2XCBpbB2FyXjjcdpa7Gl5waGuuV72eu.jpg', 'imageable_id' => 3, 'imageable_type' => 'App\\Models\\Room', 'created_at' => '2025-08-16 07:28:05', 'updated_at' => '2025-08-16 07:28:05'],
            ['id' => 10, 'path' => 'rooms/57aZniorCAJErVN0bA9Z0RJLHTmrCEKXG3nEzI9K.jpg', 'imageable_id' => 3, 'imageable_type' => 'App\\Models\\Room', 'created_at' => '2025-08-16 07:28:05', 'updated_at' => '2025-08-16 07:28:05'],
            ['id' => 11, 'path' => 'rooms/e3JzYwXP2UGquUbCxvCmvOLWWmM77gqBhvHeN8ku.jpg', 'imageable_id' => 3, 'imageable_type' => 'App\\Models\\Room', 'created_at' => '2025-08-16 07:28:05', 'updated_at' => '2025-08-16 07:28:05'],
        ]);
    }
}