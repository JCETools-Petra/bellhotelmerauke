<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AffiliateVisit;
use App\Models\Commission;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $affiliate = $user->affiliate;

        // Menghitung statistik
        $totalClicks = AffiliateVisit::where('affiliate_id', $affiliate->id)->count();
        $totalBookings = Commission::where('affiliate_id', $affiliate->id)->count();
        
        // PERBAIKAN: Mengganti 'amount' menjadi 'commission_amount'
        $totalCommissions = Commission::where('affiliate_id', $affiliate->id)->where('status', 'unpaid')->sum('commission_amount');

        // Mengambil riwayat komisi
        $commissions = Commission::where('affiliate_id', $affiliate->id)
                                 ->with('booking')
                                 ->latest()
                                 ->paginate(10);

        return view('frontend.affiliate.dashboard', compact(
            'affiliate',
            'totalClicks',
            'totalBookings',
            'totalCommissions',
            'commissions'
        ));
    }
}