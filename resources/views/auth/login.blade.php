@extends('layouts.frontend')

@section('seo_title', 'Login - Bell Hotel Merauke')
@section('meta_description', 'Masuk ke akun Anda untuk mengelola pemesanan dan akses fitur member Bell Hotel Merauke.')

@section('content')
    <div class="min-h-screen bg-gray-900 flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        
        {{-- Background Elements --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-yellow-500 rounded-full opacity-10 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-blue-600 rounded-full opacity-10 blur-3xl"></div>
            <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=1920&auto=format&fit=crop" 
                 class="absolute inset-0 w-full h-full object-cover opacity-10 mix-blend-overlay" alt="Background">
        </div>

        <div class="relative z-10 w-full max-w-md">
            
            {{-- Logo & Header --}}
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block mb-4">
                    {{-- Ganti dengan logo Anda jika ada, atau gunakan teks --}}
                    @if(isset($settings['logo_path']) && $settings['logo_path'])
                        <img src="{{ asset('storage/' . $settings['logo_path']) }}" class="h-16 w-auto mx-auto drop-shadow-lg" alt="Bell Hotel">
                    @else
                        <span class="text-3xl font-heading font-bold text-yellow-500 tracking-wider">BELL HOTEL</span>
                    @endif
                </a>
                <h2 class="text-2xl font-bold text-white tracking-tight">
                    Selamat Datang Kembali
                </h2>
                <p class="mt-2 text-sm text-gray-400">
                    Silakan masuk ke akun Anda
                </p>
            </div>

            {{-- Login Card --}}
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-800 p-8 sm:p-10">
                
                {{-- Session Status --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Email Address --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-500 uppercase mb-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-yellow-500 focus:border-yellow-500 transition-colors placeholder-gray-400"
                                   placeholder="nama@email.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-500 uppercase mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:ring-yellow-500 focus:border-yellow-500 transition-colors placeholder-gray-400"
                                   placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="flex items-center justify-between text-sm">
                        <label for="remember_me" class="flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember" 
                                   class="rounded border-gray-300 text-yellow-500 focus:ring-yellow-500 cursor-pointer">
                            <span class="ml-2 text-gray-600">Ingat Saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="font-medium text-yellow-600 hover:text-yellow-500 transition-colors">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-gray-900 bg-yellow-500 hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all transform hover:-translate-y-0.5">
                        Masuk Sekarang
                    </button>

                    {{-- Register Link --}}
                    <div class="text-center mt-6 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-500">
                            Belum punya akun? 
                            <a href="{{ route('register') }}" class="font-bold text-gray-900 hover:text-yellow-600 transition-colors">
                                Daftar Gratis
                            </a>
                        </p>
                        <p class="text-xs text-gray-400 mt-2">
                            Atau daftar sebagai <a href="{{ route('affiliate.register.create') }}" class="text-yellow-600 hover:underline">Partner Affiliate</a>
                        </p>
                    </div>
                </form>
            </div>
            
            <div class="text-center mt-8 text-xs text-gray-500">
                &copy; {{ date('Y') }} {{ $settings['website_title'] ?? 'Bell Hotel' }}. All rights reserved.
            </div>

        </div>
    </div>
@endsection