@extends('layouts.frontend')

@section('seo_title', 'Buat Booking Baru - Affiliate Dashboard')

@section('content')
    {{-- 1. HERO HEADER SECTION --}}
    <div class="bg-gray-900 pt-24 pb-12 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-yellow-500 rounded-full opacity-10 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-blue-600 rounded-full opacity-10 blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <nav class="flex text-sm text-gray-400 mb-4">
                <a href="{{ route('home') }}" class="hover:text-yellow-500 transition-colors">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('affiliate.dashboard') }}" class="hover:text-yellow-500 transition-colors">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-white">Buat Booking</span>
            </nav>
            <h1 class="text-3xl md:text-4xl font-heading font-bold text-white mb-2">Buat Booking Tamu</h1>
            <p class="text-gray-400">Input data tamu Anda secara manual untuk mendapatkan komisi.</p>
        </div>
    </div>

    {{-- 2. FORM SECTION --}}
    <div class="bg-gray-50 py-12 min-h-screen">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <form action="{{ route('affiliate.bookings.store') }}" method="POST" id="bookingForm">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- KOLOM KIRI: INPUT DATA --}}
                    <div class="lg:col-span-2 space-y-6">
                        
                        {{-- Card: Detail Kamar --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2 border-b pb-4">
                                <i class="fas fa-bed text-yellow-500"></i> Pilih Kamar & Tanggal
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Pilih Kamar --}}
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tipe Kamar</label>
                                    <select name="room_id" id="room_id" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 transition-all" required>
                                        <option value="" data-price="0">-- Pilih Kamar --</option>
                                        
                                        @foreach($rooms as $room)
                                            @php
                                                // --- PERBAIKAN LOGIKA HARGA ---
                                                // 1. Ambil Harga Dasar (Biasanya dari HotelierMarket/Database)
                                                $finalPrice = $room->price; 

                                                // 2. Cek apakah user adalah affiliate & kamar punya diskon
                                                // Ini memastikan harga yang tampil adalah harga NETT Affiliate
                                                if(Auth::check() && Auth::user()->role === 'affiliate' && $room->discount_percentage > 0) {
                                                    $discountAmount = $finalPrice * ($room->discount_percentage / 100);
                                                    $finalPrice -= $discountAmount;
                                                }
                                            @endphp

                                            <option value="{{ $room->id }}" 
                                                    data-price="{{ $finalPrice }}" 
                                                    {{ (request('room_id') == $room->id) ? 'selected' : '' }}>
                                                {{ $room->name }} - Rp {{ number_format($finalPrice, 0, ',', '.') }} / malam
                                            </option>
                                        @endforeach

                                    </select>
                                    @error('room_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                {{-- Check In --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Check-in</label>
                                    <div class="relative">
                                        <input type="text" name="checkin" id="checkin" class="datepicker w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500" placeholder="Pilih Tanggal" required>
                                        <i class="fas fa-calendar absolute right-4 top-3.5 text-gray-400"></i>
                                    </div>
                                    @error('checkin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                {{-- Check Out --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Check-out</label>
                                    <div class="relative">
                                        <input type="text" name="checkout" id="checkout" class="datepicker w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500" placeholder="Pilih Tanggal" required>
                                        <i class="fas fa-calendar absolute right-4 top-3.5 text-gray-400"></i>
                                    </div>
                                    @error('checkout') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                {{-- Jumlah Kamar --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Kamar</label>
                                    <input type="number" name="num_rooms" id="num_rooms" min="1" value="1" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500" required>
                                </div>
                            </div>
                        </div>

                        {{-- Card: Data Tamu --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2 border-b pb-4">
                                <i class="fas fa-user-circle text-yellow-500"></i> Informasi Tamu
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Lengkap Tamu</label>
                                    <input type="text" name="guest_name" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500" placeholder="Contoh: Budi Santoso" required>
                                    @error('guest_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor WhatsApp</label>
                                        <input type="tel" name="guest_phone" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500" placeholder="08123456789" required>
                                        @error('guest_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Email Tamu</label>
                                        <input type="email" name="guest_email" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500" placeholder="tamu@email.com" required>
                                        @error('guest_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- KOLOM KANAN: RINGKASAN HARGA (Sticky) --}}
                    <div class="lg:col-span-1">
                        <div class="sticky top-24">
                            <div class="bg-white rounded-2xl shadow-lg border-t-4 border-yellow-500 overflow-hidden">
                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Pesanan</h3>
                                    
                                    {{-- Kalkulasi --}}
                                    <div class="space-y-3 text-sm border-b border-gray-100 pb-4 mb-4">
                                        <div class="flex justify-between items-center text-gray-600">
                                            <span>Harga Kamar</span>
                                            <span id="summary-price" class="font-medium">Rp 0</span>
                                        </div>
                                        <div class="flex justify-between items-center text-gray-600">
                                            <span>Durasi</span>
                                            <span id="summary-nights">0 Malam</span>
                                        </div>
                                        <div class="flex justify-between items-center text-gray-600">
                                            <span>Jumlah Kamar</span>
                                            <span id="summary-rooms">1 Unit</span>
                                        </div>
                                    </div>

                                    {{-- Total --}}
                                    <div class="flex justify-between items-center mb-6">
                                        <span class="text-gray-900 font-bold">Total Estimasi</span>
                                        <span id="summary-total" class="text-xl font-bold text-yellow-600">Rp 0</span>
                                    </div>

                                    {{-- Button --}}
                                    <button type="submit" class="w-full bg-gray-900 hover:bg-yellow-500 text-white hover:text-gray-900 font-bold py-3.5 rounded-lg transition-all duration-300 shadow-md flex items-center justify-center gap-2 group">
                                        <span>Proses Booking</span>
                                        <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
                                    </button>
                                </div>
                                <div class="bg-gray-50 px-6 py-4 text-xs text-gray-500 text-center">
                                    Komisi akan dihitung otomatis setelah pembayaran tamu dikonfirmasi.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT: Datepicker & Calculator --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Setup Flatpickr
            flatpickr(".datepicker", {
                dateFormat: "d-m-Y",
                minDate: "today",
                onChange: calculateTotal 
            });

            // 2. Elements
            const roomSelect = document.getElementById('room_id');
            const checkinInput = document.getElementById('checkin');
            const checkoutInput = document.getElementById('checkout');
            const numRoomsInput = document.getElementById('num_rooms');

            const summaryPrice = document.getElementById('summary-price');
            const summaryNights = document.getElementById('summary-nights');
            const summaryRooms = document.getElementById('summary-rooms');
            const summaryTotal = document.getElementById('summary-total');

            // 3. Calculator Logic
            function calculateTotal() {
                // Get Price from Selected Option Data Attribute
                const selectedOption = roomSelect.options[roomSelect.selectedIndex];
                const pricePerNight = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                const rooms = parseInt(numRoomsInput.value) || 1;

                // Date Diff
                let nights = 0;
                if (checkinInput.value && checkoutInput.value) {
                    const d1 = checkinInput.value.split('-').reverse().join('-'); // Format YYYY-MM-DD
                    const d2 = checkoutInput.value.split('-').reverse().join('-');
                    
                    const date1 = new Date(d1);
                    const date2 = new Date(d2);

                    if (date2 > date1) {
                        const timeDiff = Math.abs(date2 - date1);
                        nights = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
                    }
                }

                // Calculate Total
                const total = pricePerNight * nights * rooms;

                // Update UI
                const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
                
                summaryPrice.innerText = formatter.format(pricePerNight);
                summaryNights.innerText = nights + " Malam";
                summaryRooms.innerText = rooms + " Unit";
                summaryTotal.innerText = formatter.format(total);
            }

            // 4. Event Listeners
            roomSelect.addEventListener('change', calculateTotal);
            numRoomsInput.addEventListener('input', calculateTotal);
            
            // Run once on load
            calculateTotal();
        });
    </script>
    @endpush
@endsection