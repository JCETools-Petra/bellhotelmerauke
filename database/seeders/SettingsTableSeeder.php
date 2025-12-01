<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Data dari seeder lama dan database Anda
            ['key' => 'hero_title', 'value' => 'BELL HOTEL Merauke'],
            ['key' => 'hero_subtitle', 'value' => 'Bell Hotel Merauke, hotel modern yang terletak strategis...'],
            ['key' => 'about_title', 'value' => 'PROMO DISCOUNT 20%'],
            ['key' => 'about_content', 'value' => 'silahkan hubungi admin kami untuk discount yang sedang berlaku'],
            ['key' => 'hero_text_align', 'value' => 'text-center'],
            ['key' => 'hero_bg_image', 'value' => 'homepage/7P6miyMJ7Ygbnt8tO1vsUtcCqYnUrtCEO1rxfEVc.jpg'],
            ['key' => 'website_title', 'value' => 'Bell Hotel Merauke'],
            ['key' => 'featured_display_option', 'value' => 'rooms,mice,restaurants'],
            ['key' => 'logo_path', 'value' => 'settings/M5N72KDIEyd39q25rPaeBQiH84DJwDFMELeAzTzP.png'],
            ['key' => 'favicon_path', 'value' => 'settings/mEaeYpZw2CXQLfyr2i0UeTV7XX1G0cOtmoZ5WUuC.png'],
            ['key' => 'logo_height', 'value' => '50'],
            ['key' => 'show_logo_text', 'value' => '0'],
            ['key' => 'contact_address', 'value' => 'Jl. Prajurit, Mandala, Kec. Merauke, Kabupaten Merauke, Papua 99614'],
            ['key' => 'contact_phone', 'value' => '08114821323'],
            ['key' => 'contact_email', 'value' => 'frontoffice@bellhotelmerauke.com'],
            ['key' => 'contact_instagram', 'value' => 'https://www.instagram.com/ghmhotel.merauke/'],
            ['key' => 'contact_facebook', 'value' => 'https://www.facebook.com/ghmhotel.merauke'],
            ['key' => 'contact_linkedin', 'value' => 'https://www.linkedin.com/company/ghmmerauke'],
            ['key' => 'contact_youtube', 'value' => 'https://www.youtube.com/@ghmhotel.merauke'],
            ['key' => 'contact_tiktok', 'value' => 'https://www.tiktok.com/@ghmhotel.merauke'],
            ['key' => 'terms_and_conditions', 'value' => '<h1>Terms and Conditions</h1><p>Please update this content from the admin panel.</p>'],
            ['key' => 'contact_maps_embed', 'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3967.66969595232!2d140.3929493147682!3d-8.47313099389619!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x68370df182155555%3A0x5cb5cd1fc4e2f54a!2sBell%20Hotel%20Merauke!5e0!3m2!1sen!2sid!4v1692708605555!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'],
            
            // Kunci yang ada di database tapi tidak ada di seeder lama
            ['key' => 'hero_image_path', 'value' => 'settings/9SpcO2JiljcNqGLRVc8Z8B6YK6Lr49qUC2GRAHmo.jpg'],
            ['key' => 'midtrans_merchant_id', 'value' => 'G156568388'],
            ['key' => 'midtrans_client_key', 'value' => 'Mid-client-YhzhL41kb65w2Vhh'],

            // Pengaturan Running Text Baru
            ['key' => 'running_text_enabled', 'value' => '0'],
            ['key' => 'running_text_content', 'value' => 'Selamat Datang di Bell Hotel! Hubungi kami untuk penawaran spesial.'],
            ['key' => 'running_text_url', 'value' => ''],
        ];

        DB::table('settings')->truncate(); // Hapus data lama dulu
        DB::table('settings')->insert($settings);
    }
}