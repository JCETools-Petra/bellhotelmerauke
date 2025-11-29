@extends('layouts.frontend')

@section('seo_title', $room->seo_title ?: $room->name)
@section('meta_description', $room->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($room->description), 160))

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
/* =============== GLOBAL =============== */
body, .page-content-wrapper { background:#fff !important; color:#333; }

/* =============== LAYOUT UTAMA (Slider + Sidebar sejajar) =============== */
.hero-sidebar-wrap { padding: 24px 0; }

/* >>> Ketinggian slider kita set via variabel agar gampang diubah <<< */
.hero-shell{
  --hero-height: clamp(520px, 68vh, 820px);  /* MUAT di samping sidebar */
  width: 100%;
  height: var(--hero-height);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 10px 28px rgba(0,0,0,.08);
  background: #f8f9fa;
  position: relative;
}

.hero-shell .carousel,
.hero-shell .carousel-inner,
.hero-shell .carousel-item{
  position: absolute; inset:0; width:100%; height:100%;
}

.hero-shell .carousel-item img{
  width:100%; height:100%;
  object-fit: cover; object-position: center;
  background:#fff;
}

/* Kontrol & indikator slider */
.carousel-control-prev-icon,
.carousel-control-next-icon{
  filter: invert(1) drop-shadow(0 2px 4px rgba(0,0,0,.3));
}
.carousel-indicators{ margin-bottom: 1rem; }
.carousel-indicators [data-bs-target]{
  width: 12px; height: 12px; border-radius: 50%;
  background: rgba(255,255,255,.55); border:0;
}
.carousel-indicators .active{ background:#fff; }

/* Kartu sidebar (Room Details) */
.sidebar-card{
  background:#fff; border:1px solid rgba(0,0,0,.06);
  border-radius:14px; box-shadow:0 8px 20px rgba(0,0,0,.06);
}
.sidebar-card .bi{ color: var(--color-gold, #c9a227); }

/* Sticky supaya tetap terlihat saat scroll panjang */
@media (min-width: 992px){
  .sidebar-sticky{ position: sticky; top: 24px; }
}

/* Deskripsi kamar di bawahnya */
.item-description{ font-size: 1.1rem; line-height: 1.8; color:#555; text-align: justify; }

/* Placeholder jika tak ada gambar */
.placeholder-hero{
  height: clamp(420px, 60vh, 640px);
  display:grid; place-items:center;
  color:#777; border-radius:16px; background:#f2f2f2;
}

/* Mobile tweaks (stack) */
@media (max-width: 991.98px){
  .hero-shell{ --hero-height: clamp(380px, 55vh, 640px); }
}
</style>
@endpush

@section('content')
<div class="page-content-wrapper">

  {{-- =================== BARIS: SLIDER (KIRI) + ROOM DETAILS (KANAN) =================== --}}
  <section class="hero-sidebar-wrap">
    <div class="container">
      <div class="row g-4 align-items-start">
        {{-- KIRI: SLIDER --}}
        <div class="col-lg-8">
          @if($room->images->isNotEmpty())
            <div class="hero-shell">
              <div id="roomHeroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
                <div class="carousel-indicators">
                  @foreach($room->images as $key => $image)
                    <button type="button"
                            data-bs-target="#roomHeroCarousel"
                            data-bs-slide-to="{{ $key }}"
                            class="{{ $loop->first ? 'active' : '' }}"
                            aria-label="Slide {{ $key + 1 }}"></button>
                  @endforeach
                </div>
                <div class="carousel-inner">
                  @foreach($room->images as $image)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                      <img src="{{ asset('storage/' . $image->path) }}"
                           alt="Photo of {{ $room->name }}"
                           @if(!$loop->first) loading="lazy" @endif>
                    </div>
                  @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#roomHeroCarousel" data-bs-slide="prev" aria-label="Previous">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#roomHeroCarousel" data-bs-slide="next" aria-label="Next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
              </div>
            </div>
          @else
            <div class="placeholder-hero text-center">
              <div>
                <h2 class="mb-2">{{ $room->name }}</h2>
                <p>No images available yet.</p>
              </div>
            </div>
          @endif
        </div>

        {{-- KANAN: ROOM DETAILS (SEJAJAR DENGAN SLIDER) --}}
        <div class="col-lg-4">
          <div class="sidebar-card sidebar-sticky">
            <div class="card-body p-4">
              <h4 class="card-title h2 mb-3">Room Details</h4>
              <hr>

              <p class="card-price mb-4 price-for-room-{{ $room->id }}">
                <strong>Price:</strong>
                Rp {{ number_format($room->price, 0, ',', '.') }} / night
              </p>

              <h5 class="mt-2 mb-3">Facilities</h5>
              <ul class="list-unstyled mb-4">
                @php
                  // Jika ingin meng-overwrite dari contoh yang kamu tulis manual, gunakan daftar berikut:
                  // $facilities = ["Wifi","Parking","AC","Shower","Air Panas","Bathroom amenities","Free antar jemput Bandara Mopa Merauke"];
                  // Kalau ingin tetap ambil dari $room->facilities (baris-baris), pakai blok di bawah ini.
                @endphp
                @if(!empty($room->facilities))
                  @foreach(explode("\n", $room->facilities) as $facility)
                    @php $facility = trim($facility); @endphp
                    @if($facility !== '')
                      <li class="mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                             fill="currentColor" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                          <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                        {{ $facility }}
                      </li>
                    @endif
                  @endforeach
                @else
                  {{-- Fallback contoh dari permintaan kamu --}}
                  @foreach(["Wifi","Parking","AC","Shower","Air Panas","Bathroom amenities","Free antar jemput Bandara Mopa Merauke"] as $facility)
                    <li class="mb-2">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                           fill="currentColor" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                      </svg>
                      {{ $facility }}
                    </li>
                  @endforeach
                @endif
              </ul>

              <button type="button" class="btn btn-custom w-100" data-bs-toggle="modal" data-bs-target="#bookingModal">
                Book Now
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- Judul + Deskripsi di bawah baris slider/sidebar --}}
      <div class="row mt-5">
        <div class="col-lg-10">
          <h1 class="display-5">{{ $room->name }}</h1>
          <hr style="border-color: var(--color-gold); border-width: 2px; width: 100px; opacity: 1;">
          <div class="item-description mt-3">
            {!! nl2br(e($room->description)) !!}
          </div>
        </div>
      </div>
    </div>
  </section>

</div>

{{-- =================== BOOKING MODAL =================== --}}
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bookingModalLabel">Booking Form: {{ $room->name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('bookings.store') }}" method="POST">
          @csrf
          <input type="hidden" name="room_id" value="{{ $room->id }}">
          <input type="hidden" id="room_price_modal_{{ $room->id }}" value="{{ $room->price }}">

          @if(request('checkin') && request('checkout'))
            <input type="hidden" id="modal_checkin"  name="checkin"  value="{{ request('checkin') }}">
            <input type="hidden" id="modal_checkout" name="checkout" value="{{ request('checkout') }}">
            <input type="hidden" id="modal_num_rooms" name="num_rooms" value="{{ request('rooms', 1) }}">
            <div class="alert alert-light border">
              <h6 class="alert-heading">Your Selection</h6>
              <p class="mb-1"><strong>Check-in:</strong> {{ request('checkin') }}</p>
              <p class="mb-1"><strong>Check-out:</strong> {{ request('checkout') }}</p>
              <p class="mb-0"><strong>Rooms:</strong> {{ request('rooms', 1) }}</p>
            </div>
            <hr>
          @else
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label for="modal_checkin" class="form-label">Check-in Date</label>
                <input type="text" class="form-control datepicker" id="modal_checkin" name="checkin" placeholder="Select Date" required>
              </div>
              <div class="col-md-6">
                <label for="modal_checkout" class="form-label">Check-out Date</label>
                <input type="text" class="form-control datepicker" id="modal_checkout" name="checkout" placeholder="Select Date" required>
              </div>
              <div class="col-md-12">
                <label for="modal_num_rooms" class="form-label">Number of Rooms</label>
                <input type="number" class="form-control" id="modal_num_rooms" name="num_rooms" value="1" min="1" required>
              </div>
            </div>
            <hr>
          @endif

          <h6 class="mt-4">Guest Information</h6>
          <div class="mb-3">
            <label for="guest_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="guest_name" name="guest_name" required>
          </div>
          <div class="mb-3">
            <label for="guest_email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="guest_email" name="guest_email" required>
          </div>
          <div class="mb-3">
            <label for="guest_phone" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="guest_phone" name="guest_phone" required>
          </div>

          <div id="price-calculation-modal" class="mt-4 p-3 bg-light rounded" style="display:none;">
            <h6 class="mb-0">Estimasi Total Biaya: <span id="total-price-modal" class="text-primary fw-bold">Rp 0</span></h6>
          </div>

          <div class="d-grid mt-4">
            @if(settings('booking_method', 'direct') == 'direct')
              <button type="submit" class="btn btn-custom">Lanjutkan ke Pembayaran</button>
            @else
              <button type="submit" class="btn btn-custom">Kirim Permintaan Booking</button>
            @endif
          </div>

          @if(settings('booking_method', 'direct') == 'direct')
            <p class="text-muted small mt-3">*Anda akan melanjutkan ke halaman pembayaran setelah mengisi formulir ini.</p>
          @else
            <p class="text-muted small mt-3">*Admin kami akan segera menghubungi Anda melalui WhatsApp untuk konfirmasi dan pembayaran.</p>
          @endif
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
@php
  $amenities = [];
  if (!empty($room->facilities)) {
      $facilitiesList = explode("\n", $room->facilities);
      foreach ($facilitiesList as $facility) {
          if (trim($facility) !== '') {
              $amenities[] = ['@type' => 'LocationFeatureSpecification', 'name' => trim($facility)];
          }
      }
  }
  $ld = [
      '@context' => 'https://schema.org',
      '@type'    => 'HotelRoom',
      'name'     => $room->name,
      'description' => \Illuminate\Support\Str::limit(strip_tags($room->description), 250),
      'offers'      => ['@type' => 'Offer', 'price' => (string) $room->price, 'priceCurrency' => 'IDR'],
  ];
  if ($room->images->isNotEmpty()) {
      $ld['image'] = asset('storage/' . $room->images->first()->path);
  }
  if (!empty($amenities)) {
      $ld['amenityFeature'] = $amenities;
  }
@endphp
<script type="application/ld+json">{!! json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Price estimator
  (function initPriceCalculator(){
    const checkinInput  = document.getElementById('modal_checkin');
    const checkoutInput = document.getElementById('modal_checkout');
    const numRoomsInput = document.getElementById('modal_num_rooms');
    const roomPriceInput= document.getElementById('room_price_modal_{{ $room->id }}');
    const priceBox      = document.getElementById('price-calculation-modal');
    const totalEl       = document.getElementById('total-price-modal');

    function update(){
      if(!checkinInput || !checkoutInput || !numRoomsInput || !roomPriceInput) return;
      const ci = checkinInput.value, co = checkoutInput.value;
      const n  = parseInt(numRoomsInput.value) || 0;
      const p  = parseFloat(roomPriceInput.value);
      if(ci && co && n>0 && p){
        const d1 = new Date(ci.split('-').reverse().join('-'));
        const d2 = new Date(co.split('-').reverse().join('-'));
        if(d2 > d1){
          const ms = d2 - d1;
          let days = Math.ceil(ms / (1000*3600*24));
          if (days < 1) days = 1;
          const total = p * n * days;
          totalEl.textContent = new Intl.NumberFormat('id-ID', {style:'currency', currency:'IDR', minimumFractionDigits:0}).format(total);
          priceBox.style.display = 'block';
          return;
        }
      }
      priceBox.style.display = 'none';
    }

    [checkinInput, checkoutInput].forEach(el => el && el.addEventListener('change', update));
    numRoomsInput && numRoomsInput.addEventListener('input', update);
    roomPriceInput && roomPriceInput.addEventListener('input', update);
    update();
  })();
});
</script>
@endpush
