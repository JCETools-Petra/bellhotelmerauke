@extends('layouts.frontend')

@section('seo_title', $settings['contact_seo_title'] ?? 'Contact Us')
@section('meta_description', $settings['contact_seo_description'] ?? 'Get in touch with us. Find our address, phone number, and location on the map.')

@section('content')
<style>
    .contact-map iframe {
        width: 100%;
        height: 100%;
        min-height: 450px;
        border: 0;
    }
    .page-content-wrapper {
        padding-top: 8rem;
        padding-bottom: 5rem;
    }
</style>

<div class="page-content-wrapper"> 
    <div class="container my-5">
        <div class="text-center mb-5">
            <h1 class="section-title">Contact Us</h1>
            <p class="lead text-muted">Kami senang mendengar dari Anda. Hubungi kami melalui detail di bawah ini.</p>
        </div>

        <div class="row g-5">
            <div class="col-lg-6">
                <div class="card p-4 h-100 shadow-sm">
                    <h3 class="mb-4">Alamat Kami</h3>
                    {{-- style="white-space: pre-wrap;" akan menjaga format baris baru dari alamat --}}
                    <p style="white-space: pre-wrap;">{{ $settings['contact_address'] ?? 'Alamat tidak tersedia.' }}</p>
                    
                    <h3 class="mt-5 mb-4">Detail Kontak</h3>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-phone fa-fw me-3"></i>
                            <div>
                                @if(!empty($settings['contact_phone']))
                                    @php
                                        $originalPhone = $settings['contact_phone'];
                                        $cleanedPhone = preg_replace('/[^0-9]/', '', $originalPhone);
                                        $waPhone = substr($cleanedPhone, 0, 1) === '0' ? '62' . substr($cleanedPhone, 1) : $cleanedPhone;
                                    @endphp
                                    {{-- Tampilkan nomor asli, tapi link-nya ke nomor WhatsApp --}}
                                    <a href="https://wa.me/{{ $waPhone }}" target="_blank" rel="noopener noreferrer">{{ $originalPhone }}</a>
                                @else
                                    Telepon tidak tersedia.
                                @endif
                            </div>
                        </li>
                        <li class="d-flex align-items-center">
                             <i class="fas fa-envelope fa-fw me-3"></i>
                             <div>
                                <a href="mailto:{{ $settings['contact_email'] ?? '' }}">{{ $settings['contact_email'] ?? 'Email tidak tersedia.' }}</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="contact-map shadow rounded overflow-hidden h-100">
                @if(!empty($settings['contact_maps_embed']))
                    {!! $settings['contact_maps_embed'] !!}
                @else
                    <div class="d-flex justify-content-center align-items-center h-100 bg-light">
                        <p class="text-muted">Peta tidak tersedia.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection