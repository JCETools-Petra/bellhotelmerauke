@extends('layouts.frontend')

@section('seo_title', 'Book MICE - ' . $miceKit->title)

@section('content')
<div class="page-content-wrapper" style="background-color: #f8f9fa;">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="display-5">{{ $miceKit->title }}</h1>
            <a href="{{ route('affiliate.special_mice.index') }}" class="btn btn-secondary">Kembali</a>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4">
                @php
                    $extension = '';
                    if ($miceKit->original_filename) {
                        $extension = strtolower(pathinfo($miceKit->original_filename, PATHINFO_EXTENSION));
                    }
                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
                @endphp

                <div class="card shadow-sm">
                    @if ($miceKit->type == 'file' && $isImage)
                        <img src="{{ route('affiliate.mice-kit.preview', $miceKit->id) }}" class="card-img-top" alt="{{ $miceKit->title }}" style="object-fit: cover; max-height: 400px;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="min-height: 300px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-calendar-event text-primary" viewBox="0 0 16 16">
                                <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">Deskripsi</h5>
                        <p class="card-text">{{ $miceKit->description }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Form Booking MICE</h5>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('affiliate.special_mice.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="mice_kit_id" value="{{ $miceKit->id }}">

                            <div class="mb-3">
                                <label for="check_in_date" class="form-label">Tanggal Event</label>
                                <input type="date" class="form-control" id="check_in_date" name="check_in_date"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                <small class="text-muted">Minimal H+1 dari hari ini</small>
                            </div>

                            <div class="mb-3">
                                <label for="pax" class="form-label">Jumlah Peserta (PAX)</label>
                                <input type="number" class="form-control" id="pax" name="pax"
                                       min="10" required placeholder="Minimal 10 orang">
                            </div>

                            <div class="mb-3">
                                <label for="total_price" class="form-label">Total Harga (Rp)</label>
                                <input type="number" class="form-control" id="total_price" name="total_price"
                                       min="0" step="1000" required placeholder="Contoh: 5000000">
                                <small class="text-muted">Masukkan total harga sesuai kesepakatan dengan customer</small>
                            </div>

                            @if($affiliate && $affiliate->commission_rate)
                                <div class="alert alert-info">
                                    <strong>Komisi Anda: {{ $affiliate->commission_rate }}%</strong>
                                    <p class="mb-0 small">Komisi akan dihitung dari total harga yang Anda input di atas</p>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="note" class="form-label">Catatan (Opsional)</label>
                                <textarea class="form-control" id="note" name="note" rows="3"
                                          placeholder="Tambahkan catatan khusus untuk booking ini"></textarea>
                            </div>

                            <div class="alert alert-warning">
                                <small>
                                    <strong>Perhatian:</strong> Metode pembayaran untuk booking MICE adalah
                                    <strong>Pay at Hotel</strong>. Status booking akan pending hingga admin
                                    mengkonfirmasi pembayaran.
                                </small>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-check-circle"></i> Submit Booking
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
