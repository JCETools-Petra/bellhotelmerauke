<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\PriceOverride;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RoomPriceController extends Controller
{
    /**
     * Mengambil harga dinamis untuk semua kamar pada satu tanggal spesifik.
     */
    public function getPricesOnDate(Request $request)
    {
        $request->validate(['date' => 'required|date_format:d-m-Y']);

        $date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
        $rooms = Room::where('is_available', true)->get();
        $prices = [];

        foreach ($rooms as $room) {
            $override = PriceOverride::where('room_id', $room->id)
                                     ->where('date', $date)
                                     ->first();
            
            $currentPrice = $override ? $override->price : $room->price;

            // --- LOGIKA DISKON ---
            if (Auth::check() && in_array(Auth::user()->role, ['admin', 'affiliate'])) {
                if ($room->discount_percentage > 0) {
                    $discountAmount = $currentPrice * ($room->discount_percentage / 100);
                    $currentPrice -= $discountAmount;
                }
            }
            // --- END LOGIKA DISKON ---

            $prices[$room->id] = [
                'price' => $currentPrice,
                'is_special' => (bool)$override
            ];
        }

        return response()->json($prices);
    }

    public function getPricesForMonth(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|between:2020,2030',
            'month' => 'required|integer|between:1,12',
        ]);

        $year = $request->year;
        $month = $request->month;

        $baseRoom = Room::where('name', 'Superior')->where('is_available', true)->first();
        
        if (!$baseRoom) {
            return response()->json([]);
        }
        $basePrice = $baseRoom->price;
        $discountPercentage = $baseRoom->discount_percentage;

        $overrides = PriceOverride::whereYear('date', $year)
                                  ->whereMonth('date', $month)
                                  ->where('room_id', $baseRoom->id)
                                  ->pluck('price', 'date');

        $prices = [];
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        
        $isAffiliateOrAdmin = Auth::check() && in_array(Auth::user()->role, ['admin', 'affiliate']);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day)->format('Y-m-d');
            $isSpecial = $overrides->has($date);
            
            $currentPrice = $isSpecial ? $overrides[$date] : $basePrice;

            // --- LOGIKA DISKON ---
            if ($isAffiliateOrAdmin && $discountPercentage > 0) {
                $discountAmount = $currentPrice * ($discountPercentage / 100);
                $currentPrice -= $discountAmount;
            }
            // --- END LOGIKA DISKON ---

            $prices[$date] = [
                'price' => $currentPrice,
                'is_special' => $isSpecial
            ];
        }

        return response()->json($prices);
    }
}