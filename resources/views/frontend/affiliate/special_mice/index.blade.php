@extends('layouts.frontend')

@section('seo_title', 'Pilih Paket Meeting')

@section('content')
<div class="page-content-wrapper py-5 bg-light">
    <div class="container">
        
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-bold text-dark">Special MICE Booking</h1>
                <p class="text-muted">Pilih paket meeting terbaik untuk klien Anda</p>
            </div>
            <a href="{{ route('affiliate.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
        </div>

        <div class="row g-4">
            @foreach($miceKits as $kit)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                    {{-- Gambar --}}
                    <div class="ratio ratio-4x3 bg-secondary">
                        @if($kit->image)
                            <img src="{{ asset('storage/' . $kit->image) }}" class="object-fit-cover" alt="{{ $kit->name }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center text-white">
                                <i class="fas fa-image fa-2x"></i>
                            </div>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column p-4">
                        <h5 class="card-title fw-bold text-dark">{{ $kit->name }}</h5>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit($kit->description, 100) }}
                        </p>
                        
                        <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block">Harga per pax</small>
                                <span class="fw-bold text-primary fs-5">Rp {{ number_format($kit->price, 0, ',', '.') }}</span>
                            </div>
                            
                            {{-- Tombol Ke Detail --}}
                            <a href="{{ route('affiliate.special_mice.show', $kit->id) }}" class="btn btn-primary px-4 rounded-pill">
                                Pilih Paket
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection