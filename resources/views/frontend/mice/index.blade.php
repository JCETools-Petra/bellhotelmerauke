@extends('layouts.frontend')

@section('title', 'MICE & Events')

@section('content')
<div class="page-content-wrapper">
    <div class="container">
        <h1 class="section-title text-center mb-5">MICE (Meetings, Incentives, Conferences, Exhibitions)</h1>
        <div class="row">
            @forelse($miceRooms as $mice)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                         <img src="{{ $mice->images->first() ? asset('storage/' . $mice->images->first()->path) : 'https://via.placeholder.com/400x250' }}" class="card-img-top" alt="{{ $mice->name }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title h3">{{ $mice->name }}</h5>
                            <p class="card-text">Capacity up to <strong>{{ $mice->capacity_theatre ?? $mice->capacity_classroom }} persons</strong></p>
                            <a href="{{ route('mice.show', $mice->slug) }}" class="btn btn-custom mt-auto">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col">
                    <p class="text-center fs-4">No MICE rooms available.</p>
                </div>
            @endforelse
        </div>
        <div class="d-flex justify-content-center">
            {{ $miceRooms->links() }}
        </div>
    </div>
</div>
@endsection