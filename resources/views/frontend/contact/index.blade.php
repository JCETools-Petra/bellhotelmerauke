@extends('layouts.frontend')

@section('seo_title', 'Contact Us - Bell Hotel Merauke')
@section('meta_description', 'Hubungi Bell Hotel Merauke untuk reservasi kamar, MICE, atau restoran. Kami siap melayani kebutuhan Anda 24 jam.')

@section('content')
    {{-- 1. HERO HEADER KONTAK (AMAN TANPA VARIABEL) --}}
    <div class="relative bg-gray-900 min-h-[60vh] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-40">
            {{-- Gunakan gambar statis dari Unsplash atau asset lokal --}}
            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1920&auto=format&fit=crop" 
                 alt="Contact Us" 
                 class="w-full h-full object-cover blur-sm">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/50 to-transparent z-0"></div>
        
        <div class="container mx-auto px-4 relative z-10 text-center">
            <span class="text-yellow-500 font-bold uppercase tracking-[0.2em] text-sm mb-4 block">Get In Touch</span>
            <h1 class="text-5xl md:text-7xl font-heading font-bold text-white mb-6 tracking-tight shadow-sm leading-tight">
                Hubungi <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-600">Kami</span>
            </h1>
            <p class="text-gray-300 text-lg max-w-2xl mx-auto font-light leading-relaxed">
                Tim kami siap membantu merencanakan pengalaman menginap atau acara terbaik Anda di Merauke.
            </p>
        </div>
    </div>

    {{-- 2. MAIN CONTENT --}}
    <div class="bg-gray-50 py-16 md:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- INFO & FORM --}}
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 mb-16">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    
                    {{-- KOLOM KIRI: INFO KONTAK (Dark Theme) --}}
                    <div class="bg-gray-900 p-10 sm:p-12 text-white flex flex-col justify-between relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-yellow-500 rounded-full opacity-20 blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 bg-blue-500 rounded-full opacity-20 blur-3xl"></div>

                        <div class="relative z-10">
                            <h3 class="text-2xl font-heading font-bold text-white mb-8">Informasi Kontak</h3>
                            
                            <div class="space-y-8">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center flex-shrink-0 text-yellow-500">
                                        <i class="fas fa-map-marker-alt text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-200 mb-1">Alamat</h4>
                                        <p class="text-gray-400 text-sm leading-relaxed" style="white-space: pre-wrap;">{{ $settings['contact_address'] ?? 'Jl. Raya Mandala No. 123, Merauke' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center flex-shrink-0 text-yellow-500">
                                        <i class="fas fa-phone-alt text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-200 mb-1">Telepon & Reservasi</h4>
                                        <p class="text-gray-400 text-sm mb-2">Siap melayani 24 Jam</p>
                                        <a href="tel:{{ preg_replace('/[^0-9]/', '', $settings['contact_phone'] ?? '') }}" class="text-white font-bold hover:text-yellow-500 transition-colors text-lg">
                                            {{ $settings['contact_phone'] ?? '+62 812 3456 7890' }}
                                        </a>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center flex-shrink-0 text-yellow-500">
                                        <i class="fas fa-envelope text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-200 mb-1">Email</h4>
                                        <p class="text-gray-400 text-sm mb-2">Untuk kerjasama & pertanyaan umum</p>
                                        <a href="mailto:{{ $settings['contact_email'] ?? 'info@bellhotel.com' }}" class="text-white hover:text-yellow-500 transition-colors">
                                            {{ $settings['contact_email'] ?? 'info@bellhotelmerauke.com' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative z-10 mt-12">
                            <h4 class="font-bold text-gray-200 mb-4 text-sm uppercase tracking-wider">Ikuti Kami</h4>
                            <div class="flex gap-4">
                                @if(!empty($settings['contact_instagram']))
                                <a href="{{ $settings['contact_instagram'] }}" class="w-10 h-10 rounded-full bg-gray-800 hover:bg-yellow-500 hover:text-gray-900 flex items-center justify-center transition-all">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                @endif
                                @if(!empty($settings['contact_facebook']))
                                <a href="{{ $settings['contact_facebook'] }}" class="w-10 h-10 rounded-full bg-gray-800 hover:bg-yellow-500 hover:text-gray-900 flex items-center justify-center transition-all">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                @endif
                                @if(!empty($settings['contact_youtube']))
                                <a href="{{ $settings['contact_youtube'] }}" class="w-10 h-10 rounded-full bg-gray-800 hover:bg-yellow-500 hover:text-gray-900 flex items-center justify-center transition-all">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: FORM (Light Theme) --}}
                    <div class="p-10 sm:p-12 bg-white">
                        <h3 class="text-2xl font-heading font-bold text-gray-900 mb-2">Kirim Pesan</h3>
                        <p class="text-gray-500 mb-8">Punya pertanyaan spesifik? Kirimkan pesan kepada kami.</p>

                        <form onsubmit="event.preventDefault(); sendToWhatsapp();" class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Depan</label>
                                    <input type="text" id="firstName" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 transition-colors" placeholder="John">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Belakang</label>
                                    <input type="text" id="lastName" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 transition-colors" placeholder="Doe">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email Address</label>
                                <input type="email" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 transition-colors" placeholder="john@example.com">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Subjek</label>
                                <select id="subject" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 transition-colors">
                                    <option>Pertanyaan Umum</option>
                                    <option>Reservasi Kamar</option>
                                    <option>Event & MICE</option>
                                    <option>Restoran</option>
                                    <option>Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pesan</label>
                                <textarea id="message" rows="4" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 transition-colors" placeholder="Tulis pesan Anda di sini..."></textarea>
                            </div>

                            <button type="submit" class="w-full bg-gray-900 hover:bg-yellow-500 text-white hover:text-gray-900 font-bold py-3.5 rounded-lg transition-all duration-300 shadow-lg transform hover:-translate-y-1 flex items-center justify-center gap-2">
                                <span>Kirim Pesan via WhatsApp</span>
                                <i class="fab fa-whatsapp text-lg"></i>
                            </button>
                            <p class="text-xs text-center text-gray-400 mt-3">Kami akan membalas secepatnya melalui WhatsApp.</p>
                        </form>
                    </div>

                </div>
            </div>

            {{-- 3. MAP SECTION (REVISI: 3X LEBIH TINGGI) --}}
            {{-- Menggunakan h-[800px] (sebelumnya h-[500px]) atau bisa juga h-screen --}}
            <div class="rounded-3xl overflow-hidden shadow-lg border border-gray-200 h-[800px] relative bg-gray-100">
                @if(!empty($settings['contact_maps_embed']))
                    <div class="w-full h-full contact-map-iframe">
                        {!! $settings['contact_maps_embed'] !!}
                    </div>
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-500">
                        <div class="text-center">
                            <i class="fas fa-map-marked-alt text-6xl mb-4 text-gray-400"></i>
                            <p class="text-xl">Peta Lokasi Belum Dikonfigurasi</p>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        .contact-map-iframe iframe {
            width: 100% !important;
            height: 100% !important;
            border: 0;
            display: block;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function sendToWhatsapp() {
            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            
            const phone = "{{ preg_replace('/[^0-9]/', '', $settings['contact_phone'] ?? '6281234567890') }}";
            const text = `Halo Admin Bell Hotel,\n\nSaya *${firstName} ${lastName}* ingin bertanya mengenai *${subject}*.\n\nPesan:\n${message}`;
            
            window.open(`https://wa.me/${phone}?text=${encodeURIComponent(text)}`, '_blank');
        }
    </script>
    @endpush
@endsection