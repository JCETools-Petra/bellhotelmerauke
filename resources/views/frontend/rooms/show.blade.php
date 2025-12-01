@extends('layouts.frontend')

@section('seo_title', $room->seo_title ?: $room->name . ' - Bell Hotel Merauke')
@section('meta_description', $room->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($room->description), 160))

@section('content')
    {{-- HEADER IMAGE / BREADCRUMB --}}
    <div class="bg-gray-900 pt-24 pb-12 relative overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-20">
            @if($room->images->isNotEmpty())
                <img src="{{ asset('storage/' . $room->images->first()->path) }}" class="w-full h-full object-cover blur-sm">
            @endif
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <nav class="flex text-sm text-gray-400 mb-4">
                <a href="{{ route('home') }}" class="hover:text-yellow-500 transition-colors">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('rooms.index') }}" class="hover:text-yellow-500 transition-colors">Rooms</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $room->name }}</span>
            </nav>
            <h1 class="text-3xl md:text-5xl font-heading font-bold text-white mb-2">{{ $room->name }}</h1>
            <p class="text-yellow-500 text-lg md:text-xl font-medium">
                Rp {{ number_format($room->price, 0, ',', '.') }} <span class="text-sm text-gray-300">/ malam</span>
            </p>
        </div>
    </div>

    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12">
                
                {{-- KOLOM KIRI: GALERI & DESKRIPSI --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- 1. Modern Gallery --}}
                    <div class="bg-white rounded-2xl shadow-sm p-2 border border-gray-100 overflow-hidden">
                        @if($room->images->isNotEmpty())
                            {{-- Main Image --}}
                            <div class="relative h-[400px] md:h-[500px] rounded-xl overflow-hidden mb-2 group">
                                <img id="mainImage" src="{{ asset('storage/' . $room->images->first()->path) }}" 
                                     alt="{{ $room->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            </div>
                            {{-- Thumbnails --}}
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($room->images as $image)
                                    <button onclick="changeImage('{{ asset('storage/' . $image->path) }}')" 
                                            class="relative h-20 md:h-24 rounded-lg overflow-hidden cursor-pointer opacity-70 hover:opacity-100 transition-opacity focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                        <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <div class="h-[400px] bg-gray-200 rounded-xl flex items-center justify-center text-gray-400">
                                No Images Available
                            </div>
                        @endif
                    </div>

                    {{-- 2. Description --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 md:p-8 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-100 pb-4">Tentang Kamar Ini</h3>
                        <div class="prose max-w-none text-gray-600 leading-relaxed space-y-4">
                            {!! nl2br(e($room->description)) !!}
                        </div>
                    </div>

                    {{-- 3. Amenities --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 md:p-8 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Fasilitas Kamar</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-y-4 gap-x-6">
                            @php
                                $facilities = !empty($room->facilities) 
                                    ? explode("\n", $room->facilities) 
                                    : ["Free WiFi", "AC", "TV Cable", "Hot Shower", "Amenities", "Mineral Water"];
                            @endphp
                            
                            @foreach($facilities as $facility)
                                @if(trim($facility) !== '')
                                <div class="flex items-center text-gray-600">
                                    <span class="w-8 h-8 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-check text-xs"></i>
                                    </span>
                                    <span class="text-sm font-medium">{{ trim($facility) }}</span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN: BOOKING FORM (Sticky) --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-6">
                        
                        {{-- Booking Card --}}
                        <div class="bg-white rounded-2xl shadow-lg border-t-4 border-yellow-500 overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Book Your Stay</h3>
                                
                                <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                                    @csrf
                                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                                    <input type="hidden" id="room_price" value="{{ $room->price }}">

                                    {{-- Date Selection --}}
                                    <div class="space-y-4 mb-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Check-in</label>
                                            <div class="relative">
                                                <input type="text" class="datepicker w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-yellow-500 focus:border-yellow-500" 
                                                       id="checkin" name="checkin" value="{{ request('checkin') }}" placeholder="Pilih Tanggal" required>
                                                <i class="fas fa-calendar absolute right-3 top-3 text-gray-400"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Check-out</label>
                                            <div class="relative">
                                                <input type="text" class="datepicker w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-yellow-500 focus:border-yellow-500" 
                                                       id="checkout" name="checkout" value="{{ request('checkout') }}" placeholder="Pilih Tanggal" required>
                                                <i class="fas fa-calendar absolute right-3 top-3 text-gray-400"></i>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Room Count --}}
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jumlah Kamar</label>
                                        <input type="number" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-yellow-500 focus:border-yellow-500" 
                                               id="num_rooms" name="num_rooms" value="{{ request('rooms', 1) }}" min="1" required>
                                    </div>

                                    {{-- Guest Info --}}
                                    <div class="space-y-3 pt-4 border-t border-gray-100">
                                        <p class="text-xs font-bold text-gray-400 uppercase">Informasi Tamu</p>
                                        <input type="text" name="guest_name" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm" placeholder="Nama Lengkap" required>
                                        <input type="email" name="guest_email" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm" placeholder="Email Address" required>
                                        <input type="tel" name="guest_phone" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm" placeholder="No. WhatsApp" required>
                                    </div>

                                    {{-- Price Calculation Display --}}
                                    <div id="price-summary" class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-100 hidden">
                                        <div class="flex justify-between text-sm mb-2">
                                            <span class="text-gray-500">Harga x <span id="night-count">0</span> Malam</span>
                                            <span class="font-medium text-gray-900" id="base-total">Rp 0</span>
                                        </div>
                                        <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2 mt-2">
                                            <span class="text-gray-900">Total</span>
                                            <span class="text-yellow-600" id="grand-total">Rp 0</span>
                                        </div>
                                    </div>

                                    {{-- Submit Button --}}
                                    <button type="submit" class="w-full mt-6 bg-gray-900 hover:bg-yellow-500 text-white hover:text-gray-900 font-bold py-3.5 rounded-lg transition-all duration-300 shadow-lg transform hover:-translate-y-1">
                                        @if(settings('booking_method', 'direct') == 'direct')
                                            Lanjut Pembayaran
                                        @else
                                            Pesan via WhatsApp
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Contact Support --}}
                        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 text-center">
                            <p class="text-sm text-gray-500 mb-2">Butuh bantuan pemesanan?</p>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', settings('contact_phone') ?? '6281234567890') }}" target="_blank" class="text-green-600 font-bold hover:underline flex items-center justify-center gap-2">
                                <i class="fab fa-whatsapp text-lg"></i> Chat Admin
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script untuk Galeri & Kalkulasi Harga --}}
    @push('scripts')
    {{-- Load Flatpickr JS jika belum ada di layout utama --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <script>
        // 1. Image Gallery Switcher
        function changeImage(src) {
            document.getElementById('mainImage').src = src;
        }

        // 2. Inisialisasi Flatpickr & Kalkulator
        document.addEventListener('DOMContentLoaded', function() {
            
            // Inisialisasi Datepicker Manual untuk Input Spesifik ini
            flatpickr(".datepicker", {
                dateFormat: "d-m-Y",
                minDate: "today"
            });

            const checkin = document.getElementById('checkin');
            const checkout = document.getElementById('checkout');
            const numRooms = document.getElementById('num_rooms');
            const roomPrice = parseFloat(document.getElementById('room_price').value);
            const summaryBox = document.getElementById('price-summary');
            
            function calculateTotal() {
                if(checkin.value && checkout.value) {
                    const d1 = checkin.value.split('-').reverse().join('-');
                    const d2 = checkout.value.split('-').reverse().join('-');
                    
                    const date1 = new Date(d1);
                    const date2 = new Date(d2);
                    
                    if (date2 > date1) {
                        const timeDiff = Math.abs(date2 - date1);
                        const diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
                        const rooms = parseInt(numRooms.value) || 1;
                        
                        const total = roomPrice * diffDays * rooms;
                        
                        document.getElementById('night-count').innerText = diffDays;
                        document.getElementById('base-total').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(total);
                        document.getElementById('grand-total').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(total);
                        
                        summaryBox.classList.remove('hidden');
                    } else {
                        summaryBox.classList.add('hidden');
                    }
                }
            }

            [checkin, checkout, numRooms].forEach(el => {
                if(el) {
                    el.addEventListener('change', calculateTotal);
                    el.addEventListener('input', calculateTotal);
                }
            });
            
            calculateTotal();
        });
    </script>
    @endpush
@endsection