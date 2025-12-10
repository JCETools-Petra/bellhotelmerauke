@extends('layouts.frontend')

@section('seo_title', 'Affiliate Dashboard - Bell Hotel Merauke')

@section('content')
    {{-- 1. HERO & STATS SECTION --}}
    <div class="relative bg-gray-900 pt-24 pb-32 overflow-hidden">
        {{-- Background Decoration --}}
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-yellow-500 rounded-full opacity-10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-blue-600 rounded-full opacity-10 blur-3xl"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            {{-- Header Bar: Judul & Tombol Logout --}}
            <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
                <div class="flex-grow">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-yellow-500 font-bold uppercase tracking-[0.2em] text-xs mb-2 block">Overview</span>
                            <h1 class="text-3xl md:text-4xl font-heading font-bold text-white">
                                Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-600">{{ Auth::user()->name }}</span>
                            </h1>
                            <p class="text-gray-400 text-sm mt-2">Pantau performa dan pendapatan affiliate Anda hari ini.</p>
                        </div>
                        
                        {{-- TOMBOL LOGOUT (Desktop - Posisi Kanan Atas) --}}
                        <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 text-sm font-medium flex items-center gap-2 transition-colors border border-gray-700 hover:border-red-500/50 px-4 py-2 rounded-lg bg-gray-800/50 backdrop-blur-sm">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Quick Actions (Baris Baru di bawah Header) --}}
            <div class="flex flex-wrap gap-3 mb-8">
                {{-- Tombol Booking Manual (Kamar) --}}
                <a href="{{ route('affiliate.bookings.create') }}" class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold py-2.5 px-5 rounded-lg transition-all shadow-lg transform hover:-translate-y-0.5 text-sm">
                    <i class="fas fa-bed"></i> Booking Kamar
                </a>

                {{-- Tombol Booking MICE --}}
                <a href="{{ route('affiliate.special_mice.show', 6) }}" class="inline-flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-900 font-bold py-2.5 px-5 rounded-lg transition-all shadow-lg transform hover:-translate-y-0.5 text-sm">
                    <i class="fas fa-handshake"></i> Booking MICE
                </a>
                
                {{-- Tombol Katalog --}}
                <a href="{{ route('affiliate.mice-kit.index') }}" class="inline-flex items-center gap-2 bg-gray-800 hover:bg-gray-700 text-white font-bold py-2.5 px-5 rounded-lg transition-all border border-gray-700 text-sm">
                    <i class="fas fa-book-open"></i> Katalog
                </a>

                {{-- Tombol Logout (Mobile Only - Muncul di sini saat layar kecil) --}}
                <form method="POST" action="{{ route('logout') }}" class="md:hidden w-full mt-2">
                    @csrf
                    <button type="submit" class="w-full inline-flex justify-center items-center gap-2 bg-red-600/10 hover:bg-red-600/20 text-red-500 font-bold py-2.5 px-5 rounded-lg transition-all border border-red-500/30 text-sm">
                        <i class="fas fa-sign-out-alt"></i> Keluar Akun
                    </button>
                </form>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Card 1: Clicks --}}
                <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-2xl p-6 flex items-center gap-4 group hover:border-yellow-500/50 transition-colors">
                    <div class="w-14 h-14 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-all">
                        <i class="fas fa-mouse-pointer text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs uppercase font-bold tracking-wider">Total Klik</p>
                        <h3 class="text-3xl font-bold text-white mt-1">{{ number_format($totalClicks ?? 0) }}</h3>
                    </div>
                </div>

                {{-- Card 2: Bookings --}}
                <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-2xl p-6 flex items-center gap-4 group hover:border-yellow-500/50 transition-colors">
                    <div class="w-14 h-14 rounded-xl bg-green-500/10 flex items-center justify-center text-green-400 group-hover:bg-green-500 group-hover:text-white transition-all">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs uppercase font-bold tracking-wider">Booking Sukses</p>
                        <h3 class="text-3xl font-bold text-white mt-1">{{ number_format($totalBookings ?? 0) }}</h3>
                    </div>
                </div>

                {{-- Card 3: Commissions --}}
                <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-2xl p-6 flex items-center gap-4 group hover:border-yellow-500/50 transition-colors">
                    <div class="w-14 h-14 rounded-xl bg-yellow-500/10 flex items-center justify-center text-yellow-400 group-hover:bg-yellow-500 group-hover:text-gray-900 transition-all">
                        <i class="fas fa-wallet text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs uppercase font-bold tracking-wider">Komisi (Unpaid)</p>
                        <h3 class="text-3xl font-bold text-white mt-1">Rp {{ number_format($totalCommissions ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. REFERRAL LINK & HISTORY --}}
    <div class="bg-gray-50 py-12 -mt-20 relative z-20 rounded-t-[2.5rem]">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Notification Success --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Referral Link Card --}}
            <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 border border-gray-100 mb-10 relative overflow-hidden transform -translate-y-12">
                <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-100 rounded-full opacity-50 -mr-10 -mt-10"></div>
                
                <div class="relative z-10">
                    <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center gap-2">
                        <i class="fas fa-link text-yellow-500"></i> Link Referral Anda
                    </h3>
                    <p class="text-gray-500 text-sm mb-4">Bagikan link ini ke media sosial atau calon tamu untuk mulai mendapatkan komisi.</p>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-grow">
                            <input type="text" id="referralLink" 
                                   value="{{ route('home') }}/?ref={{ Auth::user()->affiliate->referral_code ?? '' }}" 
                                   class="w-full bg-gray-50 border border-gray-300 text-gray-600 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block p-3 pr-12 font-mono" readonly>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-globe text-gray-400"></i>
                            </div>
                        </div>
                        <button onclick="copyLink()" class="bg-gray-900 hover:bg-yellow-500 text-white hover:text-gray-900 font-bold py-3 px-6 rounded-lg transition-all shadow-lg flex items-center justify-center gap-2 sm:w-auto w-full">
                            <i class="far fa-copy"></i> <span>Copy Link</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Commission History Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-10">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Riwayat Komisi Terbaru</h3>
                    <span class="text-xs bg-gray-100 text-gray-500 px-3 py-1 rounded-full font-medium">
                        5 Transaksi Terakhir
                    </span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 font-bold">Tanggal</th>
                                <th class="px-6 py-4 font-bold">Keterangan / Tamu</th>
                                <th class="px-6 py-4 font-bold">Nominal</th>
                                <th class="px-6 py-4 font-bold text-center">Status</th>
                                <th class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($commissions ?? [] as $commission)
                            <tr class="hover:bg-yellow-50/50 transition-colors">
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $commission->created_at->format('d M Y') }}
                                    <br><span class="text-xs text-gray-400">{{ $commission->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($commission->booking)
                                        <div class="font-medium text-gray-900">{{ $commission->booking->guest_name }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            @if($commission->booking->mice_kit_id)
                                                <span class="text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded">MICE</span>
                                            @elseif($commission->booking->room_id)
                                                <span class="text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">ROOM</span>
                                            @endif
                                            #{{ $commission->booking->booking_code ?? $commission->booking->id }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Booking dihapus</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-green-600">
                                    + Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($commission->status == 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Paid
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Unpaid
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" 
                                        onclick="showDetail(this)"
                                        data-date="{{ $commission->created_at->format('d F Y H:i') }}"
                                        data-guest="{{ $commission->booking->guest_name ?? '-' }}"
                                        data-total="Rp {{ number_format($commission->booking->total_price ?? 0, 0, ',', '.') }}"
                                        data-commission="Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}"
                                        data-rate="{{ $commission->rate }}%"
                                        data-note="{{ $commission->notes ?? '-' }}"
                                        class="text-gray-400 hover:text-yellow-600 transition-colors"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400 bg-white">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-200"></i>
                                        <p>Belum ada riwayat komisi.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                @if(isset($commissions) && $commissions->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $commissions->links() }}
                </div>
                @endif
            </div>

            {{-- 3. REVIEW SECTION (UPDATED: Star Rating Fix) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Ulasan & Testimoni Program</h3>
                    <p class="text-sm text-gray-500">Bagikan pengalaman Anda bermitra dengan kami.</p>
                </div>
                <div class="p-6">
                    @php
                        $myReview = Auth::user()->affiliate->review;
                    @endphp

                    <form action="{{ route('affiliate.review.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Rating</label>
                            
                            {{-- LOGIKA BARU: STAR RATING --}}
                            {{-- Menggunakan flex-row-reverse agar CSS sibling selector (~) bisa mewarnai bintang sebelumnya --}}
                            <div class="flex flex-row-reverse justify-end items-center w-fit star-rating-group">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                           class="hidden peer" 
                                           {{ ($myReview && $myReview->rating == $i) ? 'checked' : '' }} required>
                                    
                                    <label for="star{{ $i }}" 
                                           class="cursor-pointer text-4xl text-gray-300 transition-colors mx-0.5 peer-checked:text-yellow-400 hover:text-yellow-400">
                                        ★
                                    </label>
                                @endfor
                            </div>
                            
                            {{-- CSS Khusus untuk Logika Bintang (Disisipkan langsung agar styling akurat) --}}
                            <style>
                                /* Saat input dicentang, warnai semua label (bintang) setelahnya (secara visual di kiri) */
                                .star-rating-group input:checked ~ label {
                                    color: #facc15; /* text-yellow-400 */
                                }
                                
                                /* Saat hover salah satu label, warnai label itu sendiri */
                                .star-rating-group label:hover {
                                    color: #facc15;
                                }

                                /* Dan warnai semua label setelahnya (visual kiri) saat di-hover */
                                .star-rating-group label:hover ~ label {
                                    color: #facc15;
                                }

                                /* Trik: Saat grup di-hover, reset warna bintang yang SUDAH dicentang menjadi abu-abu dulu,
                                   supaya efek hover bisa mengambil alih visualnya */
                                .star-rating-group:hover input:checked ~ label {
                                    color: #d1d5db; /* text-gray-300 */
                                }
                                
                                /* Kembalikan warna kuning jika cursor berada tepat di atas bintang yang dicentang atau sebelumnya */
                                .star-rating-group input:checked + label:hover,
                                .star-rating-group input:checked + label:hover ~ label,
                                .star-rating-group input:checked ~ label:hover,
                                .star-rating-group input:checked ~ label:hover ~ label,
                                .star-rating-group label:hover ~ input:checked ~ label {
                                    color: #facc15;
                                }
                            </style>
                        </div>

                        <div class="mb-5">
                            <label for="review" class="block text-gray-700 text-sm font-bold mb-2">Komentar</label>
                            <textarea name="review" id="review" rows="4" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block p-3" placeholder="Ceritakan pengalaman Anda...">{{ $myReview ? $myReview->review : '' }}</textarea>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-gray-900 hover:bg-yellow-500 hover:text-gray-900 text-white font-bold py-2.5 px-6 rounded-lg transition-all shadow-md">
                                {{ $myReview ? 'Update Review' : 'Kirim Review' }}
                            </button>
                            
                            @if($myReview)
                                <div class="text-sm">
                                    <span class="text-gray-500 mr-2">Status:</span>
                                    <span class="px-2 py-1 rounded-md text-xs font-bold {{ $myReview->is_visible ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                        {{ $myReview->is_visible ? 'DITAMPILKAN PUBLIK' : 'MENUNGGU MODERASI' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL DETAIL (Tailwind Version) --}}
    <div id="detailModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-gray-900/75 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">
                
                {{-- Modal Header --}}
                <div class="bg-gray-900 px-4 py-4 sm:px-6 flex justify-between items-center">
                    <h3 class="text-base font-bold leading-6 text-white" id="modal-title">Detail Transaksi</h3>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="px-4 py-6 sm:px-6">
                    <div class="space-y-4">
                        <div class="text-center pb-4 border-b border-gray-100">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Komisi Anda</span>
                            <p class="text-3xl font-extrabold text-green-600 mt-1" id="modalCommission">Rp 0</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="block text-xs text-gray-500 mb-1">Tanggal</span>
                                <span class="font-medium text-gray-900" id="modalDate">-</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 mb-1">Nama Tamu</span>
                                <span class="font-medium text-gray-900" id="modalGuest">-</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 mb-1">Total Transaksi</span>
                                <span class="font-medium text-gray-900" id="modalTotal">-</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 mb-1">Rate Komisi</span>
                                <span class="font-medium text-gray-900" id="modalRate">-</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 mt-2">
                            <span class="block text-xs text-gray-500 mb-1">Catatan Sistem</span>
                            <p class="text-xs text-gray-700 italic" id="modalNote">-</p>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Fungsi Copy Link
        function copyLink() {
            var copyText = document.getElementById("referralLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999); // Untuk Mobile
            navigator.clipboard.writeText(copyText.value).then(() => {
                alert("Link berhasil disalin!");
            });
        }

        // Fungsi Modal Detail
        function showDetail(button) {
            // Ambil data dari atribut
            const date = button.getAttribute('data-date');
            const guest = button.getAttribute('data-guest');
            const total = button.getAttribute('data-total');
            const commission = button.getAttribute('data-commission');
            const rate = button.getAttribute('data-rate');
            const note = button.getAttribute('data-note');

            // Isi ke dalam modal
            document.getElementById('modalDate').innerText = date;
            document.getElementById('modalGuest').innerText = guest;
            document.getElementById('modalTotal').innerText = total;
            document.getElementById('modalCommission').innerText = commission;
            document.getElementById('modalRate').innerText = rate;
            document.getElementById('modalNote').innerText = note;

            // Tampilkan modal (Hapus class hidden)
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }
    </script>
    @endpush
@endsection