<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliateVisit;
use App\Models\Booking;
use App\Models\MiceRoom;
use App\Models\Room;
use App\Models\WebsiteVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Existing stats
        $roomCount = Room::count();
        $miceCount = MiceRoom::count();

        // Website visit statistics
        $totalWebsiteVisits = WebsiteVisit::count();
        $websiteVisitsToday = WebsiteVisit::whereDate('created_at', today())->count();
        $websiteVisitsThisMonth = WebsiteVisit::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $websiteVisitsThisWeek = WebsiteVisit::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        // Affiliate link click statistics
        $totalAffiliateClicks = AffiliateVisit::count();
        $affiliateClicksToday = AffiliateVisit::whereDate('created_at', today())->count();
        $affiliateClicksThisMonth = AffiliateVisit::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $affiliateClicksThisWeek = AffiliateVisit::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        // Top performing affiliates (by clicks)
        $topAffiliatesByClicks = Affiliate::select('affiliates.*')
            ->withCount('affiliateVisits as total_clicks')
            ->with('user:id,name,email')
            ->orderByDesc('total_clicks')
            ->limit(5)
            ->get();

        // Top performing affiliates (by bookings/commissions)
        $topAffiliatesByBookings = Affiliate::select('affiliates.*')
            ->withCount('commissions as total_bookings')
            ->withSum('commissions as total_commission', 'commission_amount')
            ->with('user:id,name,email')
            ->orderByDesc('total_bookings')
            ->limit(5)
            ->get();

        // Booking statistics
        $totalBookings = Booking::count();
        $bookingsToday = Booking::whereDate('created_at', today())->count();
        $bookingsThisMonth = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Active affiliates
        $activeAffiliates = Affiliate::where('status', 'active')->count();
        $pendingAffiliates = Affiliate::where('status', 'pending')->count();

        // Chart data for visits over last 7 days
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $last7Days->push([
                'date' => now()->subDays($i)->format('M d'),
                'website_visits' => WebsiteVisit::whereDate('created_at', $date)->count(),
                'affiliate_clicks' => AffiliateVisit::whereDate('created_at', $date)->count(),
            ]);
        }

        return view('admin.dashboard', compact(
            'roomCount',
            'miceCount',
            'totalWebsiteVisits',
            'websiteVisitsToday',
            'websiteVisitsThisMonth',
            'websiteVisitsThisWeek',
            'totalAffiliateClicks',
            'affiliateClicksToday',
            'affiliateClicksThisMonth',
            'affiliateClicksThisWeek',
            'topAffiliatesByClicks',
            'topAffiliatesByBookings',
            'totalBookings',
            'bookingsToday',
            'bookingsThisMonth',
            'activeAffiliates',
            'pendingAffiliates',
            'last7Days'
        ));
    }
}