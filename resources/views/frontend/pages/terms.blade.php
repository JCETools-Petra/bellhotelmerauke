{{-- File: resources/views/frontend/pages/terms.blade.php --}}

@extends('layouts.frontend')

@section('seo_title', 'Terms and Conditions - ' . ($settings['website_title'] ?? 'Bell Hotel Merauke'))
@section('meta_description', 'Syarat dan Ketentuan penggunaan layanan Bell Hotel Merauke.')

@section('content')
<div class="page-content-wrapper" style="padding-top: 8rem; padding-bottom: 5rem;">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card p-4 p-md-5 shadow-sm">
                    <h1 class="text-center section-title">Terms and Conditions</h1>
                    
                    <div class="page-content mt-4 preserve-format">
                        @if(!empty($settings['terms_and_conditions']))
                            {!! $settings['terms_and_conditions'] !!}
                        @else
                            <p class="text-center text-muted">
                                Konten untuk Syarat dan Ketentuan belum diatur.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection