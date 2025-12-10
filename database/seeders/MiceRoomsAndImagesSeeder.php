<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MiceRoomsAndImagesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mice_rooms')->insert([
            [
                'id' => 1,
                'name' => 'SOTA ROOM',
                'slug' => 'sota-room',
                'dimension' => '8 m x 10 m',
                'size_sqm' => '80 m²',
                'rate_details' => 'Hubungi tim sales kami untuk mendapatkan penawaran terbaik sesuai kebutuhan acara Anda.',
                'capacity_classroom' => 30,
                'capacity_theatre' => 50,
                'capacity_ushape' => 25,
                'capacity_round' => 30,
                'capacity_board' => 30,
                'description' => 'SOTA Room adalah ruang serbaguna berkapasitas hingga 50 orang...',
                'facilities' => "Sound System\r\nScreen\r\nLCD Projector...",
                'is_available' => 1,
                'created_at' => '2025-08-15 23:24:11',
                'updated_at' => '2025-08-16 02:26:46',
            ],
            [
                'id' => 2,
                'name' => 'BUPUL ROOM',
                'slug' => 'bupul-room',
                'dimension' => '15 m x 14 m',
                'size_sqm' => '210 m²',
                'rate_details' => 'Harga untuk event dan meeting bersifat fleksibel...',
                'capacity_classroom' => 150,
                'capacity_theatre' => 210,
                'capacity_ushape' => 120,
                'capacity_round' => 100,
                'capacity_board' => 140,
                'description' => 'BUPUL Room merupakan ballroom serbaguna...',
                'facilities' => "Sound System\r\n Screen\r\n LCD Projector...",
                'is_available' => 1,
                'created_at' => '2025-08-16 06:20:40',
                'updated_at' => '2025-08-16 06:20:40',
            ],
            [
                'id' => 3,
                'name' => 'MUTING',
                'slug' => 'muting',
                'dimension' => '15 m x 16 m',
                'size_sqm' => '240 m²',
                'rate_details' => 'Harga untuk event dan meeting bersifat fleksibel...',
                'capacity_classroom' => 200,
                'capacity_theatre' => 300,
                'capacity_ushape' => 180,
                'capacity_round' => 150,
                'capacity_board' => 100,
                'description' => 'MUTING Room menghadirkan ruang pertemuan luas...',
                'facilities' => "Sound System\r\n Screen\r\n LCD Projector...",
                'is_available' => 1,
                'created_at' => '2025-08-16 06:22:54',
                'updated_at' => '2025-08-16 06:22:54',
            ],
        ]);

        DB::table('images')->insert([
            ['id' => 5, 'path' => 'mice/8Iq4bmJ0wD6uWN2yUeiLks29BiL2tLPd7iu3megP.jpg', 'imageable_id' => 1, 'imageable_type' => 'App\\Models\\MiceRoom', 'created_at' => '2025-08-15 23:34:25', 'updated_at' => '2025-08-15 23:34:25'],
            ['id' => 6, 'path' => 'mice/RMeGNI91UAeyJkfQdh0A1kN9cl91c9V9eICNd4M7.jpg', 'imageable_id' => 2, 'imageable_type' => 'App\\Models\\MiceRoom', 'created_at' => '2025-08-16 06:20:41', 'updated_at' => '2025-08-16 06:20:41'],
            ['id' => 7, 'path' => 'mice/C2MldQjHC2ud7VkYoreUK8IJ6dxYQj37nQojffXl.jpg', 'imageable_id' => 3, 'imageable_type' => 'App\\Models\\MiceRoom', 'created_at' => '2025-08-16 06:22:54', 'updated_at' => '2025-08-16 06:22:54'],
        ]);
    }
}