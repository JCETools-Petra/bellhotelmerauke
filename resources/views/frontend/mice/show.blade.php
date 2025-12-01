@extends('layouts.frontend')

@section('seo_title', $mice->seo_title ?: $mice->name . ' - Bell Hotel Merauke')
@section('meta_description', $mice->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($mice->description), 160))

@section('content')
    {{-- 1. HEADER IMAGE --}}
    <div class="bg-gray-900 pt-24 pb-12 relative overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-30">
            @if($mice->images->isNotEmpty())
                <img src="{{ asset('storage/' . $mice->images->first()->path) }}" class="w-full h-full object-cover blur-sm">
            @endif
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent z-0"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <nav class="flex text-sm text-gray-400 mb-4">
                <a href="{{ route('home') }}" class="hover:text-yellow-500 transition-colors">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('mice.index') }}" class="hover:text-yellow-500 transition-colors">MICE</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $mice->name }}</span>
            </nav>
            <h1 class="text-3xl md:text-5xl font-heading font-bold text-white mb-2">{{ $mice->name }}</h1>
            <p class="text-yellow-500 text-lg font-medium flex items-center gap-2">
                <i class="fas fa-users"></i> Kapasitas: {{ $mice->capacity ?? 'Hubungi Kami' }} Pax
            </p>
        </div>
    </div>

    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12">
                
                {{-- KOLOM KIRI: GALERI & INFO --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- Gallery --}}
                    <div class="bg-white rounded-2xl shadow-sm p-2 border border-gray-100 overflow-hidden">
                        @if($mice->images->isNotEmpty())
                            <div class="relative h-[400px] md:h-[500px] rounded-xl overflow-hidden mb-2 group">
                                <img id="mainImage" src="{{ asset('storage/' . $mice->images->first()->path) }}" 
                                     alt="{{ $mice->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            </div>
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($mice->images as $image)
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

                    {{-- Description --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 md:p-8 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-100 pb-4">Deskripsi Ruangan</h3>
                        <div class="prose max-w-none text-gray-600 leading-relaxed">
                            {!! nl2br(e($mice->description)) !!}
                        </div>
                    </div>

                    {{-- Specifications / Facilities --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 md:p-8 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Fasilitas & Spesifikasi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Fitur Standar (Hardcoded jika tidak ada di DB, atau ambil dari DB jika ada) --}}
                            @php
                                $features = !empty($mice->facilities) 
                                    ? explode("\n", $mice->facilities) 
                                    : ["Sound System Standard", "LCD Projector & Screen", "High-Speed Wi-Fi", "Standard Lighting", "Flipchart & Markers", "Mineral Water & Candies"];
                            @endphp

                            @foreach($features as $feature)
                                @if(trim($feature) !== '')
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-check-circle text-sm"></i>
                                    </div>
                                    <span class="text-gray-600 text-sm font-medium mt-1.5">{{ trim($feature) }}</span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN: INQUIRY FORM (Sticky) --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-6">
                        
                        {{-- Inquiry Card --}}
                        <div class="bg-white rounded-2xl shadow-lg border-t-4 border-yellow-500 overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Minta Penawaran</h3>
                                <p class="text-sm text-gray-500 mb-4">Isi formulir untuk mendapatkan penawaran spesial.</p>
                                
                                {{-- Tampilkan Pesan Sukses --}}
                                @if(session('success'))
                                    <div class="mb-4 p-3 bg-green-100 text-green-700 text-xs rounded-lg border border-green-200">
                                        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                                    </div>
                                @endif

                                <form action="{{ route('mice.inquiries.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="mice_room_id" value="{{ $mice->id }}">

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                                            <input type="text" name="customer_name" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-yellow-500 focus:border-yellow-500" required placeholder="Nama Anda / Perusahaan">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nomor WhatsApp</label>
                                            <input type="tel" name="customer_phone" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-yellow-500 focus:border-yellow-500" required placeholder="08xxxxxxxxxx">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jenis Acara</label>
                                            <select name="event_type" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-yellow-500 focus:border-yellow-500">
                                                <option value="meeting">Meeting / Rapat</option>
                                                <option value="wedding">Wedding / Pernikahan</option>
                                                <option value="seminar">Seminar / Workshop</option>
                                                <option value="birthday">Birthday / Ulang Tahun</option>
                                                <option value="other">Lainnya</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Detail Tambahan</label>
                                            <textarea name="event_other_description" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-yellow-500 focus:border-yellow-500" placeholder="Tanggal acara, jumlah peserta, atau request khusus..."></textarea>
                                        </div>

                                        <button type="submit" class="w-full bg-gray-900 hover:bg-yellow-500 text-white hover:text-gray-900 font-bold py-3.5 rounded-lg transition-all duration-300 shadow-lg transform hover:-translate-y-1 flex items-center justify-center gap-2">
                                            <span>Kirim Permintaan</span>
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Contact Sales --}}
                        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 text-center">
                            <p class="text-sm text-gray-500 mb-3">Ingin respon lebih cepat?</p>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', settings('contact_phone') ?? '6281234567890') }}" target="_blank" class="inline-flex items-center justify-center px-5 py-2.5 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg font-bold transition-colors w-full">
                                <i class="fab fa-whatsapp text-lg mr-2"></i> Chat Marketing
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Image Gallery Switcher
        function changeImage(src) {
            const mainImage = document.getElementById('mainImage');
            mainImage.style.opacity = '0';
            setTimeout(() => {
                mainImage.src = src;
                mainImage.style.opacity = '1';
            }, 200);
        }
    </script>
    @endpush
@endsection