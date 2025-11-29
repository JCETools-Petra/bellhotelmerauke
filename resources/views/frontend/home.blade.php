@extends('layouts.frontend')

@section('title', 'Welcome to Bell Hotel Merauke')

@section('content')
@php
    // BENAR: Menggunakan kunci '_path' yang disimpan oleh controller
    $heroBg = isset($settings['hero_image_path'])
              ? asset('storage/' . $settings['hero_image_path'])
              : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2070&auto=format&fit=crop';
@endphp

{{-- GANTI BAGIAN HERO SECTION --}}
<section class="hero-section" style="
    height: {{ $settings['hero_slider_height'] ?? 'auto' }};
    width: {{ $settings['hero_slider_width'] ?? '100%' }};
">

    {{-- 1. Slider sebagai Background (MENGGUNAKAN $heroSliders) --}}
    @if($heroSliders->isNotEmpty())
        <div id="heroSlider" class="carousel slide hero-slider-background" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">
                @foreach($heroSliders as $slider)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $slider->image_path) }}" class="d-block w-100" alt="Hero Background Image">
                    </div>
                @endforeach
            </div>

            @if($heroSliders->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            @endif
        </div>
    @else
        {{-- Fallback jika tidak ada slider, gunakan gambar statis lama --}}
        <div class="hero-slider-background" style="background-image: url('{{ $heroBg }}'); background-size: cover; background-position: center;"></div>
    @endif

    {{-- 2. Konten (Teks & Form) sebagai Overlay --}}
    <div class="container hero-content-overlay {{ $settings['hero_text_align'] ?? 'text-center' }}">
        {{-- ... (Seluruh H1, P, dan Form Booking tetap sama, tidak perlu diubah) ... --}}
        <h1 class="display-3" style="
            font-size: {{ $settings['hero_title_font_size'] ?? '4.5' }}rem;
            font-family: {!! $settings['hero_title_font_family'] ?? 'var(--heading-font)' !!};
        ">
            {{ $settings['hero_title'] ?? 'Bell Hotel Merauke' }}
        </h1>

        <p class="lead" style="
            font-size: {{ $settings['hero_subtitle_font_size'] ?? '1.5' }}rem;
            font-family: {!! $settings['hero_subtitle_font_family'] ?? 'var(--primary-font)' !!};
        ">
            {{ $settings['hero_subtitle'] ?? 'Elegance & Comfort in The Heart of The East.' }}
        </p>

        <div class="hero-booking-form mt-4">
            {{-- ... (Form Booking tetap sama) ... --}}
            <form action="{{ route('rooms.availability') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-lg-3">
                        <label for="checkin" class="form-label">Check-in</label>
                        <input type="text" class="form-control datepicker" id="checkin" name="checkin" placeholder="Select Date" required>
                    </div>
                    <div class="col-lg-3">
                        <label for="checkout" class="form-label">Check-out</label>
                        <input type="text" class="form-control datepicker" id="checkout" name="checkout" placeholder="Select Date" required>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="guests" class="form-label">Guests</label>
                        <input type="number" class="form-control" id="guests" name="guests" value="1" min="1" required>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="rooms" class="form-label">Rooms</label>
                        <input type="number" class="form-control" id="rooms" name="rooms" value="1" min="1" required>
                    </div>
                    <div class="col-lg-2 d-grid">
                        <button type="submit" class="btn btn-custom">Check Availability</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- TAMBAHKAN KEMBALI KODE INI --}}
@if($banners->isNotEmpty())
<section class="banner-section py-5">
    <div class="container">
        <div id="homepageBanner" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner" style="border-radius: 10px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);">
                @foreach($banners as $banner)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        @if($banner->link_url)
                            <a href="{{ $banner->link_url }}" target="_blank" rel="noopener">
                        @endif

                        <img src="{{ asset('storage/' . $banner->image_path) }}" class="d-block w-100" alt="Banner Image">

                        @if($banner->link_url)
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($banners->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#homepageBanner" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#homepageBanner" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            @endif
        </div>
    </div>
</section>
@endif
{{-- AKHIR DARI KODE YANG DITAMBAHKAN KEMBALI --}}

<div class="container" id="featured-content">
    @if(isset($settings['show_about_section']) && $settings['show_about_section'] == '1')
    <section class="about-section {{ $settings['about_text_align'] ?? 'text-center' }}">
        <h2 class="section-title" style="
            font-size: {{ $settings['about_title_font_size'] ?? '2.8' }}rem;
            font-family: {!! $settings['about_title_font_family'] ?? 'var(--heading-font)' !!};
        ">
            {{ $settings['about_title'] ?? 'Discover Our Story' }}
        </h2>
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <p style="
                    font-size: {{ $settings['about_content_font_size'] ?? '1' }}rem;
                    font-family: {!! $settings['about_content_font_family'] ?? 'var(--primary-font)' !!};
                ">
                    {{ $settings['about_content'] ?? 'Lorem ipsum dolor sit amet...' }}
                </p>
            </div>
        </div>
    </section>
    @endif

    {{-- Logika baru untuk menampilkan konten berdasarkan array --}}
    @if(in_array('rooms', $featuredOptions) && $featuredRooms->isNotEmpty())
        <section class="featured-section">
            <h2 class="section-title text-center mb-5">Featured Rooms</h2>
            <div class="row justify-content-center">
    
                @foreach($featuredRooms as $room)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            @if ($room->images->isNotEmpty())
                                {{-- ======================= AWAL PERBAIKAN ======================= --}}
                                {{-- Mengaktifkan auto-play dengan data-bs-ride="carousel" dan interval 4 detik --}}
                                <div id="roomSlider{{ $room->id }}" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                                {{-- ======================== AKHIR PERBAIKAN ======================= --}}
                                    <div class="carousel-inner">
                                        @foreach($room->images as $image)
                                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                <img src="{{ asset('storage/' . $image->path) }}" class="d-block w-100 card-img-top" alt="{{ $room->name }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($room->images->count() > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#roomSlider{{ $room->id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#roomSlider{{ $room->id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <img src="https://via.placeholder.com/400x250" class="card-img-top" alt="{{ $room->name }}">
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title h3">{{ $room->name }}</h5>
                                <p class="card-price mb-3">Rp {{ number_format($room->price, 0, ',', '.') }} / night</p>
                                <p class="card-text">{{ Str::limit($room->description, 100) }}</p>
                                <a href="{{ route('rooms.show', $room->slug) }}" class="btn btn-custom mt-auto">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
                
            </div>
        </section>
    @endif


    @if(in_array('mice', $featuredOptions) && $featuredMice->isNotEmpty())
        <section class="featured-section">
            <h2 class="section-title text-center mb-5">Featured Event Spaces</h2>
            <div class="row justify-content-center">
                @foreach($featuredMice as $mice)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            {{-- STRUKTUR SLIDER DITERAPKAN DI SINI --}}
                            @if ($mice->images->isNotEmpty())
                                <div id="miceSlider{{ $mice->id }}" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4500">
                                    <div class="carousel-inner">
                                        @foreach($mice->images as $image)
                                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                <img src="{{ asset('storage/' . $image->path) }}" class="d-block w-100 card-img-top" alt="{{ $mice->name }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($mice->images->count() > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#miceSlider{{ $mice->id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#miceSlider{{ $mice->id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <img src="https://via.placeholder.com/400x250" class="card-img-top" alt="{{ $mice->name }}">
                            @endif
                            
                            {{-- Bagian deskripsi, dll. tetap sama --}}
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title h3">{{ $mice->name }}</h5>
                                <p class="card-price mb-3">Capacity up to <strong>{{ $mice->capacity_theatre ?? $mice->capacity_classroom }} persons</strong></p>
                                <p class="card-text">{{ Str::limit($mice->description, 100) }}</p>
                                <a href="{{ route('mice.show', $mice->slug) }}" class="btn btn-custom mt-auto">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- GANTI SELURUH BAGIAN @if(in_array('restaurants', ...)) DENGAN KODE DI BAWAH INI --}}

   @if(in_array('restaurants', $featuredOptions) && $featuredRestaurantImages->isNotEmpty())
        <section id="restaurants" class="py-5">
            <div class="container">
                <h2 class="section-title text-center mb-5">Our Restaurants</h2>
            </div>

            {{-- Struktur HTML untuk Slider --}}
            <div class="slider">
                <div class="slide-track">
                    
                    {{-- Loop pertama untuk menampilkan set gambar asli --}}
                    @foreach($featuredRestaurantImages as $image)
                    <div class="slide">
                        {{-- Pastikan relasi restaurant ada untuk menghindari error --}}
                        @if($image->restaurant)
                        <a href="{{ route('restaurants.show', $image->restaurant->slug) }}">
                            <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->restaurant->name }}">
                        </a>
                        @endif
                    </div>
                    @endforeach

                    {{-- Loop kedua untuk duplikasi gambar (kunci dari efek continuous) --}}
                    @foreach($featuredRestaurantImages as $image)
                    <div class="slide">
                        @if($image->restaurant)
                        <a href="{{ route('restaurants.show', $image->restaurant->slug) }}">
                            <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->restaurant->name }}">
                        </a>
                        @endif
                    </div>
                    @endforeach

                </div>
            </div>
        </section>
    @endif
</div>
@endsection

{{-- TAMBAHKAN BLOK SCRIPT INI DI BAGIAN PALING BAWAH FILE --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof flatpickr === "undefined") {
        console.error("Flatpickr is not loaded.");
        return;
    }
    let pricesCache = {};
    async function getPricesForMonth(year, month) {
        const cacheKey = `${year}-${month}`;
        if (pricesCache[cacheKey]) return pricesCache[cacheKey];
        try {
            const response = await fetch(`{{ route('api.room-prices.month') }}?year=${year}&month=${month + 1}`);
            if (!response.ok) return {};
            const data = await response.json();
            pricesCache[cacheKey] = data;
            return data;
        } catch (error) {
            console.error('Error fetching monthly prices:', error);
            return {};
        }
    }
    const fpConfig = {
        dateFormat: "d-m-Y",
        minDate: "today",
        onReady: async function(selectedDates, dateStr, instance) {
            const prices = await getPricesForMonth(instance.currentYear, instance.currentMonth);
            instance.prices = prices;
            instance.redraw();
        },
        onMonthChange: async function(selectedDates, dateStr, instance) {
            const prices = await getPricesForMonth(instance.currentYear, instance.currentMonth);
            instance.prices = prices;
            instance.redraw();
        },
        onDayCreate: function(dObj, dStr, fp, dayElem) {
            const date = dayElem.dateObj;
            const dateString = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
            if (fp.input.id === 'checkin' && fp.prices && fp.prices[dateString]) {
                const priceInfo = fp.prices[dateString];
                const priceElement = document.createElement('span');
                priceElement.className = 'day-price';
                priceElement.textContent = `${parseInt(priceInfo.price / 1000)}K`;
                dayElem.appendChild(priceElement);
            }
        }
    };
    flatpickr(".datepicker", fpConfig);
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const slideTrack = document.querySelector('.slider .slide-track');

    if (slideTrack) {
        const slideCount = slideTrack.children.length / 2;
        if (slideCount > 0) {
            const slideWidth = 400; // Lebar satu slide dari CSS
            const trackWidth = slideWidth * slideCount * 2;
            const animationDistance = -(slideWidth * slideCount);
            const animationDuration = slideCount * 5; // Durasi dinamis

            const styleElement = document.createElement('style');
            styleElement.innerHTML = `
                .slide-track {
                    width: ${trackWidth}px;
                    animation: scroll-dynamic ${animationDuration}s linear infinite;
                }
                @keyframes scroll-dynamic {
                    0% { transform: translateX(0); }
                    100% { transform: translateX(${animationDistance}px); }
                }
            `;
            document.head.appendChild(styleElement);
        }
    }
});
</script>
@endpush