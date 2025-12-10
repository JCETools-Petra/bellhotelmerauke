@extends('layouts.frontend')

@section('seo_title', $restaurant->seo_title ?: $restaurant->name . ' - Dining at Bell Hotel')
@section('meta_description', $restaurant->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($restaurant->description), 160))

@section('content')
    {{-- 1. HERO HEADER --}}
    <div class="relative bg-gray-900 pt-24 pb-16 sm:pb-20 overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-40">
            @if($restaurant->images->isNotEmpty())
                <img src="{{ asset('storage/' . $restaurant->images->first()->path) }}" class="w-full h-full object-cover blur-sm scale-105">
            @else
                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1000" class="w-full h-full object-cover blur-sm">
            @endif
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/50 to-transparent z-0"></div>
        
        <div class="container mx-auto px-4 relative z-10 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 text-xs font-bold tracking-widest uppercase mb-4 backdrop-blur-sm">
                {{ $restaurant->cuisine_type ?? 'Fine Dining' }}
            </span>
            <h1 class="text-4xl md:text-6xl font-heading font-bold text-white mb-4 tracking-tight shadow-sm">{{ $restaurant->name }}</h1>
            
            <div class="flex items-center justify-center gap-6 text-gray-300 text-sm md:text-base">
                @if($restaurant->opening_hours)
                <span class="flex items-center gap-2"><i class="far fa-clock text-yellow-500"></i> {{ $restaurant->opening_hours }}</span>
                @endif
                @if($restaurant->location)
                <span class="flex items-center gap-2"><i class="fas fa-map-marker-alt text-yellow-500"></i> {{ $restaurant->location }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- 2. MAIN CONTENT (Split Layout) --}}
    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12">
                
                {{-- KOLOM KIRI: GALERI & CERITA --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- Modern Gallery --}}
                    <div class="bg-white rounded-2xl shadow-sm p-2 border border-gray-100 overflow-hidden">
                        @if($restaurant->images->isNotEmpty())
                            {{-- Main View --}}
                            <div class="relative h-[400px] md:h-[500px] rounded-xl overflow-hidden mb-2 group cursor-zoom-in">
                                <img id="mainImage" src="{{ asset('storage/' . $restaurant->images->first()->path) }}" 
                                     alt="{{ $restaurant->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            </div>
                            {{-- Thumbnails --}}
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($restaurant->images as $image)
                                    <button onclick="changeImage('{{ asset('storage/' . $image->path) }}')" 
                                            class="relative h-20 md:h-24 rounded-lg overflow-hidden cursor-pointer opacity-70 hover:opacity-100 transition-all hover:ring-2 hover:ring-yellow-500">
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

                    {{-- About / Description --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 md:p-8 border border-gray-100">
                        <h3 class="text-2xl font-heading font-bold text-gray-900 mb-4 border-b border-gray-100 pb-4">
                            Tentang <span class="text-yellow-600">{{ $restaurant->name }}</span>
                        </h3>
                        <div class="prose max-w-none text-gray-600 leading-relaxed text-lg">
                            {!! nl2br(e($restaurant->description)) !!}
                        </div>
                    </div>

                    {{-- Menu Highlights (Optional - Jika ada data menu di deskripsi atau kolom lain) --}}
                    {{-- Contoh Statis untuk Layout --}}
                    <div class="bg-gray-900 rounded-2xl shadow-lg p-8 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-500 rounded-full filter blur-[60px] opacity-20"></div>
                        <h3 class="text-xl font-bold text-yellow-500 mb-6 relative z-10">Signature Dishes</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-2xl">🥩</div>
                                <div>
                                    <h4 class="font-bold text-lg">Chef's Special Steak</h4>
                                    <p class="text-sm text-gray-400">Daging impor pilihan dengan saus spesial racikan chef.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-2xl">🦞</div>
                                <div>
                                    <h4 class="font-bold text-lg">Seafood Platter</h4>
                                    <p class="text-sm text-gray-400">Aneka hidangan laut segar khas Merauke.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN: INFO & RESERVASI (Sticky) --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-6">
                        
                        {{-- Info Card --}}
                        <div class="bg-white rounded-2xl shadow-lg border-t-4 border-yellow-500 overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-6">Informasi Restoran</h3>
                                
                                <ul class="space-y-5">
                                    <li class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center flex-shrink-0">
                                            <i class="far fa-clock"></i>
                                        </div>
                                        <div>
                                            <span class="block text-xs font-bold text-gray-400 uppercase">Jam Operasional</span>
                                            <span class="text-gray-900 font-medium">{{ $restaurant->opening_hours ?? 'Setiap Hari, 10:00 - 22:00' }}</span>
                                        </div>
                                    </li>
                                    
                                    <li class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-utensils"></i>
                                        </div>
                                        <div>
                                            <span class="block text-xs font-bold text-gray-400 uppercase">Jenis Masakan</span>
                                            <span class="text-gray-900 font-medium">{{ $restaurant->cuisine_type ?? 'Indonesian & Western' }}</span>
                                        </div>
                                    </li>

                                    <li class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-map-signs"></i>
                                        </div>
                                        <div>
                                            <span class="block text-xs font-bold text-gray-400 uppercase">Lokasi</span>
                                            <span class="text-gray-900 font-medium">{{ $restaurant->location ?? 'Lobby Level, Bell Hotel' }}</span>
                                        </div>
                                    </li>
                                </ul>

                                <hr class="my-6 border-gray-100">

                                <h4 class="text-sm font-bold text-gray-900 mb-3">Reservasi Meja</h4>
                                @if($restaurant->contact_number)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurant->contact_number) }}" target="_blank" 
                                       class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition-all shadow-lg hover:shadow-green-500/30 mb-3">
                                        <i class="fab fa-whatsapp text-xl"></i> Pesan via WhatsApp
                                    </a>
                                @else
                                    <a href="{{ route('contact.index') }}" 
                                       class="w-full flex items-center justify-center gap-2 bg-gray-900 hover:bg-yellow-500 text-white hover:text-gray-900 font-bold py-3 rounded-lg transition-all">
                                        Hubungi Kami
                                    </a>
                                @endif
                                <p class="text-xs text-center text-gray-400 mt-2">Kami menyarankan reservasi H-1 untuk grup besar.</p>
                            </div>
                        </div>

                        {{-- Promo Banner (Optional) --}}
                        <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group">
                            <div class="absolute -right-6 -bottom-6 text-8xl text-white opacity-10 transform rotate-12 group-hover:rotate-0 transition-transform duration-500">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <h4 class="text-xl font-bold mb-2 relative z-10">Diskon Spesial?</h4>
                            <p class="text-yellow-50 text-sm mb-4 relative z-10">Dapatkan penawaran khusus untuk pemesanan acara ulang tahun atau *private dining*.</p>
                            <a href="{{ route('contact.index') }}" class="inline-block bg-white text-orange-600 text-sm font-bold px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors relative z-10">
                                Tanya Promo
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function changeImage(src) {
            const mainImage = document.getElementById('mainImage');
            mainImage.style.opacity = '0.5';
            setTimeout(() => {
                mainImage.src = src;
                mainImage.style.opacity = '1';
            }, 150);
        }
    </script>
    @endpush
@endsection