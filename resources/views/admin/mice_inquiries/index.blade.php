<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log MICE Affiliate Commission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Sesi untuk notifikasi sukses atau error --}}
            @if(session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Card Formulir Pencatatan --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Add New MICE Commission</h3>
                    <form action="{{ route('admin.mice-inquiries.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Kolom Kiri --}}
                            <div class="space-y-4">
                                <div>
                                    <label for="event_name" class="block text-sm font-medium text-gray-700">Nama Kegiatan</label>
                                    <input type="text" name="event_name" id="event_name" value="{{ old('event_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="mice_room_id" class="block text-sm font-medium text-gray-700">Ruangan MICE</label>
                                    <select name="mice_room_id" id="mice_room_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="">-- Pilih Ruangan --</option>
                                        @foreach($miceRooms as $room)
                                            <option value="{{ $room->id }}" {{ old('mice_room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="event_date" class="block text-sm font-medium text-gray-700">Tanggal Kegiatan</label>
                                    <input type="date" name="event_date" id="event_date" value="{{ old('event_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                            </div>

                            {{-- Kolom Kanan --}}
                            <div class="space-y-4">
                                <div>
                                    <label for="affiliate-select" class="block text-sm font-medium text-gray-700">Pilih Affiliate</label>
                                    {{-- INI BAGIAN YANG DIPERBARUI --}}
                                    <select name="user_id" id="affiliate-select" class="mt-1 block w-full" style="width: 100%;" required>
                                        <option value="">-- Cari atau Pilih Affiliate --</option>
                                        @foreach($affiliates as $affiliateUser)
                                            <option value="{{ $affiliateUser->id }}" {{ old('user_id') == $affiliateUser->id ? 'selected' : '' }}>
                                                {{ $affiliateUser->name }} ({{ $affiliateUser->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="total_payment" class="block text-sm font-medium text-gray-700">Total Pembayaran (Rp)</label>
                                    <input type="number" name="total_payment" id="total_payment" value="{{ old('total_payment') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: 5000000" required>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <h4 class="font-semibold text-gray-800">Perhitungan Komisi (2.5%)</h4>
                                    <p class="text-2xl font-bold text-green-600 mt-2" id="commission-display">Rp 0</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-brand-red text-white rounded-md hover:opacity-90 transition-opacity">Save Commission</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Card Riwayat Komisi MICE (Tidak ada perubahan di sini) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">MICE Commission History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Affiliate</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail Event</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Recorded</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($miceCommissions as $commission)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $commission->affiliate->user->name ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $commission->affiliate->user->email ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-pre-wrap text-sm text-gray-700">{!! nl2br(e($commission->notes)) !!}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-green-600">Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}</div>
                                            <div class="text-sm text-gray-500">({{ $commission->rate }}%)</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $commission->created_at->format('d F Y, H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('admin.mice-inquiries.destroy', $commission->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No MICE commissions recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $miceCommissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    {{-- Script untuk Select2 --}}
    <script>
        $(document).ready(function() {
            $('#affiliate-select').select2({
                placeholder: "Cari nama atau email affiliate",
                allowClear: true
            });
        });
    </script>

    {{-- Script untuk Kalkulasi Komisi --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalPaymentInput = document.getElementById('total_payment');
            const commissionDisplay = document.getElementById('commission-display');
            const commissionRate = 0.025; // 2.5%

            function calculateCommission() {
                const totalPayment = parseFloat(totalPaymentInput.value) || 0;
                const commission = totalPayment * commissionRate;
                
                // Format ke format Rupiah
                commissionDisplay.textContent = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(commission);
            }

            totalPaymentInput.addEventListener('input', calculateCommission);
            
            // Hitung saat halaman dimuat jika ada old value
            if (totalPaymentInput.value) {
                calculateCommission();
            }
        });
    </script>
    @endpush
</x-app-layout>