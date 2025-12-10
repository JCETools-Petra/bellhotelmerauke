<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Commission Payouts') }}
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
                    <p class="text-gray-600 mb-4">Halaman ini menampilkan ringkasan komisi yang belum dibayar. Klik tombol aksi untuk melihat detail.</p>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Affiliate Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referral Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unpaid Amount</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($affiliates as $affiliate)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($affiliate->user)
                                            {{-- LOGIKA PRIVASI NAMA --}}
                                            @php
                                                $fullName = $affiliate->user->name;
                                                $email = $affiliate->user->email;
                                                $displayName = $fullName;
                                                $displayEmail = $email;
                                                $isFrontOffice = Auth::user()->role === 'frontoffice';

                                                if ($isFrontOffice) {
                                                    // 1. Samarkan Nama (Nama Depan Full + Inisial Belakang)
                                                    $names = explode(' ', $fullName);
                                                    $firstName = $names[0] ?? '';
                                                    $abbreviated = $firstName;
                                                    
                                                    for($i = 1; $i < count($names); $i++) {
                                                        if(!empty($names[$i])) {
                                                            $abbreviated .= ' ' . strtoupper(substr($names[$i], 0, 1)) . '.';
                                                        }
                                                    }
                                                    $displayName = $abbreviated;

                                                    // 2. Sembunyikan Email
                                                    $displayEmail = 'Data Privasi Dilindungi';
                                                }
                                            @endphp

                                            {{-- TAMPILAN DI TABEL --}}
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="font-medium text-gray-900">
                                                        {{ $displayName }}
                                                        @if($isFrontOffice)
                                                            <span class="ml-1 text-xs text-orange-500 bg-orange-100 px-1 rounded" title="Nama disamarkan untuk privasi">🔒</span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($isFrontOffice)
                                                        <div class="text-sm text-gray-400 italic flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                            {{ $displayEmail }}
                                                        </div>
                                                    @else
                                                        <div class="text-sm text-gray-500">{{ $displayEmail }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="font-medium text-red-600">User Deleted</div>
                                            <div class="text-sm text-gray-500">ID: {{ $affiliate->id }}</div>
                                            @php $displayName = 'Unknown'; @endphp
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-gray-600">{{ $affiliate->referral_code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-lg text-gray-800">
                                        Rp {{ number_format($affiliate->unpaid_amount ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button 
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition disabled:opacity-50 flex items-center gap-2 ml-auto"
                                            @if($affiliate->user)
                                                {{-- Kita kirim $displayName yang SUDAH disamarkan ke fungsi Javascript --}}
                                                onclick="openCommissionModal({{ $affiliate->id }}, '{{ addslashes($displayName) }}')"
                                            @endif
                                            {{ ($affiliate->unpaid_amount ?? 0) > 0 ? '' : 'disabled' }}>
                                            <span>View & Pay</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No affiliates with unpaid commissions.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $affiliates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="commissionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full backdrop-blur-sm" style="display: none; z-index: 50;">
        <div class="relative top-20 mx-auto p-0 border w-full max-w-2xl shadow-xl rounded-lg bg-white overflow-hidden">
            {{-- Modal Header --}}
            <div class="bg-gray-50 px-5 py-4 border-b flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Rincian Komisi</h3>
                    <p class="text-sm text-gray-500" id="modalSubtitle">Loading...</p>
                </div>
                <button type="button" class="text-gray-400 bg-white hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center border shadow-sm" onclick="closeCommissionModal()">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-5 max-h-96 overflow-y-auto bg-white">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Sumber Komisi</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-2 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Nominal</th>
                        </tr>
                    </thead>
                    <tbody id="commissionDetailsBody" class="divide-y divide-gray-100">
                        {{-- Data will be loaded here via JS --}}
                    </tbody>
                </table>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-gray-50 px-5 py-4 border-t flex justify-end">
                <form id="payForm" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition">
                        Tandai Sudah Dibayar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCommissionModal(affiliateId, affiliateName) {
            // Update UI Modal dengan nama yang sudah disamarkan dari Blade
            document.getElementById('modalSubtitle').innerText = 'Affiliate: ' + affiliateName;
            
            // Set action URL form
            const payForm = document.getElementById('payForm');
            if (payForm) {
                payForm.action = '/admin/commissions/' + affiliateId + '/pay';
            }
    
            // Tampilkan loading state
            let tableBody = document.getElementById('commissionDetailsBody');
            tableBody.innerHTML = '<tr><td colspan="3" class="text-center py-8 text-gray-500 animate-pulse">Memuat data...</td></tr>';
            document.getElementById('commissionModal').style.display = 'block';

            // Fetch data
            fetch('/admin/commissions/' + affiliateId)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    tableBody.innerHTML = ''; 
                    
                    if (data.length > 0) {
                        data.forEach(commission => {
                            let detailHtml = '';
                            let subText = '';

                            // LOGIKA DETEKSI SUMBER KOMISI
                            if (commission.booking_id && commission.booking) {
                                if (commission.booking.mice_kit_id && commission.booking.mice_kit) {
                                    // MICE
                                    detailHtml = commission.booking.event_name || 'MICE Event';
                                    subText = 'Paket: ' + commission.booking.mice_kit.title;
                                } else if (commission.booking.room) {
                                    // ROOM
                                    // Samarkan Booking ID agar lebih rapi
                                    detailHtml = 'Room Booking'; 
                                    subText = commission.booking.room.name;
                                } else {
                                    detailHtml = 'Booking #' + commission.booking_id;
                                    subText = '<span class="text-red-500">Item Deleted</span>';
                                }
                            } else if (commission.notes) {
                                // MANUAL / OTHER
                                const lines = commission.notes.split('\n');
                                detailHtml = lines[0] ? lines[0].replace('MICE Event: ', '') : 'MICE Event';
                                subText = 'Manual Inquiry';
                            } else {
                                detailHtml = 'Manual Commission';
                            }
    
                            const commissionAmount = parseInt(commission.commission_amount).toLocaleString('id-ID');
                            const dateStr = new Date(commission.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    
                            let row = `
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900">${detailHtml}</div>
                                        <div class="text-xs text-gray-500">${subText}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">${dateStr}</td>
                                    <td class="px-4 py-3 text-right text-sm font-bold text-gray-900">Rp ${commissionAmount}</td>
                                </tr>`;
                            tableBody.innerHTML += row;
                        });
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="3" class="text-center py-6 text-gray-500">Tidak ada komisi yang belum dibayar.</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    tableBody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-red-500">Gagal memuat data. Silakan coba lagi.</td></tr>';
                });
        }
    
        function closeCommissionModal() {
            document.getElementById('commissionModal').style.display = 'none';
        }
    </script>
</x-app-layout>