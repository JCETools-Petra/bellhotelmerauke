<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $title ?? 'All Bookings' }}
            </h2>
            {{-- Tombol Filter Status (Opsional) --}}
            <div class="flex space-x-2">
                <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="px-3 py-1 text-xs rounded-full {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700' }}">Pending</a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'confirmed']) }}" class="px-3 py-1 text-xs rounded-full {{ request('status') == 'confirmed' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }}">Confirmed</a>
                <a href="{{ route('admin.bookings.index', ['type' => request('type')]) }}" class="px-3 py-1 text-xs rounded-full bg-gray-800 text-white">Reset</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 border-l-4 border-green-500 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 text-red-700 border-l-4 border-red-500 rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Guest & ID</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    {{ request('type') == 'mice' ? 'Event / Package' : 'Room Detail' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dates</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total Price</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($bookings as $booking)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $booking->guest_name }}</div>
                                        <div class="text-xs text-gray-500 font-mono">#{{ $booking->booking_code ?? $booking->id }}</div>
                                        @if($booking->affiliate)
                                            <div class="text-xs text-blue-600 mt-1">Ref: {{ $booking->affiliate->user->name }}</div>
                                        @endif
                                    </td>
                                    
                                    {{-- Kolom Dinamis (Room vs MICE) --}}
                                    <td class="px-6 py-4">
                                        @if($booking->mice_kit_id)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 mb-1">MICE</span>
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->event_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->miceKit->title ?? 'Deleted Package' }} ({{ $booking->pax }} Pax)</div>
                                        @elseif($booking->room)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mb-1">ROOM</span>
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->room->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->num_rooms }} Kamar</div>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex flex-col">
                                            <span>In: {{ \Carbon\Carbon::parse($booking->checkin_date)->format('d M Y') }}</span>
                                            @if($booking->checkout_date != $booking->checkin_date)
                                            <span>Out: {{ \Carbon\Carbon::parse($booking->checkout_date)->format('d M Y') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-emerald-600">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColor = match($booking->status) {
                                                'confirmed', 'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                'awaiting_arrival' => 'bg-blue-100 text-blue-800',
                                                default => 'bg-yellow-100 text-yellow-800'
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                        @if($booking->payment_method == 'pay_at_hotel')
                                            <div class="text-xs text-gray-500 mt-1 italic">Pay at Hotel</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            @if ($booking->status != 'cancelled')
                                                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" onchange="if(confirm('Change status?')) this.form.submit()" class="text-xs rounded border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-1">
                                                        <option value="pending" @selected($booking->status == 'pending')>Pending</option>
                                                        <option value="confirmed" @selected($booking->status == 'confirmed')>Confirm</option>
                                                        <option value="cancelled">Cancel</option>
                                                    </select>
                                                    {{-- Hidden input agar filter tidak hilang saat update --}}
                                                    <input type="hidden" name="payment_status" value="{{ $booking->payment_status }}">
                                                </form>
                                            @endif

                                            <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Delete this booking permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 ml-2" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            <span class="text-lg font-medium">No bookings found</span>
                                            <span class="text-sm">Try changing the filter or creating a new booking.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $bookings->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>