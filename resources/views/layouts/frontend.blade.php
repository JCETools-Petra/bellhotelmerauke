<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(isset($settings['favicon_path']))
        <link rel="icon" href="{{ asset('storage/' . $settings['favicon_path']) }}" type="image/x-icon">
    @endif
    
    <title>@yield('seo_title', $settings['website_title'] ?? 'Bell Hotel Merauke')</title>
    <meta name="description" content="@yield('meta_description', 'Bell Hotel Merauke adalah hotel modern yang berlokasi strategis di pusat Kota Merauke.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .font-heading { family: 'Playfair Display', serif; }
        .font-body { family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="font-body text-gray-700 antialiased bg-gray-50 flex flex-col min-h-screen">

    <nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="{ 'bg-gray-900/95 backdrop-blur-md shadow-lg': scrolled, 'bg-transparent': !scrolled && '{{ request()->routeIs('home') }}', 'bg-gray-900': !'{{ request()->routeIs('home') }}' }"
         class="fixed w-full z-50 transition-all duration-300 top-0 start-0 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                {{-- Tambahkan class 'relative' dan 'z-10' pada container utama --}}
                <div class="flex-shrink-0 relative z-10">
                    
                    {{-- 
                       EFEK BERCAK BACKGROUND KUNING
                       - absolute -inset-6: Membuat elemen ini melayang dan ukurannya lebih besar dari container induknya.
                       - bg-yellow-400/40: Warna kuning dengan transparansi 40% agar tidak terlalu pekat.
                       - blur-2xl: Kunci efeknya! Membuat kotak kuning menjadi blur (buram) seperti bercak cahaya.
                       - rounded-full: Membuat bentuk dasarnya menjadi oval/bulat.
                       - -z-10: Memastikan efek ini berada DI BELAKANG logo.
                    --}}
                    <div class="absolute -inset-6 bg-yellow-400/40 blur-2xl rounded-full -z-10 pointer-events-none"></div>

                    {{-- Link Logo Asli (Pastikan ini tetap di depan dengan z-index lebih tinggi jika perlu, tapi 'relative z-10' di parent sudah cukup) --}}
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group relative">
                        @if(isset($settings['logo_path']) && $settings['logo_path'])
                            {{-- Saya sedikit perbesar ukuran logo agar lebih pas dengan efeknya (h-10 jadi h-12) --}}
                            <img src="{{ asset('storage/' . $settings['logo_path']) }}" class="h-12 w-auto object-contain drop-shadow-sm" alt="Logo">
                        @else
                            <span class="text-2xl font-heading font-bold text-yellow-500 tracking-wider group-hover:text-white transition-colors drop-shadow-sm">
                                BELL HOTEL
                            </span>
                        @endif
                    </a>
                </div>

                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="{{ route('home') }}" class="text-sm font-medium hover:text-yellow-500 transition-colors {{ request()->routeIs('home') ? 'text-yellow-500' : 'text-gray-300' }}">Home</a>
                        <a href="{{ route('rooms.index') }}" class="text-sm font-medium hover:text-yellow-500 transition-colors {{ request()->routeIs('rooms.*') ? 'text-yellow-500' : 'text-gray-300' }}">Rooms</a>
                        <a href="{{ route('mice.index') }}" class="text-sm font-medium hover:text-yellow-500 transition-colors {{ request()->routeIs('mice.*') ? 'text-yellow-500' : 'text-gray-300' }}">MICE</a>
                        <a href="{{ route('restaurants.index') }}" class="text-sm font-medium hover:text-yellow-500 transition-colors {{ request()->routeIs('restaurants.*') ? 'text-yellow-500' : 'text-gray-300' }}">Dining</a>
                        <a href="{{ route('contact.index') }}" class="text-sm font-medium hover:text-yellow-500 transition-colors {{ request()->routeIs('contact.index') ? 'text-yellow-500' : 'text-gray-300' }}">Contact</a>
                    </div>
                </div>

                <div class="hidden md:block">
                    <div class="flex items-center gap-4">
                        @auth
                            @if(Auth::user()->affiliate && Auth::user()->affiliate->status == 'active')
                                <a href="{{ route('affiliate.dashboard') }}" class="text-sm font-medium text-white hover:text-yellow-400">Dashboard</a>
                            @else
                                <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-white hover:text-yellow-400">Dashboard</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-white hover:text-yellow-400">Affiliate Log In</a>
                            <a href="{{ route('rooms.index') }}" class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 px-5 py-2 rounded-full text-sm font-bold transition-transform hover:scale-105">
                                Book Now
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="-mr-2 flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg class="h-6 w-6" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-gray-900 border-t border-gray-800">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-gray-800 {{ request()->routeIs('home') ? 'bg-gray-800 text-yellow-500' : '' }}">Home</a>
                <a href="{{ route('rooms.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Rooms</a>
                <a href="{{ route('mice.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">MICE</a>
                <a href="{{ route('restaurants.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Dining</a>
                <a href="{{ route('contact.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Contact</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-yellow-500 hover:bg-gray-700">My Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">Login</a>

                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-white pt-16 pb-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <div>
                    <h3 class="text-2xl font-heading font-bold text-yellow-500 mb-4">BELL HOTEL</h3>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">
                        Hotel terbaik di Merauke dengan fasilitas lengkap untuk bisnis dan liburan keluarga Anda.
                    </p>
                    <div class="flex space-x-4">
                        @if(!empty($settings['contact_facebook']))
                            <a href="{{ $settings['contact_facebook'] }}" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if(!empty($settings['contact_instagram']))
                            <a href="{{ $settings['contact_instagram'] }}" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if(!empty($settings['contact_phone']))
                            <a href="https://wa.me/{{ $settings['contact_phone'] }}" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-whatsapp"></i></a>
                        @endif
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-6">Quick Links</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-yellow-500 transition-colors">Home</a></li>
                        <li><a href="{{ route('rooms.index') }}" class="hover:text-yellow-500 transition-colors">Accommodation</a></li>
                        <li><a href="{{ route('mice.index') }}" class="hover:text-yellow-500 transition-colors">Meeting & Events</a></li>
                        <li><a href="{{ route('restaurants.index') }}" class="hover:text-yellow-500 transition-colors">Dining</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-6">Support</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="{{ route('contact.index') }}" class="hover:text-yellow-500 transition-colors">Contact Us</a></li>
                        <li><a href="{{ route('pages.terms') }}" class="hover:text-yellow-500 transition-colors">Terms & Conditions</a></li>
                        <li><a href="{{ route('pages.affiliate_info') }}" class="hover:text-yellow-500 transition-colors">Affiliate Program</a></li>
                        <li><a href="{{ route('affiliate.register.create') }}" class="hover:text-yellow-500 transition-colors">Register Partner</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-6">Contact Info</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt mt-1 text-yellow-500"></i>
                            <span>{{ $settings['contact_address'] ?? 'Jl. Raya Mandala No. 123, Merauke, Papua Selatan' }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-phone-alt text-yellow-500"></i>
                            <span>{{ $settings['contact_phone'] ?? '+62 812 3456 7890' }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-envelope text-yellow-500"></i>
                            <span>{{ $settings['contact_email'] ?? 'info@bellhotelmerauke.com' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} {{ $settings['website_title'] ?? 'Bell Hotel' }}. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    {{-- Memastikan Alpine.js dimuat untuk navbar mobile --}}
    <script src="//unpkg.com/alpinejs" defer></script> 
    
    @stack('scripts')

    {{-- Script Kalender (Flatpickr) --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const fpConfig = {
            dateFormat: "d-m-Y",
            minDate: "today"
        };
        flatpickr(".datepicker", fpConfig);
        
        // Input type="date" fallback to flatpickr if needed
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(function(input) {
            // Optional: Jika ingin memaksa tampilan konsisten di semua browser
            // flatpickr(input, fpConfig); 
        });
    });
    </script>
</body>
</html>