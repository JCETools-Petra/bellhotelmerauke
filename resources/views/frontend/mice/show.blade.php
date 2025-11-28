@extends('layouts.frontend')

@section('seo_title', $mice->seo_title ?: $mice->name)
@section('meta_description', $mice->meta_description ?: Str::limit(strip_tags($mice->description), 160))

@push('styles')
<style>
/* ================= GLOBAL ================= */
body, .page-content-wrapper { background:#fff !important; color:#333; }

/* ================= HERO / SLIDER ================= */
.hero-sidebar-wrap { padding: 24px 0; }

.hero-shell{
  --hero-height: clamp(520px, 68vh, 820px);
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
.carousel-control-next-icon{ filter: invert(1) drop-shadow(0 2px 4px rgba(0,0,0,.3)); }
.carousel-indicators{ margin-bottom: 1rem; }
.carousel-indicators [data-bs-target]{
  width: 12px; height: 12px; border-radius: 50%;
  background: rgba(255,255,255,.55); border:0;
}
.carousel-indicators .active{ background:#fff; }

/* Sidebar Inquiry */
.sidebar-card{
  background:#fff; border:1px solid rgba(0,0,0,.06);
  border-radius:14px; box-shadow:0 8px 20px rgba(0,0,0,.06);
}
.sidebar-card .bi{ color: var(--color-gold, #c9a227); }

@media (min-width: 992px){
  .sidebar-sticky{ position: sticky; top: 24px; }
}

/* Deskripsi */
.item-description{ font-size:1.1rem; line-height:1.8; color:#555; text-align: justify; }

/* Placeholder */
.placeholder-hero{
  height: clamp(420px, 60vh, 640px);
  display:grid; place-items:center;
  color:#777; border-radius:16px; background:#f2f2f2;
}

/* Mobile */
@media (max-width: 991.98px){
  .hero-shell{ --hero-height: clamp(380px, 55vh, 640px); }
}
</style>
@endpush

@section('content')
<div class="page-content-wrapper">

  {{-- =================== BARIS: SLIDER (KIRI) + INQUIRY (KANAN) =================== --}}
  <section class="hero-sidebar-wrap">
    <div class="container">
      <div class="row g-4 align-items-start">
        {{-- KIRI: SLIDER --}}
        <div class="col-lg-8">
          @if($mice->images->isNotEmpty())
            <div class="hero-shell">
              <div id="miceHeroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
                <div class="carousel-indicators">
                  @foreach($mice->images as $key => $image)
                    <button type="button"
                            data-bs-target="#miceHeroCarousel"
                            data-bs-slide-to="{{ $key }}"
                            class="{{ $loop->first ? 'active' : '' }}"
                            aria-label="Slide {{ $key + 1 }}"></button>
                  @endforeach
                </div>
                <div class="carousel-inner">
                  @foreach($mice->images as $image)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                      <img src="{{ asset('storage/' . $image->path) }}"
                           alt="Photo of {{ $mice->name }}"
                           @if(!$loop->first) loading="lazy" @endif>
                    </div>
                  @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#miceHeroCarousel" data-bs-slide="prev" aria-label="Previous">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#miceHeroCarousel" data-bs-slide="next" aria-label="Next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
              </div>
            </div>
          @else
            <div class="placeholder-hero text-center">
              <div><h2 class="mb-2">{{ $mice->name }}</h2><p>No images available yet.</p></div>
            </div>
          @endif
        </div>

        {{-- KANAN: Inquiry & Booking --}}
        <div class="col-lg-4">
          <div class="sidebar-card sidebar-sticky">
            <div class="card-body p-4">
              <h4 class="card-title h2">Inquiry & Booking</h4>
              <hr>
              <p class="mb-4">
                Harga untuk event dan meeting bersifat fleksibel.
                Silakan hubungi tim sales kami untuk mendapatkan penawaran terbaik sesuai kebutuhan Anda.
              </p>

              <h5 class="mt-4 mb-3">Fasilitas Unggulan</h5>
              <ul class="list-unstyled mb-4">
                @foreach(explode("\n", $mice->facilities) as $facility)
                  @if(trim($facility) != '')
                    <li class="mb-2">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                           fill="currentColor" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                      </svg>
                      {{ trim($facility) }}
                    </li>
                  @endif
                @endforeach
              </ul>

              <button type="button" class="btn btn-custom w-100 mt-3" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                Hubungi Sales
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- =================== DETAIL DI BAWAH =================== --}}
      <div class="row mt-5">
        <div class="col-lg-10">
          <h1 class="display-5">{{ $mice->name }}</h1>
          <hr style="border-color: var(--color-gold); border-width: 2px; width: 100px; opacity: 1;">

          <div class="my-4">
            <div class="row border-top border-bottom py-3">
              @if($mice->dimension)
                <div class="col-6 col-md-3">
                  <strong>DIMENSION</strong>
                  <p class="h5">{{ $mice->dimension }}</p>
                </div>
              @endif
              @if($mice->size_sqm)
                <div class="col-6 col-md-3">
                  <strong>SIZE</strong>
                  <p class="h5">{{ $mice->size_sqm }}</p>
                </div>
              @endif
            </div>
          </div>

          <div class="item-description mt-4">
            {!! nl2br(e($mice->description)) !!}
          </div>

          {{-- =================== Layout Capacity =================== --}}
          <div class="my-5">
            <h2 class="section-title text-center mb-5">Layout Capacity</h2>
            <div class="row text-center g-4 justify-content-center">
              @php
                $layouts = [
                  'Classroom' => ['capacity' => $mice->capacity_classroom, 'icon_key' => 'layout_icon_classroom'],
                  'Theatre' => ['capacity' => $mice->capacity_theatre, 'icon_key' => 'layout_icon_theatre'],
                  'U-Shape' => ['capacity' => $mice->capacity_ushape, 'icon_key' => 'layout_icon_ushape'],
                  'Round Table' => ['capacity' => $mice->capacity_round, 'icon_key' => 'layout_icon_round'],
                  'Board Room' => ['capacity' => $mice->capacity_board, 'icon_key' => 'layout_icon_board'],
                ];
              @endphp
              @foreach($layouts as $name => $details)
                @if($details['capacity'])
                  <div class="col-6 col-md-4 col-lg-3 mb-3">
                    <div class="card card-body text-center h-100 justify-content-center">
                      @if(isset($settings[$details['icon_key']]))
                        <img src="{{ asset('storage/'. $settings[$details['icon_key']]) }}" class="mb-2 mx-auto" style="width: 80px; height: 80px; object-fit: contain;">
                      @endif
                      <h6 class="fw-bold text-uppercase small">{{ $name }}</h6>
                      <span class="h5 fw-bold" style="color: var(--color-gold);">{{ $details['capacity'] }} Pax</span>
                    </div>
                  </div>
                @endif
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>

{{-- =================== INQUIRY MODAL =================== --}}
<div class="modal fade" id="inquiryModal" tabindex="-1" aria-labelledby="inquiryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="inquiryModalLabel">Inquiry for {{ $mice->name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('mice.inquiries.store') }}" method="POST" id="inquiryForm">
          @csrf
          <input type="hidden" name="mice_room_id" value="{{ $mice->id }}">
          <div class="mb-3">
            <label for="customer_name" class="form-label">Nama Anda</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
          </div>
          <div class="mb-3">
            <label for="customer_phone" class="form-label">Nomor WhatsApp (Contoh: 08123456789)</label>
            <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required pattern="^08[0-9]{8,11}$">
          </div>
          <div class="mb-3">
            <label for="event_type" class="form-label">Jenis Acara</label>
            <select class="form-select" id="event_type" name="event_type" required>
              <option value="">-- Pilih Jenis Acara --</option>
              <option value="meeting">Meeting</option>
              <option value="wedding">Wedding</option>
              <option value="birthday">Birthday Party</option>
              <option value="seminar">Seminar</option>
              <option value="other">Lainnya</option>
            </select>
          </div>
          <div class="mb-3" id="other-event-wrapper" style="display: none;">
            <label for="event_other_description" class="form-label">Sebutkan Jenis Acara Lainnya</label>
            <input type="text" class="form-control" id="event_other_description" name="event_other_description">
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-custom">Kirim Permintaan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.getElementById('event_type').addEventListener('change', function () {
    var wrapper = document.getElementById('other-event-wrapper');
    var otherInput = document.getElementById('event_other_description');
    if (this.value === 'other') {
      wrapper.style.display = 'block';
      otherInput.required = true;
    } else {
      wrapper.style.display = 'none';
      otherInput.required = false;
    }
  });
</script>
@endpush
@endsection
