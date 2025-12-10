@extends('layouts.frontend')

 

@section('title', 'Recreation Areas')

 

@push('styles')

<style>

    .recreation-card {

        transition: transform 0.3s ease, box-shadow 0.3s ease;

        border: none;

        border-radius: 0.5rem;

        background-color: #fff;

        overflow: hidden;

    }

    .recreation-card:hover {

        transform: translateY(-10px);

        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;

    }

    .recreation-card .card-img-container {

        overflow: hidden;

        height: 250px;

    }

    .recreation-card .card-img-top {

        transition: transform 0.3s ease;

        width: 100%;

        height: 100%;

        object-fit: cover;

    }

    .recreation-card:hover .card-img-top {

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

    {{-- Header Section --}}

    <div class="page-title-area" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 80px 0;">

        <div class="container">

            <div class="page-title-content text-center text-white">

                <h1 class="display-3 fw-bold">Recreation Areas</h1>

                <p class="lead">Explore our facilities and enjoy your stay</p>

            </div>

        </div>

    </div>

 

    {{-- Recreation Areas List --}}

    <div class="page-content-wrapper py-5" style="background-color: #f8f9fa;">

        <div class="container">

            @if($recreationAreas->isEmpty())

                <div class="row">

                    <div class="col">

                        <div class="alert alert-info text-center py-5">

                            <h4 class="alert-heading">No Recreation Areas Available</h4>

                            <p>Information about our recreation areas is currently unavailable. Please check back later.</p>

                        </div>

                    </div>

                </div>

            @else

                <div class="row g-4">

                    @foreach($recreationAreas as $area)

                        <div class="col-md-6 col-lg-4 mb-4">

                            <div class="card h-100 shadow-sm recreation-card">

                                <a href="{{ route('recreation-areas.show', $area->slug) }}" class="card-img-container">

                                    @if ($area->images->isNotEmpty())

                                        <img src="{{ asset('storage/' . $area->images->first()->path) }}"

                                             class="card-img-top"

                                             alt="{{ $area->name }}">

                                    @else

                                        <img src="https://via.placeholder.com/400x250?text=No+Image"

                                             class="card-img-top"

                                             alt="No Image Available">

                                    @endif

                                </a>

                                <div class="card-body d-flex flex-column">

                                    <h5 class="card-title h4 mb-3">

                                        <a href="{{ route('recreation-areas.show', $area->slug) }}">{{ $area->name }}</a>

                                    </h5>

                                    <p class="card-text text-muted flex-grow-1">

                                        {{ Str::limit($area->description, 120) }}

                                    </p>

                                    <a href="{{ route('recreation-areas.show', $area->slug) }}"

                                       class="btn btn-outline-primary mt-3">

                                        View Details

                                    </a>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>

    </div>

@endsection