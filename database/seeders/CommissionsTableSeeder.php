<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Komisi
        DB::table('commissions')->insert([
            [
                'id' => 5,
                'affiliate_id' => 1,
                'booking_id' => 11,
                'amount' => 600000.00,
                'status' => 'paid',
                'created_at' => '2025-08-18 02:02:19',
                'updated_at' => '2025-08-18 05:49:28',
            ],
            [
                'id' => 6,
                'affiliate_id' => 1,
                'booking_id' => 12,
                'amount' => 1200000.00,
                'status' => 'unpaid',
                'created_at' => '2025-08-18 19:37:05',
                'updated_at' => '2025-08-18 19:37:05',
            ],
        ]);
    }
}