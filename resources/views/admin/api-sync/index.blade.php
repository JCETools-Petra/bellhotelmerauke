<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('API Sync - Hoteliermarket') }}
            </h2>
            <div class="flex gap-2">
                <button onclick="testConnection()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Test Connection
                </button>
                <button onclick="syncNow()" id="syncBtn" class="px-4 py-2 bg-brand-red text-white rounded-md hover:opacity-90 transition-opacity">
                    Sync Now
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alert Messages -->
            <div id="alertContainer"></div>

            <!-- API Configuration Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">📡 API Configuration</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <span class="text-gray-600">Status:</span>
                            <span class="ml-2">
                                @if($stats['api_configured'])
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-sm rounded-full">✓ Configured</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-sm rounded-full">✗ Not Configured</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-600">Last Sync:</span>
                            <span class="ml-2 font-medium" id="lastSyncTime">
                                @if($lastSync)
                                    {{ $lastSync->diffForHumans() }}
                                @else
                                    Never
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">📊 Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_rooms'] }}</div>
                            <div class="text-sm text-gray-600 mt-1">Total Rooms</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-3xl font-bold text-green-600">{{ $stats['total_price_overrides'] }}</div>
                            <div class="text-sm text-gray-600 mt-1">Total Price Overrides</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-3xl font-bold text-purple-600">{{ $stats['future_price_overrides'] }}</div>
                            <div class="text-sm text-gray-600 mt-1">Future Price Overrides</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Sync Results -->
            @if(!empty($lastSyncStats))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">📝 Last Sync Results</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="p-3 bg-gray-50 rounded">
                            <div class="text-sm text-gray-600">Rooms Updated</div>
                            <div class="text-2xl font-bold">{{ $lastSyncStats['rooms_updated'] ?? 0 }}</div>
                        </div>
                        <div class="p-3 bg-gray-50 rounded">
                            <div class="text-sm text-gray-600">Prices Created</div>
                            <div class="text-2xl font-bold">{{ $lastSyncStats['prices_created'] ?? 0 }}</div>
                        </div>
                        <div class="p-3 bg-gray-50 rounded">
                            <div class="text-sm text-gray-600">Prices Updated</div>
                            <div class="text-2xl font-bold">{{ $lastSyncStats['prices_updated'] ?? 0 }}</div>
                        </div>
                    </div>

                    @if(!empty($lastSyncStats['errors']))
                    <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-400">
                        <h4 class="font-semibold text-yellow-800 mb-2">⚠️ Warnings/Errors:</h4>
                        <ul class="list-disc list-inside text-sm text-yellow-700">
                            @foreach($lastSyncStats['errors'] as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mt-4 text-sm text-gray-500">
                        Synced at: {{ $lastSyncStats['synced_at'] ?? 'N/A' }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Information -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">About API Sync</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Sync dilakukan otomatis setiap hari pada pukul 02:00 WIB</li>
                                <li>Manual sync hanya bisa dilakukan sekali per jam untuk menghindari rate limiting</li>
                                <li>Harga dari API akan membuat price override untuk tanggal tertentu</li>
                                <li>Harga manual yang sudah ada tidak akan ditimpa kecuali ada update dari API</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            const alertClass = type === 'success' ? 'bg-green-100 text-green-700 border-green-300' : 'bg-red-100 text-red-700 border-red-300';

            const alert = `
                <div class="mb-4 p-4 ${alertClass} border rounded-md">
                    ${message}
                </div>
            `;

            alertContainer.innerHTML = alert;

            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        async function testConnection() {
            try {
                const response = await fetch('{{ route("admin.api-sync.test") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('✅ ' + data.message, 'success');
                } else {
                    showAlert('❌ ' + data.message, 'error');
                }
            } catch (error) {
                showAlert('❌ Error: ' + error.message, 'error');
            }
        }

        async function syncNow() {
            const syncBtn = document.getElementById('syncBtn');
            const originalText = syncBtn.innerHTML;

            syncBtn.disabled = true;
            syncBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Syncing...';

            try {
                const response = await fetch('{{ route("admin.api-sync.sync") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('✅ ' + data.message + '<br>' +
                        'Rooms Updated: ' + data.stats.rooms_updated + ', ' +
                        'Prices Created: ' + data.stats.prices_created + ', ' +
                        'Prices Updated: ' + data.stats.prices_updated, 'success');

                    // Refresh page after 2 seconds
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showAlert('❌ ' + data.message, 'error');
                }
            } catch (error) {
                showAlert('❌ Error: ' + error.message, 'error');
            } finally {
                syncBtn.disabled = false;
                syncBtn.innerHTML = originalText;
            }
        }
    </script>
    @endpush
</x-app-layout>
