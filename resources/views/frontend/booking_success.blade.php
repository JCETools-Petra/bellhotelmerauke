@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">

                    {{-- PERBAIKAN LOGIKA DIMULAI DI SINI --}}
                    @if($booking->payment_method == 'pay_at_hotel')
                        <h1 class="text-success mb-3">✅<br>Booking Dikonfirmasi!</h1>
                        <p class="lead">Terima kasih, {{ $booking->guest_name }}. Pesanan Anda telah kami konfirmasi. Silakan lakukan pembayaran saat check-in.</p>
                    @else
                        <h1 class="text-success mb-3">✅<br>Pembayaran Berhasil!</h1>
                        <p class="lead">Terima kasih, {{ $booking->guest_name }}. Pesanan Anda telah kami konfirmasi.</p>
                    @endif
                    {{-- AKHIR PERBAIKAN LOGIKA --}}

                    <hr class="my-4">
                    
                    <h5 class="text-start mb-3">Detail Pesanan</h5>
                    <div class="text-start">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="row" style="width: 40%;">ID Booking</th>
                                    <td>#{{ $booking->id }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Nama Tamu</th>
                                    <td>{{ $booking->guest_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Telepon</th>
                                    <td>{{ $booking->guest_phone }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td>{{ $booking->guest_email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Tipe Kamar</th>
                                    <td>{{ $booking->room->name }}</td>
                                </tr>
                                 <tr>
                                    <th scope="row">Jumlah Kamar</th>
                                    <td>{{ $booking->num_rooms ?? 1 }} kamar</td>
                                </tr>
                                <tr>
                                    <th scope="row">Tanggal Check-in</th>
                                    <td>{{ \Carbon\Carbon::parse($booking->checkin_date)->format('l, d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Tanggal Check-out</th>
                                    <td>{{ \Carbon\Carbon::parse($booking->checkout_date)->format('l, d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Total Pembayaran</th>
                                    <td><strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <th scope="row">Metode Pembayaran</th>
                                    {{-- PERBAIKAN LOGIKA TAMPILAN METODE PEMBAYARAN --}}
                                    <td>
                                        @if($booking->payment_method == 'pay_at_hotel')
                                            <span class="fw-bold">Bayar di Hotel</span>
                                        @else
                                            <span class="fw-bold">Bayar Online</span>
                                        @endif
                                    </td>
                                </tr>
                                 <tr>
                                    <th scope="row">Status</th>
                                    {{-- PERBAIKAN LOGIKA STATUS --}}
                                    <td>
                                        @if($booking->payment_method == 'pay_at_hotel')
                                            <span class="badge bg-primary">Confirmed</span>
                                        @else
                                            <span class="badge bg-success">Paid</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="mt-4">Kami telah mengirimkan detail pesanan ini ke email atau WhatsApp Anda. Terima kasih telah memilih Bell Hotel Merauke.</p>

                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Kembali ke Halaman Utama</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection