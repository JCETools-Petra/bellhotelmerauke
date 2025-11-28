<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Manual Commission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-4 text-gray-600">Gunakan form ini untuk menambahkan komisi secara manual untuk pemesanan yang dilakukan di luar website (misalnya via WhatsApp atau telepon) oleh seorang affiliate.</p>
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.commissions.store') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-6">
                            {{-- Pilih Affiliate --}}
                            <div>
                                <label for="affiliate_id" class="block text-sm font-medium text-gray-700">Pilih Affiliate</label>
                                <select id="affiliate_id" name="affiliate_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Pilih seorang affiliate --</option>
                                    @foreach ($affiliates as $affiliate)
                                        <option value="{{ $affiliate->id }}">{{ $affiliate->user->name }} ({{ $affiliate->referral_code }})</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Total Nilai Booking --}}
                            <div>
                                <label for="booking_amount" class="block text-sm font-medium text-gray-700">Total Nilai Booking (Rp)</label>
                                <input type="number" name="booking_amount" id="booking_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: 1500000" required>
                                <p class="text-xs text-gray-500 mt-1">Masukkan total harga pemesanan tanpa titik atau koma. Komisi akan dihitung otomatis.</p>
                            </div>

                            {{-- Referensi Booking --}}
                            <div>
                                <label for="booking_reference" class="block text-sm font-medium text-gray-700">Referensi Booking</label>
                                <input type="text" name="booking_reference" id="booking_reference" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Pemesanan WhatsApp Bpk. Budi" required>
                                <p class="text-xs text-gray-500 mt-1">Catatan singkat untuk mengingat pemesanan ini.</p>
                            </div>
                        </div>

                        <div class="mt-6 border-t pt-5">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
                                Tambahkan Komisi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>