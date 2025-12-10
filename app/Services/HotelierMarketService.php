<?php

namespace App\Services;

use App\Models\Room;
use App\Models\PriceOverride;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;

class HotelierMarketService
{
    private ?string $apiKey;
    private ?string $apiUrl;
    private ?int $propertyId;
    private int $timeout;

    // MAPPING MANUAL FINAL
    private array $manualMap = [
        'Standard' => 'Superior',
        'Deluxe'   => 'Deluxe',
    ];

    public function __construct()
    {
        $this->apiKey = config('services.hoteliermarket.api_key');
        $this->apiUrl = config('services.hoteliermarket.api_url', 'https://hoteliermarket.my.id');
        $this->propertyId = config('services.hoteliermarket.property_id');
        $this->timeout = config('services.hoteliermarket.timeout', 30);
    }

    private function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->apiUrl) && !empty($this->propertyId);
    }

    public function getSyncStats(): array
    {
        return [
            'total_rooms' => Room::count(),
            'total_price_overrides' => PriceOverride::count(),
            'future_price_overrides' => PriceOverride::where('date', '>=', now()->format('Y-m-d'))->count(),
            'api_configured' => $this->isConfigured(),
            'last_sync' => cache('hoteliermarket_last_sync'),
        ];
    }

    public function fetchRoomPricing(): ?array
    {
        if (!$this->isConfigured()) {
            Log::error('HotelierMarket API: Configuration missing.');
            return null;
        }

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get("{$this->apiUrl}/api/properties/{$this->propertyId}/room-pricing");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('HotelierMarket API: Failed to fetch', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('HotelierMarket API: Exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    public function syncRoomPricing(): array
    {
        $stats = [
            'success' => false,
            'rooms_updated' => 0,
            'prices_created' => 0,
            'prices_updated' => 0,
            'errors' => [],
            'synced_at' => now()->toDateTimeString(),
        ];

        $apiData = $this->fetchRoomPricing();

        if (!$apiData) {
            $stats['errors'][] = 'Failed to fetch data from API';
            return $stats;
        }

        try {
            if (isset($apiData['success']) && $apiData['success'] === true) {
                $rooms = $apiData['data']['room_types'] ?? [];

                foreach ($rooms as $roomData) {
                    $this->syncRoom($roomData, $stats);
                }

                $stats['success'] = true;
                cache()->put('hoteliermarket_last_sync', now()->toDateTimeString(), now()->addDay());
                
                Log::info("HotelierMarket Sync Completed: " . $stats['rooms_updated'] . " rooms updated.");

            } else {
                $stats['errors'][] = 'API returned unsuccessful response';
            }
        } catch (\Exception $e) {
            $stats['errors'][] = "Sync exception: {$e->getMessage()}";
            Log::error($e->getMessage());
        }

        return $stats;
    }

    private function syncRoom(array $roomData, array &$stats): void
    {
        $apiName = $roomData['name'];
        
        // FILTER: Lewati paket breakfast
        if (Str::contains(strtolower($apiName), ['breakfast', 'sarapan', 'makan pagi'])) {
            return;
        }

        // 1. Cek Manual Map
        $dbName = $this->manualMap[$apiName] ?? null;
        $room = $dbName ? Room::where('name', $dbName)->first() : null;

        // 2. Exact Match
        if (!$room) {
            $room = Room::whereRaw('LOWER(name) = ?', [strtolower($apiName)])->first();
        }

        // 3. Like Match
        if (!$room) {
            $room = Room::where('name', 'LIKE', "%{$apiName}%")->first();
        }

        if (!$room) {
            // Log ke file saja, tidak ke layar
            Log::warning("HotelierMarket Skip: No matching DB room for '{$apiName}'");
            return;
        }

        // Sync Harga
        if (isset($roomData['current_price'])) {
            $this->syncPriceOverride($room, [
                'date' => now()->toDateString(),
                'price' => $roomData['current_price']
            ], $stats);
            
            $stats['rooms_updated']++;
        }
    }

    private function syncPriceOverride(Room $room, array $priceData, array &$stats): void
    {
        try {
            $date = Carbon::parse($priceData['date'])->format('Y-m-d');
            $price = $priceData['price'];

            if ($price <= 0) return;

            $priceOverride = PriceOverride::updateOrCreate(
                [
                    'room_id' => $room->id,
                    'date' => $date,
                ],
                [
                    'price' => $price,
                    'source' => 'hoteliermarket',
                    'api_synced_at' => now(),
                ]
            );

            if ($priceOverride->wasRecentlyCreated) {
                $stats['prices_created']++;
            } else {
                $stats['prices_updated']++;
            }
        } catch (\Exception $e) {
            Log::error("Sync warning for {$room->name}: " . $e->getMessage());
        }
    }

    public function testConnection(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get("{$this->apiUrl}/api/properties/{$this->propertyId}/room-pricing");

            return [
                'success' => $response->successful(),
                'status_code' => $response->status(),
                'message' => $response->successful() ? 'Connection successful' : $response->body(),
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'status_code' => 0, 'message' => $e->getMessage()];
        }
    }
}