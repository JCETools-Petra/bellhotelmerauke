<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Affiliate;
use App\Models\Booking;
use App\Models\Commission;
use Illuminate\Support\Facades\Gate;
use App\Models\ActivityLog;

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
        // Check permission - frontoffice can view, but with limited data
        if (! Gate::allows('view-commissions')) abort(403);

        $affiliates = Affiliate::with('user')
            ->withSum(['commissions as unpaid_amount' => function ($query) {
                $query->where('status', 'unpaid');
            }], 'commission_amount')
            ->paginate(15);

        // Check if user is frontoffice to hide sensitive data
        $isFrontoffice = auth()->user()->role === 'frontoffice';

        return view('admin.commissions.index', compact('affiliates', 'isFrontoffice'));
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
        if (! Gate::allows('view-commissions')) abort(403);

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

        // Get count of commissions that will be marked as paid
        $commissions = Commission::where('affiliate_id', $affiliate->id)
            ->where('status', 'unpaid')
            ->get();

        $count = $commissions->count();
        $totalAmount = $commissions->sum('commission_amount');

        // Update all unpaid commissions to paid
        Commission::where('affiliate_id', $affiliate->id)
            ->where('status', 'unpaid')
            ->update(['status' => 'paid']);

        // Log activity for frontoffice
        if (auth()->user() && auth()->user()->role === 'frontoffice') {
            $affiliateName = $affiliate->user ? $affiliate->user->name : "Affiliate #{$affiliate->id}";
            ActivityLog::log(
                'update',
                "Marked {$count} commission(s) as paid for affiliate {$affiliateName}, total amount: Rp " . number_format($totalAmount, 0, ',', '.'),
                'Commission',
                null,
                [
                    'affiliate_id' => $affiliate->id,
                    'commission_count' => $count,
                    'total_amount' => $totalAmount,
                    'status_change' => ['from' => 'unpaid', 'to' => 'paid']
                ]
            );
        }

        return back()->with('success', 'All unpaid commissions for this affiliate have been marked as paid.');
    }

}