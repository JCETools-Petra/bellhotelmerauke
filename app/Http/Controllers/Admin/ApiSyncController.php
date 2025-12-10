<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HotelierMarketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ApiSyncController extends Controller
{
    private HotelierMarketService $service;

    public function __construct(HotelierMarketService $service)
    {
        $this->service = $service;
    }

    /**
     * Display API sync dashboard
     */
    public function index()
    {
        $stats = $this->service->getSyncStats();
        $lastSync = cache('hoteliermarket_last_sync');
        $lastSyncStats = cache('hoteliermarket_last_sync_stats', []);

        return view('admin.api-sync.index', compact('stats', 'lastSync', 'lastSyncStats'));
    }

    /**
     * Test API connection
     */
    public function testConnection()
    {
        $result = $this->service->testConnection();

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Koneksi API berhasil! Status: ' . $result['status_code'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Koneksi API gagal: ' . $result['message'],
        ], 500);
    }

    /**
     * Trigger manual sync
     */
    public function sync(Request $request)
    {
        try {
            $force = $request->input('force', false);

            // Check last sync time
            $lastSync = cache('hoteliermarket_last_sync');
            if ($lastSync && !$force) {
                $diffInMinutes = now()->diffInMinutes($lastSync);
                if ($diffInMinutes < 60) {
                    return response()->json([
                        'success' => false,
                        'message' => "Sync terakhir {$diffInMinutes} menit yang lalu. Tunggu 60 menit atau gunakan force sync.",
                        'minutes_remaining' => 60 - $diffInMinutes,
                    ], 429);
                }
            }

            // Perform sync
            $stats = $this->service->syncRoomPricing();

            // Cache sync stats
            if ($stats['success']) {
                cache(['hoteliermarket_last_sync' => now()], now()->addDays(7));
                cache(['hoteliermarket_last_sync_stats' => $stats], now()->addDays(7));

                return response()->json([
                    'success' => true,
                    'message' => 'Sinkronisasi berhasil!',
                    'stats' => $stats,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Sinkronisasi gagal. Lihat error log untuk detail.',
                'stats' => $stats,
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get sync status
     */
    public function status()
    {
        $stats = $this->service->getSyncStats();
        $lastSync = cache('hoteliermarket_last_sync');
        $lastSyncStats = cache('hoteliermarket_last_sync_stats', []);

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'last_sync' => $lastSync ? $lastSync->toDateTimeString() : null,
                'last_sync_stats' => $lastSyncStats,
                'can_sync' => $this->canSync(),
            ],
        ]);
    }

    /**
     * Check if can perform sync (rate limiting)
     */
    private function canSync(): bool
    {
        $lastSync = cache('hoteliermarket_last_sync');
        if (!$lastSync) {
            return true;
        }

        return now()->diffInMinutes($lastSync) >= 60;
    }
}
