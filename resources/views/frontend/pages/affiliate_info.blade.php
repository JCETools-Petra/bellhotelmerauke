{{-- File: resources/views/frontend/pages/affiliate_info.blade.php --}}

@extends('layouts.frontend')

@section('seo_title', 'Apa itu Program Afiliasi? - ' . ($settings['website_title'] ?? 'Bell Hotel Merauke'))
@section('meta_description', 'Pelajari cara mendapatkan komisi dengan mempromosikan hotel kami melalui Program Afiliasi Bell Hotel Merauke.')

@section('content')
<div class="page-content-wrapper" style="padding-top: 8rem; padding-bottom: 5rem;">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card p-4 p-md-5 shadow-sm">
                    <h1 class="text-center section-title">Program Afiliasi Bell Hotel</h1>

                    <div class="page-content mt-4">
                        <p class="lead text-center">Dapatkan penghasilan tambahan dengan mereferensikan tamu untuk menginap di hotel kami!</p>

                        <hr class="my-4">

                        <h3>Apa itu Program Afiliasi?</h3>
                        <p>Program Afiliasi adalah program kemitraan di mana Anda (sebagai afiliasi) akan mendapatkan komisi untuk setiap pemesanan kamar yang berhasil yang berasal dari link rujukan unik Anda.</p>

                        <h3 class="mt-4">Bagaimana Cara Kerjanya?</h3>
                        <ol>
                            <li><strong>Daftar</strong>: Buat akun dan daftar sebagai afiliasi. Prosesnya cepat dan gratis.</li>
                            <li><strong>Bagikan Link</strong>: Setelah pendaftaran Anda disetujui, Anda akan mendapatkan link rujukan khusus. Bagikan link ini di media sosial, blog, website, atau ke teman dan keluarga Anda.</li>
                            <li><strong>Dapatkan Komisi</strong>: Setiap kali seseorang mengklik link Anda dan melakukan pemesanan yang valid di website kami, Anda akan menerima komisi sebesar persentase dari total nilai pemesanan.</li>
                        </ol>

                        <h3 class="mt-4">Mengapa Bergabung dengan Kami?</h3>
                        <ul>
                            <li><strong>Komisi Kompetitif</strong>: Kami menawarkan struktur komisi yang menarik.</li>
                            <li><strong>Pelacakan Transparan</strong>: Anda akan memiliki dashboard sendiri untuk memantau klik, pemesanan, dan total komisi Anda secara real-time.</li>
                            <li><strong>Pembayaran Mudah</strong>: Komisi yang telah terverifikasi akan dibayarkan secara berkala.</li>
                        </ul>

                        <div class="text-center mt-5">
                            <a href="{{ route('affiliate.register.create') }}" class="btn btn-custom">Daftar Sekarang & Mulai Hasilkan!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection