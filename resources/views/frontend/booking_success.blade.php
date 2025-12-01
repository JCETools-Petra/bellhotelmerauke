@extends('layouts.frontend')

@section('seo_title', 'Booking Berhasil - Bell Hotel Merauke')

@section('content')
    <div class="min-h-screen bg-gray-50 py-20 flex items-center justify-center">
        <div class="container mx-auto px-4">
            
            <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 relative">
                
                {{-- Top Decoration --}}
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-green-400 to-green-600"></div>

                <div class="p-10 text-center">
                    
                    {{-- Success Icon --}}
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner animate-bounce-slow">
                        <i class="fas fa-check text-4xl text-green-600"></i>
                    </div>

                    {{-- Heading --}}
                    <h1 class="text-3xl font-heading font-bold text-gray-900 mb-2">
                        @if($booking->payment_method == 'pay_at_hotel')
                            Booking Dikonfirmasi!
                        @else
                            Pembayaran Berhasil!
                        @endif
                    </h1>
                    
                    <p class="text-gray-500 mb-8">
                        Terima kasih, <span class="font-bold text-gray-900">{{ $booking->guest_name }}</span>. 
                        @if($booking->payment_method == 'pay_at_hotel')
                            Pesanan Anda telah diterima. Silakan lakukan pembayaran saat check-in.
                        @else
                            Pesanan Anda telah lunas dan terkonfirmasi otomatis.
                        @endif
                    </p>

                    {{-- Detail Card --}}
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200 text-left">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">
                            Detail Pesanan
                        </h3>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">ID Booking</span>
                                <span class="font-mono font-bold text-gray-900">#{{ $booking->booking_code ?? $booking->id }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tipe Pesanan</span>
                                <span class="font-medium text-gray-900">
                                    @if($booking->room)
                                        {{ $booking->room->name }}
                                    @elseif($booking->miceKit)
                                        Paket MICE: {{ $booking->miceKit->title }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-500">Tanggal</span>
                                <span class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($booking->checkin_date)->format('d M Y') }}
                                    @if($booking->checkout_date && $booking->checkout_date != $booking->checkin_date)
                                        - {{ \Carbon\Carbon::parse($booking->checkout_date)->format('d M Y') }}
                                    @endif
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-500">Metode Bayar</span>
                                <span class="font-medium text-gray-900">
                                    @if($booking->payment_method == 'pay_at_hotel')
                                        <span class="flex items-center gap-1 text-yellow-600"><i class="fas fa-hand-holding-usd"></i> Bayar di Hotel</span>
                                    @else
                                        <span class="flex items-center gap-1 text-green-600"><i class="fas fa-credit-card"></i> Online Payment</span>
                                    @endif
                                </span>
                            </div>
                            
                            <div class="flex justify-between pt-3 border-t border-gray-200 mt-2">
                                <span class="text-gray-900 font-bold">Total</span>
                                <span class="text-lg font-bold text-green-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="mt-8 space-y-3">
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-center w-full bg-gray-900 hover:bg-yellow-500 text-white hover:text-gray-900 font-bold py-3.5 rounded-xl transition-all shadow-lg transform hover:-translate-y-1">
                            Kembali ke Beranda
                        </a>
                        <p class="text-xs text-gray-400">Bukti pesanan juga telah dikirimkan ke WhatsApp/Email Anda.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(-5%); }
            50% { transform: translateY(5%); }
        }
        .animate-bounce-slow {
            animation: bounce-slow 3s infinite ease-in-out;
        }
    </style>
@endsection