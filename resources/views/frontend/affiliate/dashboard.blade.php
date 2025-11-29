@extends('layouts.frontend')

@section('seo_title', 'Affiliate Dashboard')

@section('content')
<div class="page-content-wrapper">
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <h1 class="display-5">Affiliate Dashboard</h1>
            <div class="ms-auto">
                <a href="{{ route('affiliate.bookings.create') }}" class="btn btn-primary mb-2 mb-md-0">Create New Booking</a>
                <a href="{{ route('affiliate.mice-kit.index') }}" class="btn btn-info mb-2 mb-md-0">Digital MICE Kit</a>
            </div>
        </div>

        {{-- Affiliate Link --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Your Unique Referral Link</h5>
                <p class="text-muted">Bagikan link ini untuk mulai mendapatkan komisi.</p>
                <div class="input-group">
                    <input type="text" class="form-control" id="referralLink" value="{{ route('home') }}/?ref={{ $affiliate->referral_code }}" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="copyLink()">Copy</button>
                </div>
            </div>
        </div>

        {{-- Statistics --}}
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Total Clicks</h6>
                        <p class="card-text display-4">{{ $totalClicks }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Successful Bookings</h6>
                        <p class="card-text display-4">{{ $totalBookings }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Unpaid Commissions</h6>
                        <p class="card-text display-4">Rp {{ number_format($totalCommissions, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mt-5 mb-4">Commission History</h3>
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="py-3 px-4">Detail</th>
                                <th scope="col" class="py-3 px-4">Commission</th>
                                <th scope="col" class="py-3 px-4">Status</th>
                                <th scope="col" class="py-3 px-4">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($commissions as $commission)
                                <tr>
                                    <td class="py-3 px-4">
                                        @if ($commission->booking_id)
                                            {{-- Tampilan untuk komisi dari booking kamar --}}
                                            <div class="fw-bold text-dark">
                                                Booking ID #{{ $commission->booking_id }}
                                            </div>
                                            @if($commission->booking && $commission->booking->room)
                                                <small class="text-muted">{{ $commission->booking->room->name }}</small>
                                            @endif
                                        @else
                                            {{-- Tampilan untuk komisi MICE --}}
                                            @php
                                                $notesLines = explode("\n", $commission->notes ?? '');
                                                $eventName = str_replace('MICE Event: ', '', $notesLines[0] ?? 'MICE Event');
                                                $roomName = str_replace('Room: ', '', $notesLines[1] ?? '');
                                            @endphp
                                            <div class="fw-bold text-dark">
                                                {{ $eventName }}
                                            </div>
                                            <small class="text-muted">{{ $roomName }}</small>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 fw-bold text-success">
                                        Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @if ($commission->status == 'paid')
                                            <span class="badge bg-success-soft text-success">
                                                {{ ucfirst($commission->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning-soft text-warning">
                                                {{ ucfirst($commission->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-muted">
                                        {{ $commission->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No commissions yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $commissions->links() }}
            </div>
    </div>
</div>

<script>
    function copyLink() {
        var copyText = document.getElementById("referralLink");
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        document.execCommand("copy");
        alert("Link copied to clipboard: " + copyText.value);
    }
</script>
@endsection