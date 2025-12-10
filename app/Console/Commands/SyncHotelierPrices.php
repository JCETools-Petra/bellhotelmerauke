<?php

namespace App\Console\Commands;

use App\Services\HotelierMarketService;
use Illuminate\Console\Command;

class SyncHotelierPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hoteliermarket:sync
                            {--test : Test API connection without syncing}
                            {--force : Force sync even if recently synced}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync room pricing from Hoteliermarket API';

    /**
     * Execute the console command.
     */
    public function handle(HotelierMarketService $service): int
    {
        $this->info('🚀 Starting Hoteliermarket sync...');
        $this->newLine();

        // Test connection if --test flag is provided
        if ($this->option('test')) {
            return $this->testConnection($service);
        }

        // Check last sync time
        $lastSync = cache('hoteliermarket_last_sync');
        if ($lastSync && !$this->option('force')) {
            $diffInMinutes = now()->diffInMinutes($lastSync);
            if ($diffInMinutes < 60) {
                $this->warn("⏱️  Last sync was {$diffInMinutes} minutes ago.");
                $this->warn("   Use --force to sync anyway, or wait until 60 minutes have passed.");
                return self::SUCCESS;
            }
        }

        // Perform sync
        $this->info('📡 Fetching data from Hoteliermarket API...');
        $stats = $service->syncRoomPricing();

        $this->newLine();
        $this->displayResults($stats);

        // Update last sync time if successful
        if ($stats['success']) {
            cache(['hoteliermarket_last_sync' => now()], now()->addDays(7));
        }

        return $stats['success'] ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Test API connection
     */
    private function testConnection(HotelierMarketService $service): int
    {
        $this->info('🔍 Testing API connection...');
        $this->newLine();

        $result = $service->testConnection();

        if ($result['success']) {
            $this->info("✅ Connection successful!");
            $this->info("   Status Code: {$result['status_code']}");
            $this->info("   Message: {$result['message']}");
            return self::SUCCESS;
        } else {
            $this->error("❌ Connection failed!");
            $this->error("   Status Code: {$result['status_code']}");
            $this->error("   Message: {$result['message']}");
            return self::FAILURE;
        }
    }

    /**
     * Display sync results
     */
    private function displayResults(array $stats): void
    {
        if ($stats['success']) {
            $this->info('✅ Sync completed successfully!');
            $this->newLine();

            $this->table(
                ['Metric', 'Count'],
                [
                    ['Rooms Updated', $stats['rooms_updated']],
                    ['Prices Created', $stats['prices_created']],
                    ['Prices Updated', $stats['prices_updated']],
                ]
            );

            if (!empty($stats['errors'])) {
                $this->newLine();
                $this->warn('⚠️  Warnings/Errors:');
                foreach ($stats['errors'] as $error) {
                    $this->warn("   - {$error}");
                }
            }

            $this->newLine();
            $this->info("🕐 Synced at: {$stats['synced_at']}");
        } else {
            $this->error('❌ Sync failed!');
            $this->newLine();

            if (!empty($stats['errors'])) {
                $this->error('Errors:');
                foreach ($stats['errors'] as $error) {
                    $this->error("   - {$error}");
                }
            }
        }
    }
}
