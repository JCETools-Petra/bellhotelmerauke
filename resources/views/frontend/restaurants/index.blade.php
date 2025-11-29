@extends('layouts.frontend')

@section('title', 'Our Restaurants')

{{-- Menambahkan CSS kustom untuk efek hover yang elegan --}}
@push('styles')
<style>
    .restaurant-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 0.5rem;
        background-color: #fff;
    }
    .restaurant-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
    }
    .restaurant-card .card-img-container {
        overflow: hidden;
        border-radius: 0.5rem 0.5rem 0 0;
    }
    .restaurant-card .card-img-top {
        transition: transform 0.3s ease;
    }
    .restaurant-card:hover .card-img-top {
        transform: scale(1.05);
    }
    .card-title a {
        color: inherit;
        text-decoration: none;
    }
    .card-title a:hover {
        color: var(--bs-primary);
    }
</style>
@endpush

@section('content')
    {{-- Bagian Header Halaman --}}
    <div class="page-title-area" style="background-image: url('{{ $settings['restaurant_header_image'] ?? '' }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="page-title-content">
                <h1 class="display-3">Our Restaurants</h1>
                <p class="lead">Discover culinary delights and unwind at our exquisite venues.</p>
            </div>
        </div>
    </div>

    {{-- Bagian Daftar Restoran --}}
    <div class="page-content-wrapper py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="row g-4">
                {{-- Menggunakan @forelse dengan variabel $restaurants (plural) --}}
                @forelse($restaurants as $restaurant)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm restaurant-card">
                            <a href="{{ route('restaurants.show', $restaurant->slug) }}" class="card-img-container">
                                @if ($restaurant->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $restaurant->images->first()->path) }}" class="card-img-top" alt="{{ $restaurant->name }}" style="height: 250px; object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/400x250?text=No+Image" class="card-img-top" alt="No Image Available" style="height: 250px; object-fit: cover;">
                                @endif
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title h4">
                                    <a href="{{ route('restaurants.show', $restaurant->slug) }}">{{ $restaurant->name }}</a>
                                </h5>
                                <p class="card-text text-muted flex-grow-1">{{ Str::limit($restaurant->description, 120) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col">
                        <div class="alert alert-info text-center py-5">
                            <h4 class="alert-heading">No Venues Available</h4>
                            <p>Information about our restaurants and bars is currently unavailable. Please check back later.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($restaurants->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $restaurants->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection