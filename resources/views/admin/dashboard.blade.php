<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-4">Selamat Datang di Admin Panel!</h3>
                    <p class="text-gray-600">
                        Dari sini, Anda dapat mengelola semua konten website Bell Hotel Merauke dan memantau statistik pengunjung serta performa affiliate.
                    </p>
                </div>
            </div>

            <!-- Website Visit Statistics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Statistik Kunjungan Website</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h3 class="text-sm font-semibold text-blue-700">Total Kunjungan</h3>
                            <p class="text-3xl font-extrabold text-blue-600 mt-2">{{ number_format($totalWebsiteVisits) }}</p>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                            <h3 class="text-sm font-semibold text-green-700">Kunjungan Hari Ini</h3>
                            <p class="text-3xl font-extrabold text-green-600 mt-2">{{ number_format($websiteVisitsToday) }}</p>
                        </div>
                        <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                            <h3 class="text-sm font-semibold text-purple-700">Kunjungan Minggu Ini</h3>
                            <p class="text-3xl font-extrabold text-purple-600 mt-2">{{ number_format($websiteVisitsThisWeek) }}</p>
                        </div>
                        <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                            <h3 class="text-sm font-semibold text-indigo-700">Kunjungan Bulan Ini</h3>
                            <p class="text-3xl font-extrabold text-indigo-600 mt-2">{{ number_format($websiteVisitsThisMonth) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Affiliate Link Click Statistics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Statistik Klik Link Affiliate</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                            <h3 class="text-sm font-semibold text-orange-700">Total Klik Affiliate</h3>
                            <p class="text-3xl font-extrabold text-orange-600 mt-2">{{ number_format($totalAffiliateClicks) }}</p>
                        </div>
                        <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                            <h3 class="text-sm font-semibold text-red-700">Klik Hari Ini</h3>
                            <p class="text-3xl font-extrabold text-red-600 mt-2">{{ number_format($affiliateClicksToday) }}</p>
                        </div>
                        <div class="p-4 bg-pink-50 rounded-lg border border-pink-200">
                            <h3 class="text-sm font-semibold text-pink-700">Klik Minggu Ini</h3>
                            <p class="text-3xl font-extrabold text-pink-600 mt-2">{{ number_format($affiliateClicksThisWeek) }}</p>
                        </div>
                        <div class="p-4 bg-rose-50 rounded-lg border border-rose-200">
                            <h3 class="text-sm font-semibold text-rose-700">Klik Bulan Ini</h3>
                            <p class="text-3xl font-extrabold text-rose-600 mt-2">{{ number_format($affiliateClicksThisMonth) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking & Affiliate Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Booking Statistics -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Statistik Booking</h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="p-4 bg-teal-50 rounded-lg border border-teal-200">
                                <h3 class="text-sm font-semibold text-teal-700">Total Booking</h3>
                                <p class="text-3xl font-extrabold text-teal-600 mt-2">{{ number_format($totalBookings) }}</p>
                            </div>
                            <div class="p-4 bg-cyan-50 rounded-lg border border-cyan-200">
                                <h3 class="text-sm font-semibold text-cyan-700">Booking Hari Ini</h3>
                                <p class="text-3xl font-extrabold text-cyan-600 mt-2">{{ number_format($bookingsToday) }}</p>
                            </div>
                            <div class="p-4 bg-sky-50 rounded-lg border border-sky-200">
                                <h3 class="text-sm font-semibold text-sky-700">Booking Bulan Ini</h3>
                                <p class="text-3xl font-extrabold text-sky-600 mt-2">{{ number_format($bookingsThisMonth) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Affiliate & Property Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Ringkasan Lainnya</h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-200">
                                <h3 class="text-sm font-semibold text-emerald-700">Affiliate Aktif</h3>
                                <p class="text-3xl font-extrabold text-emerald-600 mt-2">{{ number_format($activeAffiliates) }}</p>
                            </div>
                            <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                <h3 class="text-sm font-semibold text-yellow-700">Affiliate Pending</h3>
                                <p class="text-3xl font-extrabold text-yellow-600 mt-2">{{ number_format($pendingAffiliates) }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <h3 class="text-sm font-semibold text-gray-700">Tipe Kamar</h3>
                                    <p class="text-3xl font-extrabold text-gray-600 mt-2">{{ $roomCount }}</p>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <h3 class="text-sm font-semibold text-gray-700">Ruang MICE</h3>
                                    <p class="text-3xl font-extrabold text-gray-600 mt-2">{{ $miceCount }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart: Last 7 Days -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Tren Kunjungan 7 Hari Terakhir</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kunjungan Website</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klik Affiliate</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($last7Days as $day)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $day['date'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ number_format($day['website_visits']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
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

            <!-- Top Performing Affiliates -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top by Clicks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Top 5 Affiliate (Berdasarkan Klik)</h4>
                        <div class="space-y-3">
                            @forelse($topAffiliatesByClicks as $index => $affiliate)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-orange-100 text-orange-800 font-bold">
                                            {{ $index + 1 }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $affiliate->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $affiliate->user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-orange-600">{{ number_format($affiliate->total_clicks) }}</p>
                                    <p class="text-xs text-gray-500">klik</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">Belum ada data affiliate.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Top by Bookings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Top 5 Affiliate (Berdasarkan Booking)</h4>
                        <div class="space-y-3">
                            @forelse($topAffiliatesByBookings as $index => $affiliate)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-teal-100 text-teal-800 font-bold">
                                            {{ $index + 1 }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $affiliate->user->name }}</p>
                                        <p class="text-xs text-gray-500">Komisi: Rp {{ number_format($affiliate->total_commission ?? 0) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-teal-600">{{ number_format($affiliate->total_bookings) }}</p>
                                    <p class="text-xs text-gray-500">booking</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">Belum ada data affiliate.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>