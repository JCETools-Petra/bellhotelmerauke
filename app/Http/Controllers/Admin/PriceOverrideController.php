<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\PriceOverride;
use Carbon\Carbon;

class PriceOverrideController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        $overrides = PriceOverride::with('room')->orderBy('date', 'desc')->paginate(15);
        return view('admin.price_overrides.index', compact('rooms', 'overrides'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'prices' => 'required|array',
            'prices.*' => 'nullable|numeric|min:0',
        ]);

        $date = $request->date;

        foreach ($request->prices as $roomId => $price) {
            // Hanya simpan jika harga diisi
            if (!is_null($price)) {
                PriceOverride::updateOrCreate(
                    ['date' => $date, 'room_id' => $roomId],
                    ['price' => $price]
                );
            }
        }

        return back()->with('success', 'Harga khusus untuk tanggal ' . $date . ' berhasil disimpan.');
    }

    public function destroy(PriceOverride $priceOverride)
    {
        $priceOverride->delete();
        return back()->with('success', 'Harga khusus berhasil dihapus.');
    }
}