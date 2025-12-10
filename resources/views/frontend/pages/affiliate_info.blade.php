@extends('layouts.frontend')

@section('seo_title', 'Apa itu Affiliate? - Bell Hotel Merauke')
@section('meta_description', 'Pelajari cara kerja program affiliate Bell Hotel Merauke. Dapatkan komisi dengan merekomendasikan hotel kami.')

@section('content')
    {{-- 1. HERO SECTION --}}
    <div class="relative bg-gray-900 pt-32 pb-20 sm:pt-40 sm:pb-24 overflow-hidden">
        {{-- Background --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/90 to-gray-900/50 z-10"></div>
            <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?q=80&w=1920&auto=format&fit=crop" 
                 alt="Affiliate Partnership" 
                 class="w-full h-full object-cover opacity-30 animate-slow-zoom">
        </div>
        
        <div class="container mx-auto px-4 relative z-20 text-center">
            <span class="text-yellow-500 font-bold uppercase tracking-[0.2em] text-sm mb-4 block animate-fade-in-up">Partnership Program</span>
            <h1 class="text-4xl md:text-6xl font-heading font-bold text-white mb-6 leading-tight animate-fade-in-up delay-100">
                Grow With <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-600">Bell Hotel</span>
            </h1>
            <p class="text-gray-300 text-lg md:text-xl max-w-3xl mx-auto font-light leading-relaxed animate-fade-in-up delay-200">
                Ubah rekomendasi Anda menjadi pendapatan nyata. Program kemitraan eksklusif untuk Anda yang ingin berkembang bersama hotel terbaik di Merauke.
            </p>
            
            <div class="mt-10 animate-fade-in-up delay-300 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('affiliate.register.create') }}" class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold py-3.5 px-8 rounded-full transition-all transform hover:-translate-y-1 shadow-lg shadow-yellow-500/20">
                    Gabung Sekarang
                </a>
                <a href="#how-it-works" class="bg-transparent border-2 border-gray-700 text-white hover:bg-gray-800 hover:border-gray-600 font-bold py-3.5 px-8 rounded-full transition-all">
                    Pelajari Cara Kerja
                </a>
            </div>
        </div>
    </div>

    {{-- 2. INTRO & EXPLANATION --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2 relative">
                    <div class="absolute -top-6 -left-6 w-24 h-24 bg-yellow-100 rounded-full z-0"></div>
                    <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?q=80&w=1000" 
                         class="relative z-10 rounded-3xl shadow-2xl w-full object-cover transform hover:scale-[1.02] transition-transform duration-500" 
                         alt="Business Partnership">
                </div>
                <div class="lg:w-1/2">
                    <h3 class="text-3xl font-heading font-bold text-gray-900 mb-6">Apa itu Program Affiliate?</h3>
                    <p class="text-gray-600 text-lg leading-relaxed mb-6 text-justify">
                        Program Affiliate Bell Hotel Merauke adalah sebuah peluang kemitraan di mana Anda bisa mendapatkan penghasilan tambahan (komisi) hanya dengan mempromosikan layanan kami kepada orang lain.
                    </p>
                    <p class="text-gray-600 text-lg leading-relaxed mb-8 text-justify">
                        Setiap kali ada tamu yang melakukan pemesanan kamar atau paket MICE melalui referensi Anda, kami akan memberikan apresiasi berupa komisi uang tunai yang transparan dan menguntungkan.
                    </p>
                    
                    <div class="bg-gray-50 border-l-4 border-yellow-500 p-6 rounded-r-xl">
                        <p class="text-gray-800 font-medium italic">
                            "Tidak perlu modal, tidak perlu stok barang. Cukup rekomendasikan kenyamanan Bell Hotel, dan nikmati hasilnya."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. HOW IT WORKS (STEPS) --}}
    <section id="how-it-works" class="py-20 bg-gray-50 relative overflow-hidden">
        {{-- Decor --}}
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-yellow-600 font-bold uppercase tracking-wider text-xs">Simple Steps</span>
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 mt-2">Cara Kerja Program</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                {{-- Connector Line (Desktop) --}}
                <div class="hidden md:block absolute top-12 left-[16%] right-[16%] h-0.5 bg-gray-200 -z-10"></div>

                {{-- Step 1 --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-shadow text-center group border border-gray-100 relative">
                    <div class="w-20 h-20 mx-auto bg-gray-900 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mb-6 shadow-lg shadow-gray-900/20 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">1. Daftar Gratis</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Lakukan pendaftaran akun affiliate secara gratis dan cepat melalui website kami.
                    </p>
                </div>

                {{-- Step 2 --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-shadow text-center group border border-gray-100 relative">
                    <div class="w-20 h-20 mx-auto bg-yellow-500 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mb-6 shadow-lg shadow-yellow-500/30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">2. Promosikan</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Bagikan kode referral atau link unik Anda ke teman, keluarga, atau media sosial.
                    </p>
                </div>

                {{-- Step 3 --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-shadow text-center group border border-gray-100 relative">
                    <div class="w-20 h-20 mx-auto bg-green-600 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mb-6 shadow-lg shadow-green-600/20 group-hover:scale-110 transition-transform">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">3. Terima Komisi</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Dapatkan komisi dari setiap tamu yang menginap atau mengadakan acara melalui referensi Anda.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. BENEFITS --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900">Kenapa Bergabung?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 hover:bg-white hover:shadow-lg transition-all">
                    <i class="fas fa-money-bill-wave text-3xl text-yellow-500 mb-4"></i>
                    <h4 class="font-bold text-gray-900 mb-2">Komisi Kompetitif</h4>
                    <p class="text-gray-500 text-sm">Rate komisi yang menarik untuk setiap transaksi sukses.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 hover:bg-white hover:shadow-lg transition-all">
                    <i class="fas fa-chart-pie text-3xl text-blue-500 mb-4"></i>
                    <h4 class="font-bold text-gray-900 mb-2">Dashboard Transparan</h4>
                    <p class="text-gray-500 text-sm">Pantau performa dan pendapatan Anda secara real-time.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 hover:bg-white hover:shadow-lg transition-all">
                    <i class="fas fa-tools text-3xl text-purple-500 mb-4"></i>
                    <h4 class="font-bold text-gray-900 mb-2">Materi Promosi</h4>
                    <p class="text-gray-500 text-sm">Akses ke Digital MICE Kit dan materi pemasaran berkualitas.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 hover:bg-white hover:shadow-lg transition-all">
                    <i class="fas fa-clock text-3xl text-green-500 mb-4"></i>
                    <h4 class="font-bold text-gray-900 mb-2">Waktu Fleksibel</h4>
                    <p class="text-gray-500 text-sm">Kerjakan kapan saja dan di mana saja sesuai keinginan Anda.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. CTA FINAL --}}
    <section class="py-20 bg-yellow-500 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-black opacity-10 rounded-full -ml-20 -mb-20"></div>

        <div class="container mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 mb-6">Siap Untuk Mulai?</h2>
            <p class="text-gray-800 text-lg max-w-2xl mx-auto mb-10 font-medium">
                Jangan lewatkan kesempatan ini. Bergabunglah dengan komunitas partner Bell Hotel Merauke sekarang juga.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('affiliate.register.create') }}" class="bg-gray-900 hover:bg-gray-800 text-white font-bold py-4 px-10 rounded-full shadow-xl transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                    Daftar Sekarang <i class="fas fa-arrow-right"></i>
                </a>
                <a href="{{ route('contact.index') }}" class="bg-white hover:bg-gray-50 text-gray-900 font-bold py-4 px-10 rounded-full shadow-md transition-all flex items-center justify-center gap-2">
                    Hubungi Support
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
@endsection