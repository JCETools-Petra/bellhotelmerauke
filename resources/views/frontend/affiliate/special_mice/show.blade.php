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

@section('seo_title', $miceKit->title . ' - Buat Penawaran')

@section('content')
<div class="bg-light min-vh-100 py-5">
    <div class="container">
        
        {{-- Header Navigation --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('affiliate.special_mice.index') }}" class="text-decoration-none text-muted">Paket MICE</a></li>
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">{{ $miceKit->title }}</li>
                </ol>
            </nav>
            <a href="{{ route('affiliate.special_mice.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="row justify-content-center">
            
            <div class="col-lg-8">
                <div class="card border-0 shadow rounded-4">
                    <div class="card-header bg-white border-bottom p-4 text-center">
                        {{-- Menampilkan Judul Paket --}}
                        <h2 class="fw-bold text-dark mb-1">{{ $miceKit->title }}</h2>
                        <p class="text-muted mb-0">
                            <i class="fas fa-file-invoice-dollar me-2 text-primary"></i> Form Penawaran & Komisi
                        </p>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('affiliate.special_mice.store') }}" method="POST" id="bookingForm">
                            @csrf
                            <input type="hidden" name="mice_kit_id" value="{{ $miceKit->id }}">
                            <input type="hidden" id="commissionRate" value="{{ $commissionRate }}">

                            <div class="row g-4">
                                {{-- Nama Event --}}
                                <div class="col-12">
                                    <label class="form-label fw-bold text-muted text-uppercase small">Nama Event / Klien</label>
                                    <input type="text" name="event_name" class="form-control form-control-lg bg-light border-0" placeholder="Contoh: Wedding John & Doe" required>
                                </div>

                                {{-- INPUT BARU: NOMOR WHATSAPP --}}
                                <div class="col-12">
                                    <label class="form-label fw-bold text-muted text-uppercase small">Nomor WhatsApp Aktif (Untuk Info Pemesanan)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 text-muted"><i class="fas fa-phone"></i></span>
                                        <input type="number" name="phone" class="form-control form-control-lg bg-light border-0" placeholder="0812xxxxx" required>
                                    </div>
                                    <div class="form-text text-muted small">Bot akan mengirim detail pemesanan ke nomor ini.</div>
                                </div>

                                {{-- Tanggal & Pax --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted text-uppercase small">Tanggal Acara</label>
                                    <input type="date" name="check_in_date" class="form-control form-control-lg bg-light border-0" required min="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted text-uppercase small">Jumlah Pax</label>
                                    <input type="number" name="pax" class="form-control form-control-lg bg-light border-0" value="50" min="10" required>
                                </div>

                                {{-- INPUT HARGA DEAL --}}
                                <div class="col-12">
                                    <div class="card bg-light border-0 p-3">
                                        <label class="form-label fw-bold text-dark mb-2">Total Nilai Proyek (Deal Price)</label>
                                        <div class="input-group input-group-lg shadow-sm">
                                            <span class="input-group-text bg-white border-end-0 text-dark fw-bold ps-3">Rp</span>
                                            <input type="number" id="totalPriceInput" name="total_price" class="form-control border-start-0 ps-2 fw-bold text-dark fs-3" placeholder="0" required>
                                        </div>
                                    </div>
                                </div>

                                {{-- BOX KOMISI --}}
                                <div class="col-12">
                                    <div class="p-4 rounded-3 bg-success-subtle border border-success border-opacity-25 transition-all text-center">
                                        <span class="d-block text-success fw-bold text-uppercase letter-spacing-1 mb-1">Estimasi Pendapatan Komisi Anda</span>
                                        <h2 class="mb-0 text-success fw-bold display-6" id="commissionDisplay">Rp 0</h2>
                                        <span class="badge bg-success bg-opacity-25 text-success mt-2">Rate: {{ $commissionRate }}%</span>
                                    </div>
                                </div>

                                {{-- Catatan --}}
                                <div class="col-12">
                                    <label class="form-label fw-bold text-muted text-uppercase small">Catatan Operasional</label>
                                    <textarea name="note" class="form-control bg-light border-0" rows="3" placeholder="Request khusus layout, menu, dll..."></textarea>
                                </div>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="d-grid gap-2 mt-5">
                                <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold rounded-3 shadow-sm hover-shadow">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Penawaran (Pay at Hotel)
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalPriceInput = document.getElementById('totalPriceInput');
        const commissionDisplay = document.getElementById('commissionDisplay');
        const commissionRate = parseFloat(document.getElementById('commissionRate').value) || 0;

        const formatRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', { 
                style: 'currency', 
                currency: 'IDR',
                minimumFractionDigits: 0, 
                maximumFractionDigits: 0 
            }).format(number);
        };

        totalPriceInput.addEventListener('input', function() {
            let dealPrice = parseFloat(this.value) || 0;
            let commission = dealPrice * (commissionRate / 100);
            commissionDisplay.textContent = formatRupiah(commission);
        });
    });
</script>

<style>
    .bg-success-subtle { background-color: #d1e7dd; }
    .text-success { color: #198754 !important; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .hover-shadow:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; transition: all .2s; }
    .form-control:focus { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1); }
    input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>
@endsection
