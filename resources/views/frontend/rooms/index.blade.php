@extends('layouts.frontend')

@section('title', 'Our Rooms')

@section('content')
<div class="page-content-wrapper">
    <div class="container">
        <h1 class="section-title text-center mb-5">Our Rooms</h1>
        <div class="row">
            @forelse($rooms as $room)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ $room->images->first() ? asset('storage/' . $room->images->first()->path) : 'https://via.placeholder.com/400x250' }}" class="card-img-top" alt="{{ $room->name }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title h3">{{ $room->name }}</h5>
                            <p class="card-price mb-3">Rp {{ number_format($room->price, 0, ',', '.') }} / night</p>
                            <p class="card-text">{{ Str::limit($room->description, 100) }}</p>
                            <a href="{{ route('rooms.show', $room->slug) }}" class="btn btn-custom mt-auto">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col">
                    <p class="text-center fs-4">No rooms available.</p>
                </div>
            @endforelse
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $rooms->links() }}
        </div>
    </div>
</div>
@endsection