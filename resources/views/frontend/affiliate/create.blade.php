@extends('layouts.frontend')

@section('seo_title', 'Daftar Affiliate - Bell Hotel Merauke')
@section('meta_description', 'Bergabunglah dengan program affiliate Bell Hotel Merauke dan dapatkan komisi menarik dari setiap pemesanan yang Anda referensikan.')

@section('content')
    <div class="min-h-screen bg-gray-900 flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        
        {{-- Background Elements --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-yellow-500 rounded-full opacity-10 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-blue-600 rounded-full opacity-10 blur-3xl"></div>
            <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?q=80&w=1920&auto=format&fit=crop" 
                 class="absolute inset-0 w-full h-full object-cover opacity-5 mix-blend-overlay" alt="Background">
        </div>

        <div class="relative z-10 w-full max-w-5xl">
            
            {{-- Header Text --}}
            <div class="text-center mb-10">
                <span class="text-yellow-500 font-bold uppercase tracking-[0.2em] text-xs mb-2 block">Partnership Program</span>
                <h1 class="text-3xl md:text-4xl font-heading font-bold text-white tracking-tight mb-3">
                    Bergabung Menjadi <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-600">Affiliate</span>
                </h1>
                <p class="text-gray-400 max-w-2xl mx-auto text-sm">
                    Dapatkan penghasilan tambahan dengan merekomendasikan Bell Hotel Merauke.
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-800 flex flex-col lg:flex-row">
                
                {{-- KIRI: KEUNTUNGAN PARTNER --}}
                <div class="w-full lg:w-5/12 bg-gray-50 p-8 border-b lg:border-b-0 lg:border-r border-gray-100 flex flex-col justify-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Keuntungan Partner</h3>
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 flex-shrink-0 shadow-sm">
                                <i class="fas fa-percentage text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Komisi Menarik</h4>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">Dapatkan persentase komisi dari setiap booking yang valid.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0 shadow-sm">
                                <i class="fas fa-chart-line text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Dashboard Real-time</h4>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">Pantau performa klik dan pendapatan Anda kapan saja.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 flex-shrink-0 shadow-sm">
                                <i class="fas fa-wallet text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Pembayaran Mudah</h4>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">Pencairan komisi langsung ke rekening bank Anda.</p>
                            </div>
                        </li>
                    </ul>
                    
                    <div class="mt-8 p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
                        <p class="text-xs text-gray-600 italic text-center">
                            "Bergabung dengan Bell Hotel Affiliate sangat mudah dan menguntungkan."
                        </p>
                    </div>
                </div>

                {{-- KANAN: FORMULIR REGISTRASI --}}
                <div class="w-full lg:w-7/12 p-8 lg:p-10 bg-white">
                    
                    @if ($errors->any())
                        <div class="mb-6 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded text-xs">
                            <p class="font-bold mb-1">Perhatian:</p>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('affiliate.register.store') }}" class="space-y-4">
                        @csrf

                        {{-- SECTION 1: DATA AKUN --}}
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-gray-900 text-white text-xs font-bold">1</span>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Informasi Akun</h4>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                    {{-- Jika Login: Tampilkan Value, Readonly. Jika Guest: Kosong, Wajib Isi --}}
                                    <input type="text" name="name" value="{{ old('name', Auth::user()->name ?? '') }}" required 
                                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 py-2.5 {{ Auth::check() ? 'text-gray-500 cursor-not-allowed' : '' }}" 
                                           placeholder="Nama Sesuai KTP"
                                           {{ Auth::check() ? 'readonly' : '' }}>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required 
                                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 py-2.5 {{ Auth::check() ? 'text-gray-500 cursor-not-allowed' : '' }}" 
                                           placeholder="anda@email.com"
                                           {{ Auth::check() ? 'readonly' : '' }}>
                                </div>
                            </div>

                            @guest
                            {{-- Password hanya muncul jika Guest --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                                    <input type="password" name="password" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 py-2.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 py-2.5">
                                </div>
                            </div>
                            @endguest
                        </div>

                        {{-- SECTION 2: DATA PEMBAYARAN --}}
                        <div class="space-y-4 pt-2">
                            <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-yellow-500 text-white text-xs font-bold">2</span>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Detail Pembayaran</h4>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. WhatsApp</label>
                                {{-- Phone diisi otomatis jika user sudah punya data phone --}}
                                <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" required placeholder="08xxxxxxxxxx" class="w-full rounded-lg border-gray-300 text-sm focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 py-2.5">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-1">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Bank</label>
                                    <select name="bank_name" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 py-2.5">
                                        <option value="" disabled selected>Pilih</option>
                                        <option value="BCA">BCA</option>
                                        <option value="Mandiri">Mandiri</option>
                                        <option value="BNI">BNI</option>
                                        <option value="BRI">BRI</option>
                                        <option value="Papua">BPD Papua</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">No. Rekening</label>
                                    <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 py-2.5" placeholder="Angka Saja">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Atas Nama (Sesuai Buku Tabungan)</label>
                                <input type="text" name="bank_account_holder" value="{{ old('bank_account_holder') }}" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-yellow-500 focus:border-yellow-500 bg-gray-50 py-2.5" placeholder="Nama Pemilik Rekening">
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-gray-900 bg-yellow-500 hover:bg-yellow-400 focus:outline-none transition-all transform hover:-translate-y-0.5">
                                Daftar Sekarang
                            </button>
                        </div>

                        @guest
                            <p class="text-center text-xs text-gray-500 pt-2">
                                Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-yellow-600 hover:underline">Masuk di sini</a>
                            </p>
                        @endguest
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <p class="text-gray-500 text-xs">
                    Dengan mendaftar, Anda menyetujui <a href="{{ route('pages.terms') }}" class="text-gray-400 hover:text-white underline decoration-dotted">Syarat & Ketentuan</a>.
                </p>
            </div>

        </div>
    </div>
@endsection