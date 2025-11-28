@extends('layouts.frontend')

@section('title', 'Booking Payment')

@section('content')
<div class="page-content-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h1 class="h3">Selesaikan Booking Anda</h1>
                    </div>
                    <div class="card-body text-center">
                        <p class="mt-4">
                            Silakan klik tombol di bawah ini untuk melanjutkan ke pembayaran.
                        </p>
                        <div class="text-center mt-4">
                            <button id="pay-button" class="btn btn-custom btn-lg">Bayar Sekarang</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Script untuk Midtrans tetap sama --}}
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $booking->snap_token }}', {
            onSuccess: function(result){
                window.location.href = '{{ route('booking.success', ['booking' => $booking->access_token]) }}'
            },
            onPending: function(result){
                alert("waiting for your payment!"); console.log(result);
            },
            onError: function(result){
                alert("payment failed!"); console.log(result);
            },
            onClose: function(){
                alert('you closed the popup without finishing the payment');
            }
        });
    };
</script>
@endpush