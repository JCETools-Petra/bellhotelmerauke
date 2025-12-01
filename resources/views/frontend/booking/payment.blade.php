@extends('layouts.frontend')

@section('seo_title', 'Selesaikan Pembayaran - Bell Hotel Merauke')

@section('content')
    {{-- MIDTRANS SNAP SCRIPT --}}
    {{-- Pastikan Client Key sudah ada di .env --}}
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>

    <div class="min-h-screen bg-gray-50 py-20 relative overflow-hidden">
        
        {{-- Background Header (Top Dark) --}}
        <div class="absolute top-0 left-0 w-full h-[50vh] bg-gray-900 rounded-b-[3rem] z-0">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-yellow-500 rounded-full opacity-10 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-blue-600 rounded-full opacity-10 blur-3xl"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            
            {{-- Page Title --}}
            <div class="text-center mb-10 pt-10">
                <span class="text-yellow-500 font-bold uppercase tracking-[0.2em] text-xs mb-2 block">Secure Checkout</span>
                <h1 class="text-3xl md:text-5xl font-heading font-bold text-white tracking-tight">
                    Selesaikan Pesanan Anda
                </h1>
                <p class="text-gray-400 mt-2 text-sm">Lakukan pembayaran untuk mengamankan reservasi Anda.</p>
            </div>

            <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row border border-gray-100">
                
                {{-- LEFT: ORDER SUMMARY --}}
                <div class="w-full lg:w-5/12 bg-gray-50 p-8 border-b lg:border-b-0 lg:border-r border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-receipt text-yellow-500"></i> Ringkasan Pesanan
                    </h3>

                    {{-- Item Image & Title --}}
                    <div class="rounded-2xl overflow-hidden shadow-sm mb-6 relative group h-48">
                        @if($booking->room && $booking->room->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $booking->room->images->first()->path) }}" class="w-full h-full object-cover" alt="Room Image">
                        @elseif($booking->miceKit)
                            {{-- Jika MICE, mungkin ada gambar paket atau default --}}
                            <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?q=80&w=1000" class="w-full h-full object-cover" alt="MICE Event">
                        @else
                            <img src="https://placehold.co/600x400?text=Bell+Hotel" class="w-full h-full object-cover">
                        @endif
                        
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                            @if($booking->room)
                                <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded uppercase mb-1 inline-block">Room Booking</span>
                                <h4 class="text-white font-bold text-lg">{{ $booking->room->name }}</h4>
                            @elseif($booking->miceKit)
                                <span class="bg-purple-600 text-white text-[10px] font-bold px-2 py-1 rounded uppercase mb-1 inline-block">MICE Event</span>
                                <h4 class="text-white font-bold text-lg">{{ $booking->event_name }}</h4>
                            @endif
                        </div>
                    </div>

                    {{-- Details List --}}
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200 border-dashed">
                            <span class="text-gray-500">Kode Booking</span>
                            <span class="font-mono font-bold text-gray-900 bg-gray-200 px-2 py-1 rounded">{{ $booking->booking_code }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Check-in</span>
                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->checkin_date)->format('d M Y') }}</span>
                        </div>
                        
                        {{-- Tampilkan Check-out hanya jika bukan MICE 1 hari --}}
                        @if($booking->checkout_date && $booking->checkout_date != $booking->checkin_date)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Check-out</span>
                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->checkout_date)->format('d M Y') }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Tamu / Pax</span>
                            <span class="font-medium text-gray-900">
                                {{ $booking->guest_name }} 
                                <span class="text-xs text-gray-400">
                                    ({{ $booking->pax ?? $booking->num_rooms . ' Kamar' }})
                                </span>
                            </span>
                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="mt-8 p-4 bg-white rounded-xl border border-yellow-200 shadow-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 font-medium">Total Tagihan</span>
                            <span class="text-xl font-bold text-yellow-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: ACTION --}}
                <div class="w-full lg:w-7/12 p-8 lg:p-12 bg-white flex flex-col justify-center items-center text-center">
                    
                    <div class="w-24 h-24 bg-yellow-50 rounded-full flex items-center justify-center mb-6 animate-pulse">
                        <i class="fas fa-wallet text-4xl text-yellow-500"></i>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Satu Langkah Lagi!</h2>
                    <p class="text-gray-500 max-w-md mb-8">
                        Silakan selesaikan pembayaran Anda melalui Midtrans untuk mengonfirmasi pesanan ini secara otomatis.
                    </p>

                    <div class="w-full max-w-sm space-y-4">
                        {{-- Tombol Bayar Midtrans --}}
                        <button id="pay-button" class="w-full bg-gray-900 hover:bg-yellow-500 text-white hover:text-gray-900 font-bold py-4 px-8 rounded-xl transition-all shadow-lg transform hover:-translate-y-1 flex items-center justify-center gap-3">
                            <span>Bayar Sekarang</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>

                        <a href="{{ route('home') }}" class="block text-sm text-gray-400 hover:text-gray-600 py-2">
                            Kembali ke Beranda
                        </a>
                    </div>

                    {{-- Secure Badge --}}
                    <div class="mt-10 flex items-center gap-2 text-xs text-gray-400 bg-gray-50 px-4 py-2 rounded-full">
                        <i class="fas fa-lock"></i>
                        <span>Pembayaran Aman & Terenkripsi oleh Midtrans</span>
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- SCRIPT MIDTRANS --}}
    @push('scripts')
    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
            window.snap.pay('{{ $booking->snap_token }}', {
                onSuccess: function (result) {
                    // Redirect ke halaman sukses
                    window.location.href = "{{ route('booking.success', $booking->access_token) }}";
                },
                onPending: function (result) {
                    alert("Menunggu pembayaran Anda!");
                    console.log(result);
                },
                onError: function (result) {
                    alert("Pembayaran gagal!");
                    console.log(result);
                },
                onClose: function () {
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        });
    </script>
    @endpush
@endsection