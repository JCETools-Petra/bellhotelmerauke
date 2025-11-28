<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Dasar
            SettingsTableSeeder::class,
            UsersTableSeeder::class,
            
            // Konten Utama
            RoomsAndImagesSeeder::class,
            MiceRoomsAndImagesSeeder::class,
            RestaurantsAndImagesSeeder::class,
            
            // Transaksional (dengan urutan ketergantungan yang benar)
            AffiliatesTableSeeder::class,      // Buat afiliasi dulu
            BookingsTableSeeder::class,        // Lalu buat booking (yang butuh afiliasi)
            CommissionsTableSeeder::class,     // Terakhir, buat komisi (yang butuh booking)
        ]);
    }
}