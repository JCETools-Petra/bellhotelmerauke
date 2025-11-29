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
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded-md">
                    {{ session('error') }}
                </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest & Booking ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->guest_name }}</div>
                                        <div class="text-sm text-gray-500">ID: #{{ $booking->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->room->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($booking->checkin_date)->format('d M Y') }} - 
                                        {{ \Carbon\Carbon::parse($booking->checkout_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClass = '';
                                            if ($booking->status == 'pending') $statusClass = 'bg-yellow-100 text-yellow-800';
                                            elseif ($booking->status == 'confirmed') $statusClass = 'bg-green-100 text-green-800';
                                            elseif ($booking->status == 'cancelled') $statusClass = 'bg-red-100 text-red-800';
                                            elseif ($booking->status == 'awaiting_arrival') $statusClass = 'bg-blue-100 text-blue-800';
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                        @if($booking->payment_method == 'pay_at_hotel')
                                            <div class="text-xs text-gray-500 mt-1">(Pay at Hotel)</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                        
                                            {{-- ========================================================== --}}
                                            {{-- PERBAIKAN LOGIKA TOMBOL AKSI DIMULAI DI SINI --}}
                                            {{-- ========================================================== --}}
                        
                                            @if ($booking->payment_method == 'pay_at_hotel' && $booking->status == 'awaiting_arrival')
                                                {{-- Opsi untuk 'Awaiting Arrival' --}}
                                                <form action="{{ route('admin.bookings.confirmPayAtHotel', $booking->id) }}" method="POST" onsubmit="return confirm('Confirm that the guest has arrived and paid? This will generate affiliate commission.');">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-md hover:bg-green-700">Confirm Payment</button>
                                                </form>
                                                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-gray-500 rounded-md hover:bg-gray-600">Cancel</button>
                                                </form>
                        
                                            @elseif ($booking->status == 'cancelled')
                                                {{-- Jika status sudah 'cancelled', hanya tampilkan tombol Delete --}}
                                                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this booking?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700">Delete</button>
                                                </form>
                        
                                            @else
                                                {{-- Dropdown untuk booking online atau yang sudah 'confirmed' --}}
                                                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" onchange="this.form.submit()" class="text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        <option value="pending" @selected($booking->status == 'pending')>Pending</option>
                                                        <option value="awaiting_arrival" @selected($booking->status == 'awaiting_arrival')>Awaiting Arrival</option>
                                                        <option value="confirmed" @selected($booking->status == 'confirmed')>Confirmed</option>
                                                        <option value="cancelled" @selected($booking->status == 'cancelled')>Cancelled</option>
                                                    </select>
                                                </form>
                                                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this booking?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700">Delete</button>
                                                </form>
                                            @endif
                                            
                                            {{-- ========================================================== --}}
                                            {{-- PERBAIKAN LOGIKA TOMBOL AKSI BERAKHIR DI SINI --}}
                                            {{-- ========================================================== --}}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
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