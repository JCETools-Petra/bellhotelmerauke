<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Favicon --}}
        @if(isset($settings['favicon_path']))
            <link rel="icon" href="{{ asset('storage/' . $settings['favicon_path']) }}" type="image/x-icon">
        @endif

        {{-- Website Title --}}
        <title>{{ $settings['website_title'] ?? config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="relative min-h-screen md:flex">
            
            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/50 transition-opacity md:hidden"></div>

            <aside
                :class="{ '-translate-x-full': !sidebarOpen }"
                class="fixed inset-y-0 left-0 z-30 w-64 transform transition-transform duration-300 md:relative md:translate-x-0">
                @include('layouts.sidebar')
            </aside>

            <div class="flex-1">
                <header class="flex items-center justify-between border-b bg-white p-4 md:hidden">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                        @if(isset($settings['logo_path']))
                            <img src="{{ asset('storage/' . $settings['logo_path']) }}" alt="Logo" class="h-8">
                        @else
                            <span class="text-brand-gold font-bold text-xl">
                                Admin Panel
                            </span>
                        @endif
                    </a>
                    <button @click="sidebarOpen = !sidebarOpen" class="rounded-md p-2 text-gray-500 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                </header>

                @if (isset($header))
                    <div class="hidden border-b bg-white px-4 py-6 sm:px-6 lg:px-8 md:block">
                       {{ $header }}
                    </div>
                @endif

                <main class="bg-gray-100 p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
        @stack('scripts')
    </body>
</html>