<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\PriceOverride;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Helper function untuk menghitung harga (Private agar code lebih rapi)
     */
    private function calculateRoomPrices($room, $date = null)
    {
        // 1. Tentukan tanggal (Hari ini atau tanggal yang diminta)
        $targetDate = $date ? Carbon::parse($date)->format('Y-m-d') : today()->toDateString();

        // 2. Cek apakah ada harga khusus dari Sync Hotelier
        $override = PriceOverride::where('room_id', $room->id)
            ->where('date', $targetDate)
            ->first();

        // 3. Harga Dasar: Pakai harga sync jika ada, kalau tidak pakai harga DB
        $basePrice = $override ? $override->price : $room->price;

        // 4. Harga Public: Base + 3% Margin
        $publicPrice = $basePrice * 1.03;

        // 5. Harga Affiliate: Public - Diskon Room
        // Ambil persentase diskon dari database room, default 10% jika null/0
        $discountPercent = ($room->discount_percentage > 0) ? $room->discount_percentage : 10;
        $affiliatePrice = $publicPrice * (1 - ($discountPercent / 100));

        // 6. Simpan hasil perhitungan ke object room
        $room->calculated_public_price = $publicPrice;
        $room->calculated_affiliate_price = $affiliatePrice;
        
        // Flag untuk view (opsional, untuk menandai harga spesial)
        $room->has_override_today = !empty($override);
        
        return $room;
    }

    public function index()
    {
        $rooms = Room::with('images')
            ->where('is_available', true)
            // ->whereNull('deleted_at') // Aktifkan jika sudah migrate soft delete
            ->latest()
            ->paginate(9);

        foreach ($rooms as $room) {
            $this->calculateRoomPrices($room);
        }

        return view('frontend.rooms.index', compact('rooms'));
    }

    public function show(Request $request, $slug)
    {
        // Cari kamar berdasarkan slug atau ID
        $room = Room::with('images')->where('slug', $slug)->orWhere('id', $slug)->firstOrFail();

        // Cek apakah ada request tanggal checkin dari user
        $checkinDate = null;
        if ($request->has('checkin') && !empty($request->checkin)) {
            try {
                $checkinDate = Carbon::createFromFormat('d-m-Y', $request->checkin)->format('Y-m-d');
            } catch (\Exception $e) {
                $checkinDate = null;
            }
        }

        // Hitung harga untuk kamar utama (Single Room)
        $this->calculateRoomPrices($room, $checkinDate);

        // Ambil kamar lain untuk rekomendasi (Related Rooms)
        $relatedRooms = Room::with('images')
            ->where('id', '!=', $room->id)
            ->where('is_available', true)
            ->inRandomOrder()
            ->take(3)
            ->get();

        // Hitung harga untuk kamar rekomendasi juga
        foreach ($relatedRooms as $related) {
            $this->calculateRoomPrices($related);
        }

        return view('frontend.rooms.show', compact('room', 'relatedRooms'));
    }

    public function checkAvailability(Request $request)
    {
        $searchParams = $request->all();
        
        // Ambil semua kamar yang tersedia
        $rooms = Room::with('images')->where('is_available', true)->get();

        // Cek tanggal dari input search
        $checkinDate = null;
        if (isset($searchParams['checkin']) && !empty($searchParams['checkin'])) {
            try {
                $checkinDate = Carbon::createFromFormat('d-m-Y', $searchParams['checkin'])->format('Y-m-d');
            } catch (\Exception $e) {
                $checkinDate = null;
            }
        }

        // Loop dan hitung harga berdasarkan tanggal tersebut
        foreach ($rooms as $room) {
            $this->calculateRoomPrices($room, $checkinDate);
        }
        
        return view('frontend.rooms.availability', compact('rooms', 'searchParams'));
    }
}