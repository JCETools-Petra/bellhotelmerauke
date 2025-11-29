@extends('layouts.frontend')

@section('title', $restaurant->name)

@push('styles')
<style>
/* ================= GLOBAL ================= */
body, .page-content-wrapper {
    background: #fff !important;
    color: #333;
}
/* ================= HERO / SLIDER ================= */
.hero-wrap {
    padding: 0;
    background: #fff;
}
.hero-shell {
    max-width: 1200px;
    width: 100%;
    margin: 0 auto;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    background: #f8f9fa;
}
.hero-carousel .carousel-item {
    position: relative;
    height: 70vh; /* Tinggi slider adalah 70% dari tinggi layar */
    min-height: 450px;
    max-height: 650px;
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
.carousel-control-prev-icon,
.carousel-control-next-icon {
    filter: invert(1) drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
}
.carousel-indicators [data-bs-target] {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.4);
    border: none;
}
.carousel-indicators .active {
    background-color: #fff;
}
/* ================= CONTENT ================= */
.content-card {
    background: #fff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 18px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
}
.item-description {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #555;
}
/* ================= GALLERY ================= */
.photo-gallery {
    column-count: 3;
    column-gap: 1.25rem;
}
.photo-gallery .gallery-item {
    display: inline-block;
    width: 100%;
    margin-bottom: 1.25rem;
    break-inside: avoid;
}
.gallery-item img {
    width: 100%;
    border-radius: 14px;
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.gallery-item:hover img {
    transform: scale(1.03);
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.12);
}
@media (max-width: 992px) { .photo-gallery { column-count: 2; } }
@media (max-width: 576px) { .photo-gallery { column-count: 1; } }
/* Placeholder */
.placeholder-hero {
    height: 70vh;
    min-height: 450px;
    display: grid;
    place-items: center;
    color: #777;
    border-radius: 20px;
    background: #f2f2f2;
}
</style>
@endpush

@section('content')
<div class="page-content-wrapper">

    @php
        $allImages = $restaurant->images ?? collect();
        $sliderImages = $allImages->take(5)->values();
        $galleryImages = $allImages->skip(5)->values();
    @endphp

    <section class="hero-wrap">
        <div class="container py-4">
            @if($sliderImages->isNotEmpty())
                <div class="hero-shell">
                    <div id="restaurantHeroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="6000">
                        <div class="carousel-indicators">
                            @foreach($sliderImages as $key => $image)
                                <button type="button" data-bs-target="#restaurantHeroCarousel" data-bs-slide-to="{{ $key }}" class="{{ $loop->first ? 'active' : '' }}" aria-label="Slide {{ $key + 1 }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach($sliderImages as $image)
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->path) }}" alt="Photo of {{ $restaurant->name }}" @if(!$loop->first) loading="lazy" @endif>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#restaurantHeroCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                        <button class="carousel-control-next" type="button" data-bs-target="#restaurantHeroCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                    </div>
                </div>
            @else
                <div class="placeholder-hero text-center">
                    <div>
                        <h2 class="mb-2">{{ $restaurant->name }}</h2>
                        <p>No images available yet.</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-xl-9 col-lg-10">
                    <div class="content-card p-4 p-md-5">
                        <h1 class="display-4 text-center mb-4">{{ $restaurant->name }}</h1>
                        <div class="item-description">
                            {!! $restaurant->description !!}
                        </div>
                    </div>
                </div>
            </div>

            @if($galleryImages->isNotEmpty())
                <div class="row justify-content-center">
                    <div class="col-xl-11 col-lg-12">
                        <h2 class="text-center mb-4">Photo Gallery</h2>
                        <hr class="w-25 mx-auto mb-5">
                        <div class="photo-gallery">
                            @foreach($galleryImages as $image)
                                <div class="gallery-item">
                                    <a href="{{ asset('storage/' . $image->path) }}" data-lightbox="restaurant-gallery" data-title="{{ $restaurant->name }}">
                                        <img src="{{ asset('storage/' . $image->path) }}" alt="Photo of {{ $restaurant->name }}" loading="lazy">
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
@endsection

@push('scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endpush