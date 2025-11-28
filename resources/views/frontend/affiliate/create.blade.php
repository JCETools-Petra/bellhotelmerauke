@extends('layouts.frontend')

@section('seo_title', 'Affiliate Program Registration')

@section('content')
<div class="page-content-wrapper">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h1 class="text-center h2 mb-4">Join Our Affiliate Program</h1>
                        <p class="text-center text-muted mb-4">Daftar dan dapatkan komisi dari setiap pemesanan yang berhasil melalui link Anda.</p>
                        
                        {{-- Menampilkan Notifikasi Sukses --}}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Menampilkan Error Validasi --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('affiliate.register.store') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor WhatsApp</label>
                                <input id="phone" class="form-control" type="tel" name="phone" value="{{ old('phone') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" class="form-control" type="password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-custom">
                                    Daftar Sekarang
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