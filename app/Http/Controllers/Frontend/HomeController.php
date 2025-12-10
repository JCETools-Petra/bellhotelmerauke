<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\MiceRoom;
use App\Models\HeroSlider;
use App\Models\AffiliateReview;
use App\Models\PriceOverride;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $heroSliders = HeroSlider::where('is_active', true)->orderBy('order')->get();

        // Ambil data kamar
        $rooms = Room::with('images')
            ->where('is_available', true)
            // ->whereNull('deleted_at') // HAPUS/KOMENTAR JIKA BELUM MIGRATE DB
            ->latest()
            ->take(3)
            ->get();

        // === LOGIKA HARGA (Hotelier + 3%) ===
        foreach ($rooms as $room) {
            // 1. Cek harga sync hari ini
            $todayPrice = PriceOverride::where('room_id', $room->id)
                ->where('date', today()->toDateString())
                ->value('price');

            // 2. Harga Dasar (Pakai harga sync jika ada, jika tidak pakai harga DB)
            $basePrice = $todayPrice ? $todayPrice : $room->price;

            // 3. Harga Public (Base + 3%)
            $publicPrice = $basePrice * 1.03;

            // 4. Harga Affiliate (Public - Diskon Room)
            $discount = ($room->discount_percentage > 0) ? $room->discount_percentage : 10;
            $affiliatePrice = $publicPrice * (1 - ($discount / 100));

            // Simpan ke variabel sementara untuk View
            $room->calculated_public_price = $publicPrice;
            $room->calculated_affiliate_price = $affiliatePrice;
        }
        // ====================================

        $miceRooms = MiceRoom::with('images')->where('is_available', true)->latest()->take(3)->get();
        $reviews = AffiliateReview::with('affiliate.user')->where('is_visible', true)->latest()->take(6)->get();

        return view('frontend.home', compact('heroSliders', 'rooms', 'miceRooms', 'reviews'));
    }
}