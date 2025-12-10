@extends('layouts.frontend')

@section('seo_title', 'MICE Facilities - Bell Hotel Merauke')
@section('meta_description', 'Ruang pertemuan dan ballroom modern di Merauke untuk berbagai kebutuhan acara bisnis, pernikahan, dan konferensi Anda.')

@section('content')
    {{-- 1. PAGE HEADER (Luxury Style) --}}
    <div class="relative bg-gray-900 py-24 sm:py-32 overflow-hidden">
        {{-- Background Image --}}
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=1920&auto=format&fit=crop" 
                 alt="MICE Background" 
                 class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
        </div>

        <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-yellow-500 font-bold uppercase tracking-[0.2em] text-sm mb-4 block animate-fade-in-up">Business & Events</span>
            <h1 class="text-4xl md:text-6xl font-heading font-bold text-white mb-6 tracking-tight animate-fade-in-up delay-100">
                MICE <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-600">Facilities</span>
            </h1>
            <p class="text-gray-300 text-lg max-w-3xl mx-auto font-light leading-relaxed animate-fade-in-up delay-200">
                Tempat sempurna untuk kesuksesan acara Anda. Dari pertemuan bisnis privat hingga konferensi berskala besar dan pernikahan impian, Bell Hotel menyediakan fasilitas modern dengan pelayanan bintang 4.
            </p>
        </div>
    </div>

    {{-- 2. MICE ROOMS LIST --}}
    <div class="bg-white py-16 sm:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(isset($miceRooms) && $miceRooms->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    @foreach($miceRooms as $index => $room)
                        {{-- Layout Zig-Zag (Alternating) --}}
                        <div class="group relative bg-gray-50 rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 flex flex-col {{ $index % 2 == 0 ? 'lg:flex-row' : 'lg:flex-row-reverse' }} lg:col-span-2 h-auto lg:h-[400px]">
                            
                            {{-- Image Section --}}
                            <div class="relative w-full lg:w-1/2 h-64 lg:h-full overflow-hidden">
                                <img src="{{ $room->image ? asset('storage/' . $room->image) : ($room->images->first() ? asset('storage/' . $room->images->first()->path) : 'https://placehold.co/800x600?text=MICE+Room') }}" 
                                     alt="{{ $room->name }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors duration-300"></div>
                            </div>

                            {{-- Content Section --}}
                            <div class="w-full lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center relative">
                                {{-- Decorative Number --}}
                                <span class="absolute top-6 right-8 text-6xl font-bold text-gray-100 -z-10 group-hover:text-yellow-50 transition-colors">
                                    0{{ $loop->iteration }}
                                </span>

                                <h3 class="text-2xl md:text-3xl font-heading font-bold text-gray-900 mb-4 group-hover:text-yellow-600 transition-colors">
                                    {{ $room->name }}
                                </h3>
                                
                                <p class="text-gray-500 mb-6 line-clamp-3 leading-relaxed">
                                    {{ $room->description }}
                                </p>

                                {{-- Specs / Capacity --}}
                                <div class="grid grid-cols-2 gap-4 mb-8">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 uppercase font-bold">Kapasitas</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $room->capacity ?? '50-100' }} Pax</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-ruler-combined"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 uppercase font-bold">Luas</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $room->size ?? '-' }} m²</p>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('mice.show', $room->slug ?? $room->id) }}" 
                                   class="inline-flex items-center text-gray-900 font-bold hover:text-yellow-600 transition-colors group/link">
                                    Lihat Detail Ruangan 
                                    <svg class="w-5 h-5 ml-2 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-6">
                        <i class="fas fa-door-closed text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Belum ada data Ruangan MICE</h3>
                    <p class="text-gray-500 mt-2">Silakan hubungi kami untuk informasi lebih lanjut.</p>
                </div>
            @endif

        </div>
    </div>

    {{-- 3. CTA: INQUIRY --}}
    <section class="bg-gray-900 py-20 relative overflow-hidden">
        {{-- Pattern --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-yellow-500 rounded-full filter blur-[100px] opacity-20 transform translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500 rounded-full filter blur-[100px] opacity-10 transform -translate-x-1/2 translate-y-1/2"></div>

        <div class="container mx-auto px-4 relative z-10 text-center">
            <h2 class="text-3xl md:text-4xl font-heading font-bold text-white mb-6">
                Rencanakan Acara Anda Bersama Kami
            </h2>
            <p class="text-gray-300 max-w-2xl mx-auto mb-10 text-lg">
                Tim event kami siap membantu menyusun kebutuhan acara Anda, mulai dari layout ruangan, katering, hingga peralatan teknis.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('contact.index') }}" class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold py-3.5 px-8 rounded-full transition-all transform hover:-translate-y-1 shadow-lg shadow-yellow-500/20">
                    Minta Penawaran
                </a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', settings('contact_phone') ?? '6281234567890') }}" target="_blank" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-gray-900 font-bold py-3.5 px-8 rounded-full transition-all flex items-center justify-center gap-2">
                    <i class="fab fa-whatsapp"></i> Chat Sales
                </a>
            </div>
        </div>
    </section>

    {{-- Style Tambahan untuk Animasi --}}
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