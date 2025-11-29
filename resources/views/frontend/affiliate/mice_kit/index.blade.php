@extends('layouts.frontend')

@section('seo_title', 'Digital MICE Kit')

@section('content')
<div class="page-content-wrapper" style="background-color: #f8f9fa;">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="display-5">Digital MICE Kit</h1>
            <a href="{{ route('affiliate.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
        
        <p class="lead mb-5">Gunakan materi di bawah ini untuk membantu promosi Anda.</p>

        @if($miceKits->isEmpty())
            <div class="alert alert-info text-center py-5">
                <h4 class="alert-heading">Materi Belum Tersedia</h4>
                <p>Saat ini belum ada materi promosi yang bisa diunduh. Silakan periksa kembali nanti.</p>
            </div>
        @else
            <div class="row">
                @foreach ($miceKits as $kit)
                    @php
                        $extension = '';
                        if ($kit->original_filename) {
                            $extension = strtolower(pathinfo($kit->original_filename, PATHINFO_EXTENSION));
                        }
                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
                        $isPdf = $extension === 'pdf';
                        // ======================================================================
                        // AWAL LOGIKA BARU UNTUK MENGENALI FILE VIDEO
                        // ======================================================================
                        $isVideoFile = in_array($extension, ['mp4', 'mov', 'ogg', 'qt']);
                        // ======================================================================
                        // AKHIR LOGIKA BARU
                        // ======================================================================
                    @endphp

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            
                            {{-- ====================================================================== --}}
                            {{-- KONDISI IF DIPERBARUI UNTUK MENAMPILKAN VIDEO DENGAN BENAR --}}
                            {{-- ====================================================================== --}}
                            @if ($kit->type == 'video' || ($kit->type == 'file' && $isVideoFile))
                                {{-- Tampilkan video jika tipenya "video" ATAU jika tipenya "file" tapi ekstensinya adalah video --}}
                                <video controls preload="metadata" class="card-img-top" style="height: 200px; background-color: black;">
                                    <source src="{{ route('affiliate.mice-kit.stream', $kit->id) }}" type="{{ Storage::disk('private')->mimeType($kit->path_or_link) }}">
                                    Browser Anda tidak mendukung tag video.
                                </video>
                            
                            @elseif ($kit->type == 'file' && $isImage)
                                <img src="{{ route('affiliate.mice-kit.preview', $kit->id) }}" class="card-img-top" alt="{{ $kit->title }}" style="object-fit: cover; height: 200px;">
                            
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative" style="min-height: 200px;">
                                    @if($isPdf)
                                        <span class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded" style="font-size: 0.8rem;">PDF</span>
                                    @endif
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-file-earmark-text text-muted" viewBox="0 0 16 16">
                                        <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z"/>
                                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 1.5v3a.5.5 0 0 0 .5.5h3l-3.5-3.5z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $kit->title }}</h5>
                                <p class="card-text text-muted small flex-grow-1">{{ $kit->description }}</p>
                                
                                <div class="mt-auto pt-3">
                                     {{-- Tombol juga disesuaikan logikanya --}}
                                    @if ($kit->type == 'video' || $isVideoFile)
                                        <a href="{{ route('affiliate.mice-kit.download', $kit->id) }}" class="btn btn-success w-100">
                                            Download Video
                                        </a>
                                    @elseif ($isPdf)
                                        <a href="{{ route('affiliate.mice-kit.preview', $kit->id) }}" class="btn btn-primary w-100" target="_blank" rel="noopener noreferrer">
                                            Lihat PDF
                                        </a>
                                    @else
                                        <a href="{{ route('affiliate.mice-kit.download', $kit->id) }}" class="btn btn-primary w-100">
                                            Download File
                                        </a>
                                    @endif
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