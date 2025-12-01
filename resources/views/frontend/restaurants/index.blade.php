@extends('layouts.frontend')

@section('seo_title', 'Dining & Restaurants - Bell Hotel Merauke')
@section('meta_description', 'Nikmati sajian kuliner terbaik di Merauke. Dari hidangan lokal autentik hingga cita rasa internasional dengan suasana yang elegan.')

@section('content')
    {{-- 1. HERO SECTION (Culinary Theme) --}}
    <div class="relative bg-gray-900 py-24 sm:py-32 overflow-hidden">
        {{-- Background Image --}}
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?q=80&w=1920&auto=format&fit=crop" 
                 alt="Dining Background" 
                 class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
        </div>

        <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-yellow-500 font-bold uppercase tracking-[0.2em] text-sm mb-4 block animate-fade-in-up">Exquisite Dining</span>
            <h1 class="text-4xl md:text-6xl font-heading font-bold text-white mb-6 tracking-tight animate-fade-in-up delay-100">
                Taste of <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-600">Luxury</span>
            </h1>
            <p class="text-gray-300 text-lg max-w-2xl mx-auto font-light leading-relaxed animate-fade-in-up delay-200">
                Jelajahi ragam kuliner istimewa yang disiapkan oleh chef berpengalaman kami. Perpaduan rasa lokal Papua dan internasional dalam suasana yang hangat dan elegan.
            </p>
        </div>
    </div>

    {{-- 2. RESTAURANT LIST --}}
    <div class="bg-gray-50 py-16 sm:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="space-y-16 sm:space-y-24">
                @forelse($restaurants as $index => $restaurant)
                    {{-- Restaurant Card (Zig-Zag Layout) --}}
                    <div class="flex flex-col {{ $index % 2 == 0 ? 'lg:flex-row' : 'lg:flex-row-reverse' }} gap-8 lg:gap-16 items-center group">
                        
                        {{-- Image Section --}}
                        <div class="w-full lg:w-1/2 relative">
                            <div class="relative h-[300px] sm:h-[400px] rounded-2xl overflow-hidden shadow-xl border border-gray-100 group-hover:shadow-2xl transition-all duration-500">
                                {{-- Logika Gambar Pintar --}}
                                <img src="{{ $restaurant->cover_image ? asset('storage/' . $restaurant->cover_image) : ($restaurant->images->first() ? asset('storage/' . $restaurant->images->first()->path) : 'https://placehold.co/800x600?text=Restaurant') }}" 
                                     alt="{{ $restaurant->name }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                
                                {{-- Badge (Optional: Type/Cuisine) --}}
                                @if($restaurant->cuisine_type)
                                <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-4 py-1.5 rounded-full shadow-sm">
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-900">
                                        {{ $restaurant->cuisine_type }}
                                    </span>
                                </div>
                                @endif
                            </div>
                            {{-- Decorative Element --}}
                            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-yellow-100 rounded-full -z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>

                        {{-- Content Section --}}
                        <div class="w-full lg:w-1/2 space-y-6">
                            <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 group-hover:text-yellow-600 transition-colors">
                                {{ $restaurant->name }}
                            </h2>
                            
                            <p class="text-gray-600 text-lg leading-relaxed">
                                {{ Str::limit($restaurant->description, 200) }}
                            </p>

                            {{-- Info Grid --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-4 border-t border-b border-gray-200">
                                {{-- Jam Buka --}}
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 flex-shrink-0">
                                        <i class="far fa-clock"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Jam Buka</p>
                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $restaurant->opening_hours ?? '10:00 - 22:00' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Lokasi / Kapasitas --}}
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 flex-shrink-0">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Lokasi</p>
                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $restaurant->location ?? 'Lantai Dasar' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 pt-2">
                                <a href="{{ route('restaurants.show', $restaurant->slug ?? $restaurant->id) }}" 
                                   class="inline-flex items-center justify-center px-6 py-3 bg-gray-900 hover:bg-yellow-500 text-white hover:text-gray-900 font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    Lihat Menu & Detail
                                </a>
                                
                                @if($restaurant->contact_number)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurant->contact_number) }}" target="_blank" 
                                   class="inline-flex items-center justify-center w-12 h-12 border-2 border-gray-200 rounded-full text-gray-600 hover:border-green-500 hover:bg-green-50 hover:text-green-600 transition-all">
                                    <i class="fab fa-whatsapp text-xl"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 mb-6">
                            <i class="fas fa-utensils text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Coming Soon</h3>
                        <p class="text-gray-500 mt-2 max-w-md mx-auto">
                            Kami sedang menyiapkan pengalaman kuliner terbaik untuk Anda. Silakan kembali lagi nanti.
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- 3. CTA: RESERVATION --}}
    <section class="py-20 bg-yellow-50 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-yellow-400 via-yellow-200 to-yellow-400"></div>
        
        <div class="container mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl font-heading font-bold text-gray-900 mb-4">
                Ingin Mengadakan Private Dinner?
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto mb-8">
                Kami melayani pemesanan untuk acara ulang tahun, *romantic dinner*, atau pertemuan bisnis privat dengan menu spesial.
            </p>
            <a href="{{ route('contact.index') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-yellow-500 text-gray-900 hover:text-yellow-600 font-bold py-3 px-8 rounded-full shadow-sm hover:shadow-md transition-all">
                <i class="far fa-calendar-alt"></i> Reservasi Sekarang
            </a>
        </div>
    </section>

    {{-- Animation Style --}}
    <style>
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.8s ease-out forwards; opacity: 0; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
    </style>
@endsection