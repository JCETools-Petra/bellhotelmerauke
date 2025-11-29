<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-md">
                    {{ session('success') }}
                </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out</th>
                                {{-- ========================================================== --}}
                                {{-- TAMBAHKAN HEADER BARU DI SINI --}}
                                {{-- ========================================================== --}}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                                {{-- ========================================================== --}}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $booking->guest_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $booking->room->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($booking->checkin_date)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($booking->checkout_date)->format('d M Y') }}</td>
                                    
                                    {{-- ========================================================== --}}
                                    {{-- TAMBAHKAN DATA CELL BARU DI SINI --}}
                                    {{-- ========================================================== --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($booking->booking_source === 'Affiliate')
                                            {{-- Class 'badge' dan 'bg-info' adalah dari Bootstrap, untuk Tailwind, kita gunakan class di bawah --}}
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $booking->booking_source }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $booking->booking_source }}
                                            </span>
                                        @endif
                                    </td>
                                    {{-- ========================================================== --}}

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClass = '';
                                            if ($booking->status == 'pending') $statusClass = 'bg-yellow-100 text-yellow-800';
                                            elseif ($booking->status == 'confirmed') $statusClass = 'bg-green-100 text-green-800';
                                            elseif ($booking->status == 'cancelled') $statusClass = 'bg-red-100 text-red-800';
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" onchange="this.form.submit()" class="text-xs rounded-md border-gray-300 shadow-sm">
                                                    <option value="pending" @selected($booking->status == 'pending')>Pending</option>
                                                    <option value="confirmed" @selected($booking->status == 'confirmed')>Confirmed</option>
                                                    <option value="cancelled" @selected($booking->status == 'cancelled')>Cancelled</option>
                                                </select>
                                            </form>
                                            
                                            <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- Sesuaikan colspan menjadi 7 karena ada 7 kolom sekarang --}}
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No bookings found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>