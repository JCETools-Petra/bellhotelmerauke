@extends('layouts.frontend')

@section('title', 'Home')

@section('content')
    {{-- 1. HERO SECTION (Full Screen Slider) --}}
    <div class="relative w-full h-[85vh] overflow-hidden bg-gray-900">
        <div class="absolute inset-0 z-0">
            @if(isset($heroSliders) && count($heroSliders) > 0)
                <div class="swiper-container h-full w-full">
                    <div class="swiper-wrapper">
                        @foreach($heroSliders as $slider)
                            <div class="swiper-slide relative">
                                <img src="{{ asset('storage/' . $slider->image_path) }}" class="w-full h-full object-cover" alt="Hero Image">
                                <div class="absolute inset-0 bg-black/40"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=1920&auto=format&fit=crop" 
                     alt="Bell Hotel Merauke" 
                     class="w-full h-full object-cover animate-slow-zoom">
                <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/20 to-gray-100"></div>
            @endif
        </div>

        <div class="absolute inset-0 z-10 flex flex-col items-center justify-center text-center px-4">
            <span class="text-yellow-400 tracking-[0.2em] uppercase text-sm md:text-base font-semibold mb-4 animate-fade-in-up">
                Welcome to Bell Hotel Merauke
            </span>
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight drop-shadow-lg animate-fade-in-up delay-100">
                Experience Comfort <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-600">in The East</span>
            </h1>
            <p class="text-gray-200 text-lg md:text-xl max-w-2xl mb-8 font-light animate-fade-in-up delay-200">
                Kenyamanan istimewa di jantung kota Merauke dengan fasilitas MICE terlengkap dan pelayanan berkelas.
            </p>
            <a href="#rooms" class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold py-3 px-8 rounded-full transition-all transform hover:scale-105 shadow-lg shadow-yellow-500/30 animate-fade-in-up delay-300">
                Jelajahi Kamar
            </a>
        </div>
    </div>

    {{-- 2. BOOKING WIDGET (Floating Modern) --}}
    <div class="relative z-30 -mt-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-2xl p-6 md:p-8 border-t-4 border-yellow-500">
            <form action="{{ route('rooms.availability') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Check In</label>
                    <div class="relative">
                        <input type="text" class="datepicker w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block p-3 pl-10" id="checkin" name="checkin" required placeholder="Select Date">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="far fa-calendar text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Check Out</label>
                    <div class="relative">
                        <input type="text" class="datepicker w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block p-3 pl-10" id="checkout" name="checkout" required placeholder="Select Date">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="far fa-calendar text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Tamu & Kamar</label>
                    <div class="relative">
                        <select name="guests" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block p-3 pl-10 appearance-none">
                            <option value="1">1 Tamu</option>
                            <option value="2" selected>2 Tamu</option>
                            <option value="3">3 Tamu</option>
                            <option value="4">4+ Tamu</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-user-friends text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-1">
                    <button type="submit" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3.5 px-6 rounded-lg transition-all duration-300 flex items-center justify-center gap-2 group">
                        <span>Cek Ketersediaan</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform text-yellow-500"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 3. FEATURED ROOMS --}}
    <section id="rooms" class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-yellow-500 font-bold uppercase tracking-wider text-sm">Akomodasi</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-2 mb-4">Pilihan Kamar Eksklusif</h2>
                <div class="h-1 w-20 bg-yellow-500 mx-auto rounded"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @if(isset($rooms) && $rooms->count() > 0)
                    @foreach($rooms as $room)
                    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col h-full">
                        <div class="relative h-64 overflow-hidden flex-shrink-0">
                            <img src="{{ $room->image ? asset('storage/' . $room->image) : ($room->images->first() ? asset('storage/' . $room->images->first()->path) : 'https://placehold.co/600x400') }}" 
                                 alt="{{ $room->name }}" 
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-gray-900 shadow">
                                {{ $room->type ?? 'Deluxe' }}
                            </div>
                        </div>
                        
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-yellow-600 transition-colors">
                                {{ $room->name }}
                            </h3>
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2 flex-grow">
                                {{ $room->description }}
                            </p>
                            
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                <div>
                                    <span class="text-xs text-gray-400 uppercase">Mulai dari</span>
                                    <div class="text-lg font-bold text-gray-900">
                                        Rp {{ number_format($room->price, 0, ',', '.') }}
                                        <span class="text-xs text-gray-400 font-normal">/ malam</span>
                                    </div>
                                </div>
                                <a href="{{ route('rooms.show', $room->slug ?? $room->id) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-all">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('rooms.index') }}" class="inline-block border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white font-bold py-3 px-8 rounded-full transition-all duration-300">
                    Lihat Semua Kamar
                </a>
            </div>
        </div>
    </section>

    {{-- 4. MICE & FACILITIES (Smart Grid Layout) --}}
    <section class="py-20 bg-gray-900 text-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-stretch gap-12 mb-20">
                
                {{-- LEFT COLUMN: Description --}}
                <div class="lg:w-1/2 flex flex-col justify-center order-2 lg:order-1">
                    <span class="text-yellow-500 font-bold uppercase tracking-wider text-sm">Business & Events</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold mt-2 mb-6">MICE Facilities</h2>
                    <p class="text-gray-400 mb-8 leading-relaxed">
                        Tingkatkan kesuksesan acara Anda dengan fasilitas meeting dan ballroom kami yang modern.
                    </p>
                    <ul class="space-y-4 mb-10 text-gray-300">
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-yellow-500 text-xl"></i> Ballroom Kapasitas Besar</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-yellow-500 text-xl"></i> Meeting Room Privat</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-yellow-500 text-xl"></i> Paket Fullboard & Halfboard</li>
                    </ul>
                    <div>
                        <a href="{{ route('mice.index') }}" class="inline-block bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold py-3 px-8 rounded-lg transition-all">
                            Pelajari Paket MICE
                        </a>
                    </div>
                </div>
                
                {{-- RIGHT COLUMN: DYNAMIC MICE IMAGES (Smart Grid 1-2 or 2-2) --}}
                <div class="lg:w-1/2 order-1 lg:order-2">
                    <div class="grid grid-cols-2 gap-4 h-full min-h-[400px]">
                        @if(isset($miceRooms) && $miceRooms->count() > 0)
                            @foreach($miceRooms as $mice)
                                {{-- 
                                   LOGIKA GRID PINTAR:
                                   - Jika total ada 3 ruangan (Muting, Bupul, Sota): 
                                     Gambar pertama akan LEBAR (col-span-2), 2 berikutnya berdampingan.
                                   - Jika total 2 ruangan: Keduanya berdampingan rata.
                                --}}
                                <div class="relative group overflow-hidden rounded-2xl h-full min-h-[200px] 
                                    {{ $miceRooms->count() == 3 && $loop->first ? 'col-span-2 row-span-2 min-h-[300px]' : 'col-span-1' }}">
                                    
                                    <img src="{{ $mice->image ? asset('storage/' . $mice->image) : ($mice->images->first() ? asset('storage/' . $mice->images->first()->path) : 'https://placehold.co/600x800') }}" 
                                         alt="{{ $mice->name }}" 
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    
                                    {{-- Overlay Gradient --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity"></div>
                                    
                                    {{-- Text Content --}}
                                    <div class="absolute bottom-0 left-0 right-0 p-5 translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                        <h4 class="text-white font-bold text-lg mb-1">{{ $mice->name }}</h4>
                                        <p class="text-gray-300 text-xs flex items-center gap-2">
                                            <i class="fas fa-users text-yellow-500"></i> {{ $mice->capacity ?? '50-100' }} Pax
                                        </p>
                                    </div>
                                    
                                    <a href="{{ route('mice.show', $mice->slug ?? $mice->id) }}" class="absolute inset-0 z-10" aria-label="View {{ $mice->name }}"></a>
                                </div>
                            @endforeach

                        @else
                            {{-- Fallback Total (Jika data kosong) --}}
                            <div class="col-span-2 h-64 relative rounded-2xl overflow-hidden bg-gray-800 flex items-center justify-center border border-gray-700">
                                <span class="text-gray-500">Belum ada data MICE</span>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- 5. TESTIMONI AFFILIATE (ADDED SECTION) --}}
    @if(isset($reviews) && $reviews->count() > 0)
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-yellow-500 font-bold uppercase tracking-wider text-sm">Testimoni</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-2">Kata Mitra Kami</h2>
                <p class="text-gray-500 mt-4 max-w-2xl mx-auto">
                    Pengalaman mereka yang telah bergabung dan mendapatkan keuntungan dari Program Afiliasi Bell Hotel.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($reviews as $review)
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white font-bold text-xl mr-4 shadow-lg shadow-yellow-500/30">
                            {{ substr($review->affiliate->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $review->affiliate->user->name }}</h4>
                            <div class="flex text-yellow-400 text-sm mt-1">
                                @for($i=1; $i<=5; $i++)
                                    <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <i class="fas fa-quote-left text-gray-100 text-4xl absolute -top-2 -left-2 -z-10"></i>
                        <p class="text-gray-600 italic leading-relaxed z-10 relative">"{{ $review->review }}"</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- 6. CTA / AFFILIATE TEASER --}}
    <section class="py-20 bg-yellow-500 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 rounded-full bg-yellow-400 opacity-50"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-yellow-600 opacity-20"></div>

        <div class="container mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-6">Bergabunglah Bersama Kami</h2>
            <p class="text-gray-900 text-lg max-w-2xl mx-auto mb-8 font-medium">
                Dapatkan penghasilan tambahan dengan merekomendasikan Bell Hotel Merauke.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('pages.affiliate_info') }}" class="bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all transform hover:-translate-y-1">
                    Info Affiliate
                </a>
                <a href="{{ route('contact.index') }}" class="bg-transparent border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white font-bold py-3 px-8 rounded-full transition-all">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    <style>
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.8s ease-out forwards; opacity: 0; }
        
        @keyframes slow-zoom {
            from { transform: scale(1); }
            to { transform: scale(1.1); }
        }
        .animate-slow-zoom { animation: slow-zoom 20s linear infinite alternate; }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
    </style>
    
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr(".datepicker", {
                dateFormat: "d-m-Y",
                minDate: "today"
            });
        });
    </script>
    @endpush
@endsection