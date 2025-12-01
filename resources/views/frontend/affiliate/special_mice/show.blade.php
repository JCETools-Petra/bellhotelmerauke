@extends('layouts.frontend')

@section('seo_title', $miceKit->title . ' - Penawaran MICE')

@section('content')
    {{-- 1. HERO HEADER --}}
    <div class="relative bg-gray-900 pt-24 pb-16 sm:pb-20 overflow-hidden">
        {{-- Background Effect --}}
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-yellow-500 rounded-full opacity-10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-blue-600 rounded-full opacity-10 blur-3xl"></div>
        
        <div class="container mx-auto px-4 relative z-10 text-center">
            <nav class="flex justify-center text-sm text-gray-400 mb-4">
                <a href="{{ route('affiliate.dashboard') }}" class="hover:text-yellow-500 transition-colors">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-white">Buat Penawaran</span>
            </nav>
            <h1 class="text-3xl md:text-5xl font-heading font-bold text-white mb-4 tracking-tight shadow-sm">
                {{ $miceKit->title }}
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-sm md:text-base">
                Buat penawaran spesial untuk klien Anda dan dapatkan komisi menarik.
            </p>
        </div>
    </div>

    {{-- 2. MAIN CONTENT --}}
    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KOLOM KIRI: INFORMASI PAKET --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Informasi Menu</h3>
                            <p class="text-xs text-gray-500">Detail paket yang ditawarkan</p>
                        </div>

                        {{-- Preview Thumbnail --}}
                        <div class="p-6 bg-gray-50 flex flex-col items-center justify-center text-center border-b border-gray-100">
                            @php
                                $extension = $miceKit->original_filename ? strtolower(pathinfo($miceKit->original_filename, PATHINFO_EXTENSION)) : '';
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp

                            @if ($miceKit->type == 'file' && $isImage)
                                <img src="{{ route('affiliate.mice-kit.preview', $miceKit->id) }}" class="rounded-lg shadow-md max-h-48 object-cover mb-4" alt="Preview">
                            @else
                                <div class="w-20 h-20 bg-red-100 text-red-500 rounded-2xl flex items-center justify-center mb-4 shadow-sm">
                                    <i class="fas fa-file-pdf text-3xl"></i>
                                </div>
                            @endif
                            
                            <h4 class="font-bold text-gray-800 text-sm line-clamp-2">{{ $miceKit->title }}</h4>
                        </div>

                        <div class="p-6 space-y-4">
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase block mb-2">Deskripsi</label>
                                <p class="text-sm text-gray-600 leading-relaxed text-justify">
                                    {{ $miceKit->description }}
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-3 pt-2">
                                {{-- Tombol Lihat Menu (Trigger Modal) --}}
                                <button type="button" onclick="openModal()" class="flex items-center justify-center gap-2 w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-2.5 rounded-lg text-xs transition-all shadow-sm">
                                    <i class="fas fa-eye"></i> Lihat Menu
                                </button>
                                
                                {{-- Tombol Download --}}
                                <a href="{{ route('affiliate.mice-kit.download', $miceKit->id) }}" target="_blank" class="flex items-center justify-center gap-2 w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-2.5 rounded-lg text-xs transition-all shadow-sm">
                                    <i class="fas fa-download"></i> Unduh
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: FORM BOOKING --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg border-t-4 border-yellow-500 overflow-hidden">
                        <div class="p-6 md:p-8">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-bold text-gray-900">Formulir Penawaran</h3>
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">
                                    Rate Komisi: {{ $commissionRate }}%
                                </span>
                            </div>

                            @if ($errors->any())
                                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded-r-lg">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('affiliate.special_mice.store') }}" method="POST" id="bookingForm" class="space-y-6">
                                @csrf
                                <input type="hidden" name="mice_kit_id" value="{{ $miceKit->id }}">
                                <input type="hidden" id="commissionRate" value="{{ $commissionRate }}">

                                {{-- Baris 1: Nama Event & WhatsApp --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Event / Klien</label>
                                        <input type="text" name="event_name" value="{{ old('event_name') }}" required 
                                               class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 transition-colors" 
                                               placeholder="Contoh: Gathering PT Maju">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">No. WhatsApp (Untuk Notifikasi)</label>
                                        <input type="tel" name="phone" value="{{ old('phone') }}" required 
                                               class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 transition-colors" 
                                               placeholder="0812xxxxxxxx">
                                    </div>
                                </div>

                                {{-- Baris 2: Tanggal & Pax --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Acara</label>
                                        <div class="relative">
                                            <input type="text" name="check_in_date" class="datepicker w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 transition-colors" 
                                                   required placeholder="Pilih Tanggal" value="{{ old('check_in_date') }}">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                                <i class="far fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jumlah Peserta (Pax)</label>
                                        <input type="number" name="pax" value="{{ old('pax', 50) }}" min="10" required 
                                               class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 transition-colors">
                                    </div>
                                </div>

                                <hr class="border-dashed border-gray-200">

                                {{-- Baris 3: Harga Deal & Estimasi Komisi --}}
                                <div class="bg-yellow-50 rounded-xl p-6 border border-yellow-100">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Total Nilai Deal (Rp)</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-bold">Rp</span>
                                                {{-- PERBAIKAN: Mengubah pl-12 menjadi pl-16 agar ada jarak cukup dari 'Rp' --}}
                                                <input type="text" id="displayPriceInput" required 
                                                       class="w-full bg-white border-0 ring-1 ring-gray-200 rounded-lg pl-16 pr-4 py-3 text-lg font-bold text-gray-900 focus:ring-2 focus:ring-yellow-500 placeholder-gray-300" 
                                                       placeholder="0">
                                                <input type="hidden" id="realPriceInput" name="total_price" value="{{ old('total_price') }}">
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">*Masukkan harga total yang disepakati dengan klien.</p>
                                        </div>
                                        
                                        <div class="text-center md:text-right">
                                            <span class="block text-xs font-bold text-green-600 uppercase mb-1">Estimasi Komisi Anda</span>
                                            <p class="text-3xl font-extrabold text-green-600 tracking-tight" id="commissionDisplay">Rp 0</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Catatan --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Catatan Tambahan</label>
                                    <textarea name="note" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:ring-yellow-500 focus:border-yellow-500 transition-colors" placeholder="Request khusus layout, menu, dll...">{{ old('note') }}</textarea>
                                </div>

                                {{-- Submit --}}
                                <button type="submit" class="w-full bg-gray-900 hover:bg-yellow-500 text-white hover:text-gray-900 font-bold py-4 rounded-xl transition-all duration-300 shadow-lg transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                    <span>Kirim Booking (Pay at Hotel)</span>
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL PDF PREVIEW (Tailwind Version) --}}
    <div id="pdfModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-5xl h-[85vh] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col">
                {{-- Header --}}
                <div class="bg-gray-900 px-6 py-4 flex justify-between items-center shrink-0">
                    <h3 class="text-white font-bold flex items-center gap-2">
                        <i class="fas fa-book-open text-yellow-500"></i> {{ $miceKit->title }}
                    </h3>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                {{-- Body (Iframe) --}}
                <div class="flex-grow bg-gray-100 relative">
                    <iframe src="{{ route('affiliate.mice-kit.preview', $miceKit->id) }}#toolbar=0" class="absolute inset-0 w-full h-full border-0"></iframe>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // 1. Flatpickr Init
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d", // Format yang dikirim ke server (Database Friendly)
                altInput: true,      // Aktifkan input alternatif
                altFormat: "d-m-Y",  // Format yang dilihat user (Human Friendly)
                minDate: "today"
            });
        });

        // 2. Modal Logic (Vanilla JS)
        function openModal() {
            document.getElementById('pdfModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scroll
        }
        function closeModal() {
            document.getElementById('pdfModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // 3. Calculator Logic
        document.addEventListener('DOMContentLoaded', function() {
            const displayInput = document.getElementById('displayPriceInput');
            const realInput = document.getElementById('realPriceInput');
            const commissionDisplay = document.getElementById('commissionDisplay');
            const commissionRate = parseFloat(document.getElementById('commissionRate').value) || 0;

            const formatRupiah = (number) => {
                return new Intl.NumberFormat('id-ID').format(number);
            };

            const formatCurrency = (number) => {
                return new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR',
                    minimumFractionDigits: 0, 
                    maximumFractionDigits: 0 
                }).format(number);
            };

            const calculateCommission = () => {
                let dealPrice = parseFloat(realInput.value) || 0;
                let commission = dealPrice * (commissionRate / 100);
                commissionDisplay.textContent = formatCurrency(commission);
            };

            // Event Listener Input
            displayInput.addEventListener('input', function(e) {
                // Hapus semua karakter non-angka
                let rawValue = this.value.replace(/\D/g, '');
                
                // Simpan nilai asli ke hidden input
                realInput.value = rawValue;

                // Format tampilan dengan titik ribuan
                if(rawValue !== '') {
                    this.value = formatRupiah(rawValue);
                } else {
                    this.value = '';
                }

                calculateCommission();
            });

            // Jika ada nilai lama (old input saat error validasi), format ulang
            if(realInput.value) {
                displayInput.value = formatRupiah(realInput.value);
                calculateCommission();
            }
        });
    </script>
    @endpush
@endsection