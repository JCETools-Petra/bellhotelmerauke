<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantsAndImagesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('restaurants')->insert([
            [
                'id' => 9,
                'name' => 'Bell Hotel Resto',
                'slug' => 'bell-hotel-resto',
                'description' => 'Rasakan pengalaman kuliner yang memadukan cita rasa lokal khas Papua...',
                'image_path' => null,
                'created_at' => '2025-08-16 22:02:31',
                'updated_at' => '2025-08-16 22:02:31',
            ],
        ]);

        DB::table('restaurant_images')->insert([
            ['id' => 35, 'restaurant_id' => 9, 'path' => 'restaurants/JHtx8IbhuJiqtXYh1Yh4C5GtHu37OCZeWpfVC1kh.jpg', 'created_at' => '2025-08-17 08:30:42', 'updated_at' => '2025-08-17 08:30:42'],
            ['id' => 36, 'restaurant_id' => 9, 'path' => 'restaurants/ouXVpgFYQHAGU9EOLRZz1eVxkj05A3BeGzWoUrHe.jpg', 'created_at' => '2025-08-17 08:30:42', 'updated_at' => '2025-08-17 08:30:42'],
            ['id' => 37, 'restaurant_id' => 9, 'path' => 'restaurants/6EQtplH8jhPV1VD3fx0Zz0i2lh3jDAv3ucC5cKRK.jpg', 'created_at' => '2025-08-17 08:30:42', 'updated_at' => '2025-08-17 08:30:42'],
        ]);
    }
}