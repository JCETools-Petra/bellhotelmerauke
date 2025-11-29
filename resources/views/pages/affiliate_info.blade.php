@extends('layouts.frontend')

@section('seo_title', 'Informasi Program Afiliasi')
@section('meta_description', 'Pelajari lebih lanjut tentang program afiliasi Bell Hotel Merauke dan bagaimana Anda bisa mendapatkan komisi.')

@section('content')
<div class="page-content-wrapper">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="display-5 mb-4">Program Afiliasi Bell Hotel Merauke</h1>
                        <hr class="mb-4" style="border-color: var(--color-gold); border-width: 2px; width: 100px; opacity: 1;">
                        
                        {{-- Konten dinamis dari backend akan ditampilkan di sini --}}
                        <div class="affiliate-content">
                            {!! $content !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tambahkan sedikit style untuk memastikan konten dari editor terlihat bagus --}}
<style>
    .affiliate-content {
        line-height: 1.8;
        font-size: 1.1rem;
    }
    .affiliate-content h1, .affiliate-content h2, .affiliate-content h3 {
        margin-top: 1.5em;
        margin-bottom: 0.5em;
        font-weight: 600;
    }
    .affiliate-content p {
        margin-bottom: 1em;
    }
    .affiliate-content ul, .affiliate-content ol {
        padding-left: 2em;
        margin-bottom: 1em;
    }
    .affiliate-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin-top: 1em;
        margin-bottom: 1em;
    }
</style>
@endsection