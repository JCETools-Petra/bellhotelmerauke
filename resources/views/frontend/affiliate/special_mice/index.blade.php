@extends('layouts.frontend')

@section('seo_title', 'Book MICE Event')

@section('content')
<div class="page-content-wrapper" style="background-color: #f8f9fa;">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="display-5">Book MICE Event</h1>
            <a href="{{ route('affiliate.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>

        <p class="lead mb-5">Pilih paket MICE untuk booking event Anda dan dapatkan komisi.</p>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($miceKits->isEmpty())
            <div class="alert alert-info text-center py-5">
                <h4 class="alert-heading">Paket MICE Belum Tersedia</h4>
                <p>Saat ini belum ada paket MICE yang tersedia untuk booking. Silakan hubungi admin untuk informasi lebih lanjut.</p>
            </div>
        @else
            <div class="row">
                @foreach ($miceKits as $kit)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            @php
                                $extension = '';
                                if ($kit->original_filename) {
                                    $extension = strtolower(pathinfo($kit->original_filename, PATHINFO_EXTENSION));
                                }
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
                            @endphp

                            @if ($kit->type == 'file' && $isImage)
                                <img src="{{ route('affiliate.mice-kit.preview', $kit->id) }}" class="card-img-top" alt="{{ $kit->title }}" style="object-fit: cover; height: 200px;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="min-height: 200px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-calendar-event text-primary" viewBox="0 0 16 16">
                                        <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                    </svg>
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $kit->title }}</h5>
                                <p class="card-text text-muted small flex-grow-1">{{ Str::limit($kit->description, 100) }}</p>

                                <div class="mt-auto pt-3">
                                    <a href="{{ route('affiliate.special_mice.show', $kit->id) }}" class="btn btn-primary w-100">
                                        Lihat Detail & Book
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
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
