<div class="flex h-full flex-col bg-gray-900 text-gray-300 shadow-xl border-r border-gray-800">
    <div class="h-16 flex-shrink-0 flex items-center justify-center border-b border-gray-800 bg-gray-900">
        <a href="{{ route('admin.dashboard') }}" class="text-yellow-500 font-bold text-2xl tracking-wide flex items-center gap-2 hover:text-yellow-400 transition-colors">
            <span class="bg-yellow-500 text-gray-900 rounded-md px-2 py-0.5 text-lg shadow-sm">B</span> Bell Hotel
        </a>
    </div>

    <div class="flex-1 overflow-y-auto custom-scrollbar">
        <nav class="flex-1 space-y-1 py-4 px-3">
        
            {{-- ============================================================== --}}
            {{-- MENU UNTUK ADMIN DAN FRONT OFFICE --}}
            {{-- ============================================================== --}}
            @if(in_array(Auth::user()->role, ['admin', 'frontoffice']))
                
                <div class="mb-6">
                    <h6 class="px-3 mb-2 text-xs font-bold uppercase tracking-wider text-gray-500">General</h6>
                    <a href="{{ route('home') }}" target="_blank" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-800 hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                        Lihat Website
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-gray-900' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        Dashboard
                    </a>
                </div>

                <div class="mb-6">
                    <h6 class="px-3 mb-2 text-xs font-bold uppercase tracking-wider text-gray-500">Booking & Ops</h6>
                    
                    <a href="{{ route('admin.bookings.index', ['type' => 'room']) }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->input('type') == 'room' || (request()->routeIs('admin.bookings.*') && !request()->input('type')) ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->input('type') == 'room' || (request()->routeIs('admin.bookings.*') && !request()->input('type')) ? 'text-gray-900' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        Room Bookings
                    </a>

                    <a href="{{ route('admin.bookings.index', ['type' => 'mice']) }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->input('type') == 'mice' ? 'bg-yellow-500 text-gray-900' : 'hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->input('type') == 'mice' ? 'text-gray-800' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        MICE Bookings
                    </a>

                    {{-- Fitur Maintenance hanya untuk Admin --}}
                    @if(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.maintenance.index') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.maintenance.*') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.maintenance.*') ? 'text-gray-900' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Maintenance Mode
                    </a>
                    @endif
                </div>

                {{-- Menu Affiliate: Admin & Front Office bisa lihat --}}
                <div class="mb-6">
                    <h6 class="px-3 mb-2 text-xs font-bold uppercase tracking-wider text-gray-500">Affiliate</h6>
                    
                    @if(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.affiliates.index') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.affiliates.*') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                        <span class="w-5 h-5 mr-3 flex items-center justify-center text-lg {{ request()->routeIs('admin.affiliates.*') ? 'text-gray-900' : 'text-gray-400 group-hover:text-white' }}">👥</span>
                        Affiliates
                    </a>
                    @endif

                    <a href="{{ route('admin.commissions.index') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.commissions.*') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                        <span class="w-5 h-5 mr-3 flex items-center justify-center text-lg {{ request()->routeIs('admin.commissions.*') ? 'text-gray-900' : 'text-gray-400 group-hover:text-white' }}">💰</span>
                        Commissions
                    </a>

                    @if(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.affiliate_page.index') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.affiliate_page.*') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                        <span class="w-5 h-5 mr-3 flex items-center justify-center text-lg {{ request()->routeIs('admin.affiliate_page.*') ? 'text-gray-900' : 'text-gray-400 group-hover:text-white' }}">📄</span>
                        Halaman Affiliate
                    </a>
                    
                    {{-- MENU BARU: REVIEW --}}
                    <a href="{{ route('admin.reviews.index') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.reviews.*') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                        <span class="w-5 h-5 mr-3 flex items-center justify-center text-lg {{ request()->routeIs('admin.reviews.*') ? 'text-gray-900' : 'text-gray-400 group-hover:text-white' }}">⭐</span>
                        Moderasi Review
                    </a>
                    @endif
                </div>

                {{-- ============================================================== --}}
                {{-- MENU KHUSUS ADMIN (Full Access) --}}
                {{-- ============================================================== --}}
                @if(Auth::user()->role == 'admin')
                <div class="mb-6">
                    <h6 class="px-3 mb-2 text-xs font-bold uppercase tracking-wider text-gray-500">Content & Settings</h6>
                    <a href="{{ route('admin.hero-sliders.index') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.hero-sliders.*') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">Hero Sliders</a>
                    <a href="{{ route('admin.banners.index') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.banners.*') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">Banners</a>
                    <a href="{{ route('admin.price-overrides.index') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.price-overrides.*') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">Custom Prices</a>
                    <a href="{{ route('admin.mice-kits.index') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.mice-kits.*') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">Digital MICE Kit</a>
                    <a href="{{ route('admin.rooms.index') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.rooms.*') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">Rooms</a>
                    <a href="{{ route('admin.mice.index') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.mice.*') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">MICE Rooms</a>
                    <a href="{{ route('admin.restaurants.index') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.restaurants.*') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">Restaurants</a>
                    
                    <div class="mt-4">
                        <a href="{{ route('admin.settings.index') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.settings.*') ? 'text-gray-900' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Website Settings
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.users.*') ? 'text-gray-900' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            User Management
                        </a>
                    </div>
                </div>
                @endif

            {{-- ============================================================== --}}
            {{-- MENU KHUSUS ACCOUNTING --}}
            {{-- ============================================================== --}}
            @elseif(Auth::user()->role == 'accounting')
                <div class="mb-6">
                    <h6 class="px-3 mb-2 text-xs font-bold uppercase tracking-wider text-gray-500">Finance</h6>
                    <a href="{{ route('admin.commissions.index') }}" class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.commissions.*') ? 'bg-yellow-500 text-gray-900 shadow-md' : 'hover:bg-gray-800 hover:text-white' }}">
                        <span class="w-5 h-5 mr-3 flex items-center justify-center text-lg">💰</span>
                        Commissions
                    </a>
                </div>
            @endif
        </nav>
    </div>
    
    <div class="border-t border-gray-800 bg-gray-900 p-4 flex-shrink-0">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-10 w-10 rounded-full bg-yellow-500 flex items-center justify-center text-gray-900 font-bold text-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
            <div class="ml-3 overflow-hidden">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs font-medium text-gray-500 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
        
        <div class="mt-3 grid grid-cols-2 gap-2">
            <a href="{{ route('profile.edit') }}" class="text-center rounded-md py-1.5 text-xs font-medium bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center rounded-md py-1.5 text-xs font-medium bg-red-900/50 text-red-200 hover:bg-red-900 hover:text-white transition-colors">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>