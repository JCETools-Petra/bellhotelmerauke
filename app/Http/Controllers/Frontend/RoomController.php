<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\PriceOverride;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index()
    {
        // Update: Tambahkan with('images') agar gambar terload dengan benar di view
        $rooms = Room::where('is_available', true)
                    ->with('images') // <--- PENTING
                    ->latest()
                    ->paginate(9); // Tampilkan 9 kamar per halaman agar grid rapi (3x3)

        return view('frontend.rooms.index', compact('rooms'));
    }

    public function show(Request $request, $slug)
    {
        $room = Room::where('slug', $slug)->firstOrFail();
        $currentPrice = $room->getOriginal('price'); // Ambil harga asli dari database

        // Cek harga khusus (override) jika ada tanggal checkin
        if ($request->has('checkin')) {
            try {
                $checkinDate = Carbon::createFromFormat('d-m-Y', $request->checkin)->format('Y-m-d');
                $override = $room->priceOverrides()->where('date', $checkinDate)->first();
                if ($override) {
                    $currentPrice = $override->price;
                }
            } catch (\Exception $e) {
                // Abaikan jika format tanggal salah
            }
        }

        // Terapkan diskon jika user adalah admin atau afiliasi
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'affiliate'])) {
            if ($room->discount_percentage > 0) {
                $discountAmount = $currentPrice * ($room->discount_percentage / 100);
                $currentPrice -= $discountAmount;
            }
        }

        // Set harga final untuk ditampilkan di view
        $room->price = $currentPrice;

        return view('frontend.rooms.show', compact('room'));
    }

    public function checkAvailability(Request $request)
    {
        $searchParams = $request->all();
        $rooms = Room::where('is_available', true)->get();

        $checkinDate = null;
        if (isset($searchParams['checkin']) && !empty($searchParams['checkin'])) {
            try {
                $checkinDate = Carbon::createFromFormat('d-m-Y', $searchParams['checkin'])->format('Y-m-d');
            } catch (\Exception $e) {
                $checkinDate = null;
            }
        }

        $isAffiliateOrAdmin = Auth::check() && in_array(Auth::user()->role, ['admin', 'affiliate']);

        foreach ($rooms as $room) {
            $currentPrice = $room->getOriginal('price'); // Mulai dengan harga asli dari database

            // Cek harga khusus (override) jika ada tanggal checkin yang valid
            if ($checkinDate) {
                $override = $room->priceOverrides()->where('date', $checkinDate)->first();
                if ($override) {
                    $currentPrice = $override->price;
                }
            }

            // Terapkan diskon untuk afiliasi/admin
            if ($isAffiliateOrAdmin && $room->discount_percentage > 0) {
                $discountAmount = $currentPrice * ($room->discount_percentage / 100);
                $currentPrice -= $discountAmount;
            }

            // Set harga final untuk ditampilkan di view
            $room->price = $currentPrice;
        }
        
        return view('frontend.rooms.availability', compact('rooms', 'searchParams'));
    }
}