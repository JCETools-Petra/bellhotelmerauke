<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\MiceKit;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AffiliateMiceBookingController extends Controller
{
    public function index()
    {
        $miceKits = MiceKit::all();
        return view('frontend.affiliate.special_mice.index', compact('miceKits'));
    }

    public function show($id)
    {
        $miceKit = MiceKit::findOrFail($id);
        
        // Ambil data affiliate user yang sedang login untuk mendapatkan commission_rate
        $user = Auth::user();
        // Asumsi relasi user ke affiliate ada (jika user adalah affiliate)
        // Jika menggunakan logic manual: $affiliate = \App\Models\Affiliate::where('user_id', $user->id)->first();
        // Di sini saya gunakan asumsi standar relasi Laravel:
        $affiliate = $user->affiliate; 

        return view('frontend.affiliate.special_mice.show', compact('miceKit', 'affiliate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mice_kit_id' => 'required|exists:mice_kits,id',
            'check_in_date' => 'required|date|after:today',
            'pax' => 'required|integer|min:10',
            'total_price' => 'required|numeric|min:0', // Validasi harga total inputan
            'note' => 'nullable|string',
        ]);

        $miceKit = MiceKit::findOrFail($request->mice_kit_id);
        
        // Kita gunakan total_price dari INPUT user (sesuai permintaan), 
        // bukan hitungan otomatis controller.
        $finalPrice = $request->total_price;

        Booking::create([
            'booking_code'    => 'AFF-MICE-' . strtoupper(Str::random(6)),
            'user_id'         => Auth::id(),
            'affiliate_id'    => Auth::user()->affiliate->id ?? null,
            'mice_kit_id'     => $miceKit->id, // Pastikan kolom ini ada di tabel bookings (nullable) atau simpan di note
            'check_in_date'   => $request->check_in_date,
            'total_price'     => $finalPrice,
            'pax'             => $request->pax,
            'note'            => $request->note,
            'payment_method'  => 'pay_at_hotel',
            'payment_status'  => 'pending',
            'status'          => 'pending',
        ]);

        return redirect()->route('affiliate.dashboard')
            ->with('success', 'Booking MICE berhasil! Komisi akan tercatat setelah pembayaran dikonfirmasi.');
    }
}