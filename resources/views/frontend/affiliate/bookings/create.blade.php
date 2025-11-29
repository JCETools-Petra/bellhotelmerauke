@extends('layouts.frontend')

@section('seo_title', 'Create New Booking')

{{-- Tidak ada lagi @push('styles') untuk kalender --}}

@section('content')
<div class="page-content-wrapper">
    <div class="container my-5">
        {{-- Mengubah layout agar form berada di tengah --}}
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark text-white">
                        <h4 class="mb-0">Create Booking for Customer</h4>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('affiliate.bookings.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="room_id" class="form-label fw-bold">Pilih Kamar</label>
                                    <select name="room_id" id="room_id" class="form-select" required>
                                        <option value="">-- Pilih Kamar --</option>
                                        @foreach($rooms as $room)
                                            <option value="{{ $room->id }}" data-price="{{ $room->price }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                                {{ $room->name }} (Rp {{ number_format($room->price, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Tambahkan blok ini di dalam <form> di create.blade.php, sebelum tombol submit --}}

                                <div class="mb-4">
                                    <label class="form-label">Metode Pembayaran</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="pay_online" value="online" checked>
                                        <label class="form-check-label" for="pay_online">
                                            Bayar Online (via Midtrans)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="pay_at_hotel" value="pay_at_hotel">
                                        <label class="form-check-label" for="pay_at_hotel">
                                            Bayar di Hotel
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="checkin" class="form-label fw-bold">Check-in</label>
                                    <input type="text" name="checkin" id="checkin" class="form-control datepicker" value="{{ old('checkin', request('checkin')) }}" required autocomplete="off">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="checkout" class="form-label fw-bold">Check-out</label>
                                    <input type="text" name="checkout" id="checkout" class="form-control datepicker" value="{{ old('checkout', request('checkout')) }}" required autocomplete="off">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="num_rooms" class="form-label fw-bold">Jumlah Kamar</label>
                                    <input type="number" name="num_rooms" class="form-control" value="{{ old('num_rooms', 1) }}" min="1" required>
                                </div>

                                <hr class="my-3">
                                
                                <h5 class="mb-3">Data Tamu</h5>
                                <div class="col-md-12 mb-3">
                                    <label for="guest_name" class="form-label fw-bold">Nama Lengkap Tamu</label>
                                    <input type="text" name="guest_name" class="form-control" value="{{ old('guest_name') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="guest_phone" class="form-label fw-bold">Nomor Telepon Tamu</label>
                                    <input type="tel" name="guest_phone" class="form-control" value="{{ old('guest_phone') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="guest_email" class="form-label fw-bold">Email Tamu (Opsional)</label>
                                    <input type="email" name="guest_email" class="form-control" value="{{ old('guest_email') }}">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary w-100">Submit Booking</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection