{{-- Kode ini akan ditempatkan di dalam layout utama, bukan sebagai halaman penuh --}}
<div class="flex h-full flex-col bg-brand-black text-gray-300">
    <div class="h-16 flex-shrink-0 flex items-center justify-center border-b border-gray-700">
        <a href="{{ route('admin.dashboard') }}" class="text-brand-gold font-bold text-2xl">
            Bell Hotel
        </a>
    </div>

    {{-- Wrapper untuk Navigasi dengan Scroll Internal --}}
    <div class="flex-1 overflow-y-auto">
        <nav class="flex-1 space-y-1 py-4 px-2">
        
            @if(Auth::user()->role == 'admin')
                <h6 class="px-4 pt-2 pb-1 text-xs font-semibold uppercase text-gray-400">General</h6>
                <a href="{{ route('home') }}"class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors hover:bg-gray-700 hover:text-white" target="_blank">Lihat Website</a>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">Dashboard</a>
                <a href="{{ route('admin.maintenance.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.maintenance.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">Maintenance Mode</a>
                <a href="{{ route('admin.affiliate_page.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.affiliate_page.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">Halaman Affiliate</a>
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.bookings.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">Bookings</a>
                <a href="{{ route('admin.mice-inquiries.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.mice-inquiries.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">MICE Inquiries</a>

                <hr class="border-gray-700 my-2">
                <h6 class="px-4 pt-2 pb-1 text-xs font-semibold uppercase text-gray-400">Affiliate Program</h6>
                <a href="{{ route('admin.affiliates.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.affiliates.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">Affiliates</a>
                <a href="{{ route('admin.commissions.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.commissions.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">Commissions</a>

                <hr class="border-gray-700 my-2">
                <h6 class="px-4 pt-2 pb-1 text-xs font-semibold uppercase text-gray-400">Content Management</h6>
                
                {{-- ======================= AWAL TAMBAHAN ======================= --}}
                <a href="{{ route('admin.hero-sliders.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.hero-sliders.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">Hero Sliders</a>
                {{-- ======================== AKHIR TAMBAHAN ======================= --}}
                
                <a href="{{ route('admin.banners.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.banners.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">Banners</a>
                {{-- PENAMBAHAN LINK CUSTOM PRICES --}}
                <a href="{{ route('admin.price-overrides.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.price-overrides.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">Custom Prices</a>
                <a href="{{ route('admin.mice-kits.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.mice-kits.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">Digital MICE Kit</a>
                <a href="{{ route('admin.rooms.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.rooms.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">Rooms</a>
                <a href="{{ route('admin.mice.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.mice.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">MICE</a>
                <a href="{{ route('admin.restaurants.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.restaurants.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">Restaurants</a>

                <hr class="border-gray-700 my-2">
                <h6 class="px-4 pt-2 pb-1 text-xs font-semibold uppercase text-gray-400">Settings</h6>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">Website Settings</a>
                
                <hr class="border-gray-700 my-2">
                <h6 class="px-4 pt-2 pb-1 text-xs font-semibold uppercase text-gray-400">User Management</h6>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">Users</a>

            @elseif(Auth::user()->role == 'accounting')
                <h6 class="px-4 pt-2 pb-1 text-xs font-semibold uppercase text-gray-400">Affiliate Program</h6>
                <a href="{{ route('admin.commissions.index') }}" class="flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.commissions.*') ? 'bg-brand-gold text-brand-black' : 'hover:bg-gray-700 hover:text-white' }}">
                    Commissions
                </a>
            @endif
        </nav>
    </div>
    
    {{-- User Info Section --}}
    <div class="border-t border-gray-700 p-4 flex-shrink-0">
        <div class="font-semibold text-white">{{ Auth::user()->name }}</div>
        <div class="text-xs text-gray-400">{{ Auth::user()->email }}</div>
        
        <a href="{{ route('profile.edit') }}" class="w-full text-left flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors hover:bg-gray-700 hover:text-white mt-3">
            Edit Profile
        </a>

        <form method="POST" action="{{ route('logout') }}" class="mt-1">
            @csrf
            <button type="submit" class="w-full text-left flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors hover:bg-red-600 hover:text-white">
                Log Out
            </button>
        </form>
    </div>
</div>