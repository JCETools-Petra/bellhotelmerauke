<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\MiceRoom; // <--- Import Model MiceRoom
use App\Models\HeroSlider;
use Illuminate\Support\Facades\Auth;
use App\Models\AffiliateReview;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Hero Sliders
        $heroSliders = HeroSlider::where('is_active', true)
            ->orderBy('order')
            ->get();

        // 2. Featured Rooms (Kamar)
        $rooms = Room::with('images') // Pastikan eager load images jika pakai tabel relasi
            ->where('is_available', true)
            ->latest()
            ->take(3)
            ->get();

        // 3. Featured MICE (TAMBAHAN BARU)
        $miceRooms = \App\Models\MiceRoom::with('images')
            ->where('is_available', true) // Pastikan Sota Room statusnya available
            ->latest()
            ->take(3) // Ubah jadi 3 agar Sota, Muting, dan Bupul masuk semua
            ->get();

        // Logika Diskon (Biarkan seperti sebelumnya)
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'affiliate']) && $rooms->isNotEmpty()) {
            foreach ($rooms as $room) {
                if ($room->discount_percentage > 0) {
                    $originalPrice = $room->getOriginal('price');
                    $discountAmount = $originalPrice * ($room->discount_percentage / 100);
                    $room->price = $originalPrice - $discountAmount;
                }
            }
        }
        
        $reviews = AffiliateReview::with('affiliate.user')
                ->where('is_visible', true)
                ->latest()
                ->take(6) // Ambil 6 review terbaru
                ->get();
        // Kirim semua variabel ke view
        return view('frontend.home', compact('heroSliders', 'rooms', 'miceRooms', 'reviews'));
    }
}