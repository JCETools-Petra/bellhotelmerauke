{{-- File Partial: resources/views/frontend/partials/show-page-layout.blade.php --}}

@push('styles')
<style>
/* ================= GLOBAL ================= */
body, .page-content-wrapper { background: #fff !important; color: #333; }

/* ================= HERO / SLIDER ================= */
.hero-wrap { padding: 0; background: #fff; }

/* Menghapus max-width dari shell agar slider bisa full-width */
.hero-shell {
    width: 100%;
    margin: 0 auto;
    background: #f8f9fa;
}

.hero-carousel .carousel-item {
    position: relative;
    height: 75vh; /* Tinggi slider disesuaikan menjadi 75% dari tinggi layar */
    min-height: 400px;
    max-height: 700px; /* Batas tinggi maksimal slider */
    background: #f5f5f5;
}

.hero-carousel .carousel-item img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
.carousel-control-prev-icon, .carousel-control-next-icon { filter: invert(1) drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3)); }
.carousel-indicators [data-bs-target] { width: 12px; height: 12px; border-radius: 50%; background-color: rgba(255, 255, 255, 0.5); border-top: 0; border-bottom: 0; }
.carousel-indicators .active { background-color: #fff; }

/* ================= CONTENT ================= */
.content-section {
    margin-top: -80px; /* Konten akan sedikit menimpa slider untuk efek modern */
    position: relative;
    z-index: 10;
}
.content-card { background: #fff; border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 18px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); }
.item-description { font-size: 1.1rem; line-height: 1.8; color: #555; }

/* ================= GALLERY ================= */
.photo-gallery { column-count: 3; column-gap: 1.25rem; }
.photo-gallery .gallery-item { display: inline-block; width: 100%; margin-bottom: 1.25rem; break-inside: avoid; }
.gallery-item img { width: 100%; border-radius: 14px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08); transition: transform 0.3s ease, box-shadow 0.3s ease; }
.gallery-item:hover img { transform: scale(1.03); box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12); }
@media (max-width: 992px) { .photo-gallery { column-count: 2; } }
@media (max-width: 576px) { .photo-gallery { column-count: 1; } }
</style>
@endpush

<div class="page-content-wrapper">

    @php
        $allImages = $item->images ?? collect();
        $sliderImages = $allImages->take(5)->values();
        $galleryImages = $allImages->skip(5)->values(); // Galeri akan menampilkan sisa setelah 5
    @endphp

    {{-- =================== SLIDER FULL-WIDTH =================== --}}
    <section class="hero-wrap">
        @if($sliderImages->isNotEmpty())
            <div class="hero-shell">
                <div id="heroCarousel-{{ $item->id }}" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="6000">
                    <div class="carousel-indicators">
                        @foreach($sliderImages as $key => $image)
                            <button type="button" data-bs-target="#heroCarousel-{{ $item->id }}" data-bs-slide-to="{{ $key }}" class="{{ $loop->first ? 'active' : '' }}" aria-label="Slide {{ $key + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach($sliderImages as $image)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image->path) }}" alt="Photo of {{ $item->name }}" @if(!$loop->first) loading="lazy" @endif>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel-{{ $item->id }}" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel-{{ $item->id }}" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                </div>
            </div>
        @endif
    </section>

    {{-- =================== KONTEN & GALERI (DALAM CONTAINER) =================== --}}
    <section class="content-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-10">
                    <div class="content-card p-4 p-md-5">
                        <h1 class="display-4 text-center mb-4">{{ $item->name }}</h1>
                        <div class="item-description">
                            {!! $item->description !!}
                        </div>
                    </div>
                </div>
            </div>

            @if($galleryImages->isNotEmpty())
                <div class="row justify-content-center mt-5">
                    <div class="col-xl-11 col-lg-12">
                        <h2 class="text-center mb-3">Photo Gallery</h2>
                        <hr class="w-25 mx-auto mb-5">
                        <div class="photo-gallery">
                            @foreach($galleryImages as $image)
                                <div class="gallery-item">
                                    <a href="{{ asset('storage/' . $image->path) }}" data-lightbox="item-gallery" data-title="{{ $item->name }}">
                                        <img src="{{ asset('storage/' . $image->path) }}" alt="Photo of {{ $item->name }}" loading="lazy">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>

@push('scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endpush