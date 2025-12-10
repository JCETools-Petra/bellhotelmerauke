<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="space-y-6">

        {{-- Welcome Section --}}
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
            <div class="p-6 text-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-2 text-gray-800">Selamat Datang, {{ Auth::user()->name }}! 👋</h3>
                        <p class="text-gray-600">
                            Kelola konten website Bell Hotel Merauke dan pantau performa bisnis Anda dari sini.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Website Stats --}}
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4 border-b pb-2">
                    <h4 class="text-lg font-bold text-gray-800">Statistik Kunjungan Website</h4>
                    <span class="text-xs font-medium px-2.5 py-0.5 rounded bg-blue-100 text-blue-800">Live Data</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-100 hover:shadow-md transition-shadow">
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Total Kunjungan</span>
                            <span class="text-2xl font-extrabold text-gray-800 mt-1">{{ number_format($totalWebsiteVisits) }}</span>
                        </div>
                    </div>
                    <div class="p-4 bg-green-50 rounded-xl border border-green-100 hover:shadow-md transition-shadow">
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-green-600 uppercase tracking-wider">Hari Ini</span>
                            <span class="text-2xl font-extrabold text-gray-800 mt-1">{{ number_format($websiteVisitsToday) }}</span>
                        </div>
                    </div>
                    <div class="p-4 bg-purple-50 rounded-xl border border-purple-100 hover:shadow-md transition-shadow">
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Minggu Ini</span>
                            <span class="text-2xl font-extrabold text-gray-800 mt-1">{{ number_format($websiteVisitsThisWeek) }}</span>
                        </div>
                    </div>
                    <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100 hover:shadow-md transition-shadow">
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Bulan Ini</span>
                            <span class="text-2xl font-extrabold text-gray-800 mt-1">{{ number_format($websiteVisitsThisMonth) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Affiliate Stats Summary --}}
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
            <div class="p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Statistik Affiliate</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-4 bg-orange-50 rounded-xl border border-orange-100">
                        <h3 class="text-xs font-semibold text-orange-700 uppercase">Total Klik</h3>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ number_format($totalAffiliateClicks) }}</p>
                    </div>
                    <div class="p-4 bg-red-50 rounded-xl border border-red-100">
                        <h3 class="text-xs font-semibold text-red-700 uppercase">Klik Hari Ini</h3>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ number_format($affiliateClicksToday) }}</p>
                    </div>
                    <div class="p-4 bg-pink-50 rounded-xl border border-pink-100">
                        <h3 class="text-xs font-semibold text-pink-700 uppercase">Klik Minggu Ini</h3>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ number_format($affiliateClicksThisWeek) }}</p>
                    </div>
                    <div class="p-4 bg-rose-50 rounded-xl border border-rose-100">
                        <h3 class="text-xs font-semibold text-rose-700 uppercase">Klik Bulan Ini</h3>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ number_format($affiliateClicksThisMonth) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            
            {{-- Left Column: Booking Stats & Trends --}}
            <div class="space-y-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
                    <div class="p-6">
                        <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            Statistik Booking
                        </h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-teal-50 rounded-lg">
                                <span class="text-sm font-medium text-teal-800">Total Booking</span>
                                <span class="text-xl font-bold text-teal-600">{{ number_format($totalBookings) }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-3 bg-cyan-50 rounded-lg text-center">
                                    <span class="block text-xs text-cyan-600 mb-1">Hari Ini</span>
                                    <span class="block text-lg font-bold text-gray-800">{{ number_format($bookingsToday) }}</span>
                                </div>
                                <div class="p-3 bg-sky-50 rounded-lg text-center">
                                    <span class="block text-xs text-sky-600 mb-1">Bulan Ini</span>
                                    <span class="block text-lg font-bold text-gray-800">{{ number_format($bookingsThisMonth) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
                    <div class="p-6">
                        <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Ringkasan Sistem
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                                <h3 class="text-xs font-semibold text-emerald-700 mb-1">Affiliate Aktif</h3>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format($activeAffiliates) }}</p>
                            </div>
                            <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                                <h3 class="text-xs font-semibold text-yellow-700 mb-1">Affiliate Pending</h3>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format($pendingAffiliates) }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h3 class="text-xs font-semibold text-gray-600 mb-1">Total Kamar</h3>
                                <p class="text-2xl font-bold text-gray-800">{{ $roomCount }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h3 class="text-xs font-semibold text-gray-600 mb-1">Ruang MICE</h3>
                                <p class="text-2xl font-bold text-gray-800">{{ $miceCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Top Affiliates --}}
            <div class="space-y-6">
                
                {{-- Top 5 Clicks --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
                    <div class="p-6">
                        <h4 class="text-lg font-bold text-gray-800 mb-4">Top 5 Affiliate (Klik)</h4>
                        <div class="space-y-4">
                            @forelse($topAffiliatesByClicks as $index => $affiliate)
                                {{-- LOGIKA PRIVASI --}}
                                @php
                                    $displayName = $affiliate->user->name;
                                    $displayEmail = $affiliate->user->email;
                                    $isFrontOffice = Auth::user()->role === 'frontoffice';

                                    if ($isFrontOffice) {
                                        // Singkat Nama
                                        $parts = explode(' ', $displayName);
                                        $displayName = $parts[0];
                                        for($i = 1; $i < count($parts); $i++) {
                                            if(!empty($parts[$i])) $displayName .= ' ' . strtoupper(substr($parts[$i], 0, 1)) . '.';
                                        }
                                        // Sembunyikan Email
                                        $displayEmail = 'Privasi Dilindungi';
                                    }
                                @endphp

                                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors border-b border-gray-100 last:border-0">
                                    <div class="flex items-center space-x-3">
                                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-orange-100 text-orange-600 font-bold text-sm">
                                            {{ $index + 1 }}
                                        </span>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $displayName }}
                                                @if($isFrontOffice) <span class="ml-1 text-xs text-orange-500" title="Nama disamarkan">🔒</span> @endif
                                            </p>
                                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                                @if($isFrontOffice) 
                                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                @endif
                                                {{ $displayEmail }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-lg font-bold text-gray-800">{{ number_format($affiliate->total_clicks) }}</span>
                                        <span class="text-xs text-gray-500">Klik</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500">Belum ada data.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Top 5 Bookings --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
                    <div class="p-6">
                        <h4 class="text-lg font-bold text-gray-800 mb-4">Top 5 Affiliate (Booking)</h4>
                        <div class="space-y-4">
                            @forelse($topAffiliatesByBookings as $index => $affiliate)
                                {{-- LOGIKA PRIVASI --}}
                                @php
                                    $displayName = $affiliate->user->name;
                                    $isFrontOffice = Auth::user()->role === 'frontoffice';

                                    if ($isFrontOffice) {
                                        $parts = explode(' ', $displayName);
                                        $displayName = $parts[0];
                                        for($i = 1; $i < count($parts); $i++) {
                                            if(!empty($parts[$i])) $displayName .= ' ' . strtoupper(substr($parts[$i], 0, 1)) . '.';
                                        }
                                    }
                                @endphp

                                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors border-b border-gray-100 last:border-0">
                                    <div class="flex items-center space-x-3">
                                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-teal-100 text-teal-600 font-bold text-sm">
                                            {{ $index + 1 }}
                                        </span>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $displayName }}
                                                @if($isFrontOffice) <span class="ml-1 text-xs text-orange-500" title="Nama disamarkan">🔒</span> @endif
                                            </p>
                                            <p class="text-xs text-teal-600 font-medium">+ Rp {{ number_format($affiliate->total_commission ?? 0) }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-lg font-bold text-gray-800">{{ number_format($affiliate->total_bookings) }}</span>
                                        <span class="text-xs text-gray-500">Booking</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500">Belum ada data.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Graph --}}
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
            <div class="p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4">Tren 7 Hari Terakhir</h4>
                <div class="overflow-x-auto rounded-lg border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kunjungan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Klik Affiliate</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($last7Days as $day)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $day['date'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                        {{ number_format($day['website_visits']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-orange-800 bg-orange-100 rounded-full">
                                        {{ number_format($day['affiliate_clicks']) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>