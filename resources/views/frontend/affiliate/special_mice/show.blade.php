@extends('layouts.frontend')

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