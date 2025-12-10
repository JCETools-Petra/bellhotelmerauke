@extends('layouts.frontend')

@section('seo_title', 'Akomodasi & Kamar - Bell Hotel Merauke')

@section('content')
    {{-- 1. PAGE HEADER --}}
    <div class="relative bg-gray-900 py-24 sm:py-32 overflow-hidden">
        {{-- Background Pattern/Image --}}
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=1920&auto=format&fit=crop" 
                 alt="Luxury Room Background" 
                 class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
        </div>

        <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-heading font-bold text-white mb-4 tracking-tight">
                Pilihan <span class="text-yellow-500">Akomodasi</span>
            </h1>
            <p class="text-gray-300 text-lg max-w-2xl mx-auto font-light leading-relaxed">
                Temukan kenyamanan istimewa dalam setiap detail kamar kami. Dirancang untuk memberikan pengalaman menginap terbaik di Merauke.
            </p>
        </div>
    </div>

    {{-- 2. ROOM LIST SECTION --}}
    <div class="bg-gray-50 py-16 sm:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Room Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($rooms as $room)
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col h-full">
                        
                        {{-- Image Wrapper --}}
                        <div class="relative h-64 overflow-hidden">
                            {{-- Logika Gambar --}}
                            <img src="{{ $room->image ? asset('storage/' . $room->image) : ($room->images->first() ? asset('storage/' . $room->images->first()->path) : 'https://placehold.co/600x400?text=No+Image') }}" 
                                 alt="{{ $room->name }}" 
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            
                            {{-- Badge Tipe (Optional) --}}
                            <div class="absolute top-4 right-4 bg-white/95 backdrop-blur px-3 py-1 rounded-full shadow-sm">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-800">
                                    {{ $room->type ?? 'Room' }}
                                </span>
                            </div>
                        </div>

                        {{-- Card Content --}}
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-xl font-heading font-bold text-gray-900 group-hover:text-yellow-600 transition-colors">
                                    {{ $room->name }}
                                </h3>
                            </div>

                            <p class="text-gray-500 text-sm mb-6 line-clamp-3 flex-grow leading-relaxed">
                                {{ $room->description }}
                            </p>

                            {{-- Price & Action (MODIFIKASI HARGA DINAMIS) --}}
                            <div class="pt-6 border-t border-gray-100 flex items-center justify-between mt-auto">
                                <div>
                                    <span class="text-xs text-gray-400 uppercase font-semibold">Mulai dari</span>
                                    <div class="text-lg font-bold text-gray-900">
                                        
                                        @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'affiliate']))
                                            {{-- Tampilan Affiliate: Harga Coret & Harga Diskon --}}
                                            <div class="flex flex-col leading-tight">
                                                <span class="text-xs text-gray-400 line-through font-normal">
                                                    Rp {{ number_format($room->calculated_public_price, 0, ',', '.') }}
                                                </span>
                                                <span class="text-yellow-600">
                                                    Rp {{ number_format($room->calculated_affiliate_price, 0, ',', '.') }}
                                                    <span class="text-sm font-normal text-gray-500 text-black">/malam</span>
                                                </span>
                                            </div>
                                        @else
                                            {{-- Tampilan Public: Harga Hotelier + 3% --}}
                                            <span>
                                                Rp {{ number_format($room->calculated_public_price, 0, ',', '.') }}
                                                <span class="text-sm font-normal text-gray-500">/malam</span>
                                            </span>
                                        @endif

                                    </div>
                                </div>
                                <a href="{{ route('rooms.show', $room->slug ?? $room->id) }}" 
                                   class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-900 hover:bg-yellow-500 text-white hover:text-gray-900 font-medium text-sm rounded-lg transition-all duration-300 group-hover:shadow-lg">
                                    Detail
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                            {{-- AKHIR MODIFIKASI --}}
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada kamar tersedia</h3>
                        <p class="text-gray-500 mt-1">Silakan periksa kembali nanti.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-12 flex justify-center">
                {{ $rooms->links() }} 
            </div>
        </div>
    </div>
@endsection