<?php

namespace App\Services;

use App\Models\Room;
use App\Models\PriceOverride;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HotelierMarketService
{
    private ?string $apiKey;
    private ?string $apiUrl;
    private ?int $propertyId;
    private int $timeout;

    public function __construct()
    {
        $this->apiKey = config('services.hoteliermarket.api_key');
        $this->apiUrl = config('services.hoteliermarket.api_url', 'https://hoteliermarket.my.id');
        $this->propertyId = config('services.hoteliermarket.property_id', 13);
        $this->timeout = config('services.hoteliermarket.timeout', 30);
    }

    /**
     * Check if service is properly configured
     *
     * @return bool
     */
    private function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->apiUrl) && !empty($this->propertyId);
    }

    /**
     * Get configuration error message
     *
     * @return string
     */
    private function getConfigurationError(): string
    {
        $missing = [];
        if (empty($this->apiKey)) {
            $missing[] = 'HOTELIERMARKET_API_KEY';
        }
        if (empty($this->apiUrl)) {
            $missing[] = 'HOTELIERMARKET_API_URL';
        }
        if (empty($this->propertyId)) {
            $missing[] = 'HOTELIERMARKET_PROPERTY_ID';
        }

        return 'API not configured. Missing: ' . implode(', ', $missing) . '. Please check your .env file.';
    }

    /**
     * Fetch room pricing data from Hoteliermarket API
     *
     * @return array|null
     */
    public function fetchRoomPricing(): ?array
    {
        if (!$this->isConfigured()) {
            Log::error('HotelierMarket API: ' . $this->getConfigurationError());
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
                Log::info('HotelierMarket API: Successfully fetched room pricing', [
                    'status' => $response->status(),
                ]);

                return $response->json();
            }

            Log::error('HotelierMarket API: Failed to fetch room pricing', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('HotelierMarket API: Exception occurred', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Sync room pricing from API to local database
     *
     * @return array Statistics about the sync operation
     */
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

        // Expected API response structure:
        // {
        //   "success": true,
        //   "data": {
        //     "rooms": [
        //       {
        //         "id": 1,
        //         "name": "Superior Room",
        //         "base_price": 500000,
        //         "pricing": [
        //           {"date": "2025-12-10", "price": 550000},
        //           {"date": "2025-12-25", "price": 750000}
        //         ]
        //       }
        //     ]
        //   }
        // }

        try {
            if (isset($apiData['success']) && $apiData['success'] === true) {
                $rooms = $apiData['data']['rooms'] ?? [];

                foreach ($rooms as $roomData) {
                    $this->syncRoom($roomData, $stats);
                }

                $stats['success'] = true;
            } else {
                $stats['errors'][] = 'API returned unsuccessful response';
            }
        } catch (\Exception $e) {
            Log::error('HotelierMarket Sync: Exception during sync', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $stats['errors'][] = "Sync exception: {$e->getMessage()}";
        }

        Log::info('HotelierMarket Sync: Completed', $stats);

        return $stats;
    }

    /**
     * Sync individual room data
     *
     * @param array $roomData
     * @param array &$stats
     * @return void
     */
    private function syncRoom(array $roomData, array &$stats): void
    {
        try {
            // Find room by name or external reference
            // You might need to adjust this matching logic based on your needs
            $room = $this->findOrCreateRoom($roomData);

            if (!$room) {
                $stats['errors'][] = "Room not found or created: {$roomData['name']}";
                return;
            }

            // Update base price if provided
            if (isset($roomData['base_price']) && $roomData['base_price'] > 0) {
                $room->price = $roomData['base_price'];
                $room->save();
                $stats['rooms_updated']++;
            }

            // Sync pricing overrides
            if (isset($roomData['pricing']) && is_array($roomData['pricing'])) {
                foreach ($roomData['pricing'] as $priceData) {
                    $this->syncPriceOverride($room, $priceData, $stats);
                }
            }
        } catch (\Exception $e) {
            Log::error('HotelierMarket Sync: Error syncing room', [
                'room_data' => $roomData,
                'error' => $e->getMessage(),
            ]);

            $stats['errors'][] = "Error syncing room {$roomData['name']}: {$e->getMessage()}";
        }
    }

    /**
     * Find existing room or create mapping
     *
     * @param array $roomData
     * @return Room|null
     */
    private function findOrCreateRoom(array $roomData): ?Room
    {
        // Strategy 1: Match by exact name
        $room = Room::where('name', $roomData['name'])->first();

        if ($room) {
            return $room;
        }

        // Strategy 2: Match by similar name (case-insensitive)
        $room = Room::whereRaw('LOWER(name) = ?', [strtolower($roomData['name'])])->first();

        if ($room) {
            return $room;
        }

        // Strategy 3: Match by external ID if provided
        if (isset($roomData['external_id'])) {
            // You might want to add external_id field to rooms table
            // $room = Room::where('external_id', $roomData['external_id'])->first();
        }

        // For now, we won't auto-create rooms to avoid duplicates
        // You can enable this if needed:
        // return Room::create([
        //     'name' => $roomData['name'],
        //     'slug' => Str::slug($roomData['name']),
        //     'price' => $roomData['base_price'] ?? 0,
        //     'description' => $roomData['description'] ?? '',
        //     'is_available' => true,
        // ]);

        return null;
    }

    /**
     * Sync price override for a specific date
     *
     * @param Room $room
     * @param array $priceData
     * @param array &$stats
     * @return void
     */
    private function syncPriceOverride(Room $room, array $priceData, array &$stats): void
    {
        try {
            $date = Carbon::parse($priceData['date'])->format('Y-m-d');
            $price = $priceData['price'];

            // Skip if price is invalid
            if ($price <= 0) {
                return;
            }

            // Find or create price override
            $priceOverride = PriceOverride::updateOrCreate(
                [
                    'room_id' => $room->id,
                    'date' => $date,
                ],
                [
                    'price' => $price,
                    'source' => 'hoteliermarket',
                    'api_synced_at' => now(),
                    'external_reference_id' => $priceData['id'] ?? null,
                ]
            );

            if ($priceOverride->wasRecentlyCreated) {
                $stats['prices_created']++;
            } else {
                $stats['prices_updated']++;
            }
        } catch (\Exception $e) {
            Log::error('HotelierMarket Sync: Error syncing price override', [
                'room_id' => $room->id,
                'price_data' => $priceData,
                'error' => $e->getMessage(),
            ]);

            $stats['errors'][] = "Error syncing price for {$room->name} on {$priceData['date']}";
        }
    }

    /**
     * Get sync statistics for display
     *
     * @return array
     */
    public function getSyncStats(): array
    {
        return [
            'total_rooms' => Room::count(),
            'total_price_overrides' => PriceOverride::count(),
            'future_price_overrides' => PriceOverride::where('date', '>=', now()->format('Y-m-d'))->count(),
            'api_configured' => !empty($this->apiKey),
            'last_sync' => cache('hoteliermarket_last_sync'),
        ];
    }

    /**
     * Test API connection
     *
     * @return array
     */
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
                'message' => $response->successful()
                    ? 'Connection successful'
                    : 'Connection failed: ' . $response->body(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status_code' => 0,
                'message' => 'Exception: ' . $e->getMessage(),
            ];
        }
    }
}
