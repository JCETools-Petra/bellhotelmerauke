<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Affiliate;
use App\Models\Booking;
use App\Models\Commission;
use Illuminate\Support\Facades\Gate;

class CommissionController extends Controller
{
    public function create()
    {
        $affiliates = Affiliate::where('status', 'active')->with('user')->get();
        return view('admin.commissions.create', compact('affiliates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'affiliate_id' => 'required|exists:affiliates,id',
            'booking_reference' => 'required|string',
            'booking_amount' => 'required|numeric|min:0',
        ]);

        $affiliate = Affiliate::find($validated['affiliate_id']);
        $commissionAmount = $validated['booking_amount'] * ($affiliate->commission_rate / 100);

        $booking = Booking::create([
            'room_id' => 1, 
            'guest_name' => 'Manual Booking via WA (' . $validated['booking_reference'] . ')',
            'guest_email' => 'manual@booking.com',
            'guest_phone' => '0000',
            'num_rooms' => 1,
            'checkin_date' => now(),
            'checkout_date' => now(),
        ]);
        
        Commission::create([
            'affiliate_id' => $affiliate->id,
            'booking_id' => $booking->id,
            // --- PERBAIKAN 1 ---
            'commission_amount' => $commissionAmount, // Diubah dari 'amount'
            'rate' => $affiliate->commission_rate,
            'status' => 'unpaid',
            'notes' => 'Manual commission for booking: ' . $validated['booking_reference'],
        ]);

        return redirect()->route('admin.affiliates.index')->with('success', 'Manual commission added successfully.');
    }

    public function index()
    {
        if (! Gate::allows('manage-commissions')) abort(403);

        $affiliates = Affiliate::with('user')
            ->withSum(['commissions as unpaid_amount' => function ($query) {
                $query->where('status', 'unpaid');
            // --- PERBAIKAN 2 ---
            }], 'commission_amount') // Diubah dari 'amount'
            ->paginate(15);
            
        return view('admin.commissions.index', compact('affiliates'));
    }

    public function update(Request $request, Commission $commission)
    {
        if (! Gate::allows('manage-commissions')) {
            abort(403);
        }
        
        $request->validate(['status' => 'required|in:paid,unpaid']);

        $commission->update(['status' => $request->status]);

        return back()->with('success', 'Commission status has been updated.');
    }

    public function show(Affiliate $affiliate)
    {
        if (! Gate::allows('manage-commissions')) abort(403);

        $commissions = Commission::where('affiliate_id', $affiliate->id)
            ->where('status', 'unpaid')
            ->whereMonth('created_at', now()->month)
            ->with('booking')
            ->get();
            
        return response()->json($commissions);
    }

    public function markAsPaid(Affiliate $affiliate)
    {
        if (! Gate::allows('manage-commissions')) abort(403);

        Commission::where('affiliate_id', $affiliate->id)
            ->where('status', 'unpaid')
            // Sebaiknya hapus filter per bulan ini agar bisa membayar semua komisi,
            // atau biarkan jika memang sengaja hanya untuk bulan ini.
            // ->whereMonth('created_at', now()->month) 
            ->update(['status' => 'paid']);

        return back()->with('success', 'All unpaid commissions for this affiliate have been marked as paid.');
    }

}