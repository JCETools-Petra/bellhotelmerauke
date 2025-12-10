@extends('layouts.frontend')

@section('title', 'Available Rooms')

@section('content')
<div class="page-content-wrapper">
    <div class="container">
        <div class="text-center mb-5">
        <h1 class="section-title">Available Rooms</h1>
        @if(isset($searchParams['checkin']) && isset($searchParams['checkout']))
        <p class="lead">
            Showing results for check-in on <strong>{{ $searchParams['checkin'] }}</strong> and check-out on <strong>{{ $searchParams['checkout'] }}</strong>.
        </p>
        @endif
    </div>

    <div class="row">
        @forelse($rooms as $room)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <img src="{{ $room->images->first() ? asset('storage/' . $room->images->first()->path) : 'https://via.placeholder.com/400x250' }}" class="card-img-top" alt="{{ $room->name }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title h3">{{ $room->name }}</h5>
                        
                        {{-- HARGA DENGAN ID UNIK --}}
                        <p class="card-price mb-3 price-for-room-{{ $room->id }}">
                            Rp {{ number_format($room->price, 0, ',', '.') }} / night
                        </p>
                        
                        <p class="card-text">{{ Str::limit($room->description, 100) }}</p>

                        <a href="{{ route('rooms.show', ['slug' => $room->slug, 'checkin' => $searchParams['checkin'] ?? '', 'checkout' => $searchParams['checkout'] ?? '', 'guests' => $searchParams['guests'] ?? 1, 'rooms' => $searchParams['rooms'] ?? 1]) }}" class="btn btn-custom mt-auto">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col">
                <div class="alert alert-warning text-center">
                    <h4>No Rooms Available</h4>
                    <p>We're sorry, but no rooms are available for the selected criteria. Please try different dates.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection