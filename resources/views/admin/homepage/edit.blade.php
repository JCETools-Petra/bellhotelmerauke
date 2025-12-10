<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Homepage Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
       <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi Sukses --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ========================================================== --}}
            {{-- TAMBAHKAN BLOK KODE DI BAWAH INI UNTUK MENAMPILKAN ERROR --}}
            {{-- ========================================================== --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded-md">
                    <p class="font-bold">Oops! Ada beberapa kesalahan:</p>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- ========================================================== --}}
            {{-- AKHIR DARI BLOK KODE YANG DITAMBAHKAN --}}
            {{-- ========================================================== --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.homepage.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- General Website Settings --}}
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold border-b pb-2">General Website Settings</h3>
                            <div>
                                <label for="website_title" class="block text-sm font-medium text-gray-700">Website Title</label>
                                <input type="text" name="website_title" id="website_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $settings['website_title'] ?? '' }}">
                            </div>
                            <div class="bg-gray-50 p-3 rounded-md">
                                <label class="flex items-center">
                                    <input type="checkbox" name="show_logo_text" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ ($settings['show_logo_text'] ?? '1') == '1' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-800 font-semibold">Tampilkan Teks di Samping Logo</span>
                                </label>
                                <p class="text-xs text-gray-500 ml-6">Jika dicentang, tulisan "Bell Hotel" akan muncul di samping logo pada navbar.</p>
                            </div>
                            <div class="flex flex-col md:flex-row md:space-x-6 space-y-4 md:space-y-0">
                                <div>
                                    <label for="logo_path" class="block text-sm font-medium text-gray-700">Logo</label>
                                    <input type="file" name="logo_path" id="logo_path" class="mt-1 block w-full text-sm text-gray-500">
                                    @if(isset($settings['logo_path']))
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600">Logo Saat Ini:</p>
                                            <img src="{{ asset('storage/' . $settings['logo_path']) }}" alt="Current Logo" class="mt-2 rounded-md h-16 w-auto object-contain border p-1">
                                        </div>
                                    @endif
                                </div>
                                 <div>
                                    <label for="logo_height" class="block text-sm font-medium text-gray-700">Ukuran Tinggi Logo (pixel)</label>
                                    <input type="number" name="logo_height" id="logo_height"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                           value="{{ $settings['logo_height'] ?? '40' }}"
                                           placeholder="Contoh: 40">
                                    <p class="text-xs text-gray-500 mt-1">Atur tinggi logo. Lebar akan menyesuaikan otomatis.</p>
                                </div>
                                <div>
                                    <label for="favicon_path" class="block text-sm font-medium text-gray-700">Favicon</label>
                                    <input type="file" name="favicon_path" id="favicon_path" class="mt-1 block w-full text-sm text-gray-500">
                                    @if(isset($settings['favicon_path']))
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600">Favicon Saat Ini:</p>
                                            <img src="{{ asset('storage/' . $settings['favicon_path']) }}" alt="Current Favicon" class="mt-2 rounded-md h-8 w-8 object-contain border p-1">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="my-6 border-t border-gray-200">
                        
                        {{-- Featured Content Settings --}}
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold border-b pb-2">Featured Content Settings</h3>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Konten Unggulan</label>
                                <p class="text-xs text-gray-500 mb-2">Pilih konten yang akan ditampilkan di halaman depan.</p>
                                <div class="mt-2 space-y-2">
                                    @php
                                        $selectedOptions = explode(',', $settings['featured_display_option'] ?? '');
                                    @endphp
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="featured_display_option[]" value="rooms" class="form-checkbox" {{ in_array('rooms', $selectedOptions) ? 'checked' : '' }}>
                                        <span class="ml-2">Rooms</span>
                                    </label>
                                    <label class="inline-flex items-center ml-6">
                                        <input type="checkbox" name="featured_display_option[]" value="mice" class="form-checkbox" {{ in_array('mice', $selectedOptions) ? 'checked' : '' }}>
                                        <span class="ml-2">MICE</span>
                                    </label>
                                    <label class="inline-flex items-center ml-6">
                                        <input type="checkbox" name="featured_display_option[]" value="restaurants" class="form-checkbox" {{ in_array('restaurants', $selectedOptions) ? 'checked' : '' }}>
                                        <span class="ml-2">Restaurants</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-6 border-t border-gray-200">

                        {{-- Hero Section Settings --}}
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold border-b pb-2">Hero Section Settings</h3>
                            <div>
                                <label for="hero_bg_image" class="block text-sm font-medium text-gray-700">Gambar Latar Belakang (Fallback)</label>
                                <p class="text-xs text-gray-500 mb-2">Gambar ini akan digunakan jika tidak ada gambar di "Hero Sliders".</p>
                                <input type="file" name="hero_bg_image" id="hero_bg_image" class="mt-1 block w-full text-sm text-gray-500">
                                @if(isset($settings['hero_bg_image']))
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-600">Gambar Saat Ini:</p>
                                        <img src="{{ asset('storage/' . $settings['hero_bg_image']) }}" alt="Current Hero Background" class="mt-2 rounded-md h-32 w-auto object-cover">
                                    </div>
                                @endif
                            </div>
                            <div>
                                <label for="hero_text_align" class="block text-sm font-medium text-gray-700">Penjajaran Teks</label>
                                <select name="hero_text_align" id="hero_text_align" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="text-center" {{ ($settings['hero_text_align'] ?? 'text-center') == 'text-center' ? 'selected' : '' }}>Center</option>
                                    <option value="text-start" {{ ($settings['hero_text_align'] ?? '') == 'text-start' ? 'selected' : '' }}>Left</option>
                                    <option value="text-end" {{ ($settings['hero_text_align'] ?? '') == 'text-end' ? 'selected' : '' }}>Right</option>
                                </select>
                            </div>
                            
                            {{-- ======================= AWAL TAMBAHAN ======================= --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="hero_slider_height" class="block text-sm font-medium text-gray-700">Hero Slider Height</label>
                                    <input type="text" name="hero_slider_height" id="hero_slider_height" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $settings['hero_slider_height'] ?? '' }}" placeholder="e.g., 100vh or 800px">
                                    <p class="mt-1 text-xs text-gray-500">Contoh: `100vh` (full screen), `800px`. Biarkan kosong for default (auto).</p>
                                </div>
                                <div>
                                    <label for="hero_slider_width" class="block text-sm font-medium text-gray-700">Hero Slider Width</label>
                                    <input type="text" name="hero_slider_width" id="hero_slider_width" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $settings['hero_slider_width'] ?? '' }}" placeholder="e.g., 100%">
                                    <p class="mt-1 text-xs text-gray-500">Contoh: `100%`. Biarkan kosong untuk default (100%).</p>
                                </div>
                            </div>
                            {{-- ======================== AKHIR TAMBAHAN ======================= --}}

                            <div class="p-4 border rounded-md bg-gray-50">
                                <label for="hero_title" class="block text-sm font-medium text-gray-700">Judul Hero</label>
                                <input type="text" name="hero_title" id="hero_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $settings['hero_title'] ?? '' }}">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                                    <div>
                                        <label for="hero_title_font_size" class="block text-xs font-medium text-gray-600">Ukuran Font (rem)</label>
                                        <input type="number" step="0.1" name="hero_title_font_size" id="hero_title_font_size" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $settings['hero_title_font_size'] ?? '4.5' }}">
                                    </div>
                                    <div>
                                        <label for="hero_title_font_family" class="block text-xs font-medium text-gray-600">Jenis Font</label>
                                        <select name="hero_title_font_family" id="hero_title_font_family" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="'Playfair Display', serif" {{ ($settings['hero_title_font_family'] ?? '') == "'Playfair Display', serif" ? 'selected' : '' }}>Playfair Display (Elegan)</option>
                                            <option value="'Montserrat', sans-serif" {{ ($settings['hero_title_font_family'] ?? '') == "'Montserrat', sans-serif" ? 'selected' : '' }}>Montserrat (Modern)</option>
                                            <option value="'Lora', serif" {{ ($settings['hero_title_font_family'] ?? '') == "'Lora', serif" ? 'selected' : '' }}>Lora (Klasik)</option>
                                            <option value="'Poppins', sans-serif" {{ ($settings['hero_title_font_family'] ?? '') == "'Poppins', sans-serif" ? 'selected' : '' }}>Poppins (Santai)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 border rounded-md bg-gray-50">
                                <label for="hero_subtitle" class="block text-sm font-medium text-gray-700">Subjudul Hero</label>
                                <input type="text" name="hero_subtitle" id="hero_subtitle" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $settings['hero_subtitle'] ?? '' }}">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                                    <div>
                                        <label for="hero_subtitle_font_size" class="block text-xs font-medium text-gray-600">Ukuran Font (rem)</label>
                                        <input type="number" step="0.1" name="hero_subtitle_font_size" id="hero_subtitle_font_size" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $settings['hero_subtitle_font_size'] ?? '1.5' }}">
                                    </div>
                                    <div>
                                        <label for="hero_subtitle_font_family" class="block text-xs font-medium text-gray-600">Jenis Font</label>
                                        <select name="hero_subtitle_font_family" id="hero_subtitle_font_family" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="'Montserrat', sans-serif" {{ ($settings['hero_subtitle_font_family'] ?? '') == "'Montserrat', sans-serif" ? 'selected' : '' }}>Montserrat (Modern)</option>
                                            <option value="'Playfair Display', serif" {{ ($settings['hero_subtitle_font_family'] ?? '') == "'Playfair Display', serif" ? 'selected' : '' }}>Playfair Display (Elegan)</option>
                                            <option value="'Lora', serif" {{ ($settings['hero_subtitle_font_family'] ?? '') == "'Lora', serif" ? 'selected' : '' }}>Lora (Klasik)</option>
                                            <option value="'Poppins', sans-serif" {{ ($settings['hero_subtitle_font_family'] ?? '') == "'Poppins', sans-serif" ? 'selected' : '' }}>Poppins (Santai)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-6 border-t border-gray-200">

                        {{-- About Section Settings --}}
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold border-b pb-2">About Section Settings</h3>
                            <div class="bg-gray-50 p-3 rounded-md">
                                <label class="flex items-center">
                                    <input type="checkbox" name="show_about_section" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ ($settings['show_about_section'] ?? '1') == '1' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-800 font-semibold">Tampilkan "About Section" di Halaman Depan</span>
                                </label>
                            </div>
                            <div>
                                <label for="about_text_align" class="block text-sm font-medium text-gray-700">Penjajaran Teks</label>
                                <select name="about_text_align" id="about_text_align" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="text-center" {{ ($settings['about_text_align'] ?? 'text-center') == 'text-center' ? 'selected' : '' }}>Center</option>
                                    <option value="text-start" {{ ($settings['about_text_align'] ?? '') == 'text-start' ? 'selected' : '' }}>Left</option>
                                    <option value="text-end" {{ ($settings['about_text_align'] ?? '') == 'text-end' ? 'selected' : '' }}>Right</option>
                                </select>
                            </div>
                            <div class="p-4 border rounded-md bg-gray-50">
                                <label for="about_title" class="block text-sm font-medium text-gray-700">Judul About</label>
                                <input type="text" name="about_title" id="about_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $settings['about_title'] ?? '' }}">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                                    <div>
                                        <label for="about_title_font_size" class="block text-xs font-medium text-gray-600">Ukuran Font (rem)</label>
                                        <input type="number" step="0.1" name="about_title_font_size" id="about_title_font_size" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $settings['about_title_font_size'] ?? '2.8' }}">
                                    </div>
                                    <div>
                                        <label for="about_title_font_family" class="block text-xs font-medium text-gray-600">Jenis Font</label>
                                        <select name="about_title_font_family" id="about_title_font_family" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="'Playfair Display', serif" {{ ($settings['about_title_font_family'] ?? '') == "'Playfair Display', serif" ? 'selected' : '' }}>Playfair Display (Elegan)</option>
                                            <option value="'Montserrat', sans-serif" {{ ($settings['about_title_font_family'] ?? '') == "'Montserrat', sans-serif" ? 'selected' : '' }}>Montserrat (Modern)</option>
                                            <option value="'Lora', serif" {{ ($settings['about_title_font_family'] ?? '') == "'Lora', serif" ? 'selected' : '' }}>Lora (Klasik)</option>
                                            <option value="'Poppins', sans-serif" {{ ($settings['about_title_font_family'] ?? '') == "'Poppins', sans-serif" ? 'selected' : '' }}>Poppins (Santai)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 border rounded-md bg-gray-50">
                                <label for="about_content" class="block text-sm font-medium text-gray-700">Konten About</label>
                                <textarea name="about_content" id="about_content" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ $settings['about_content'] ?? '' }}</textarea>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                                    <div>
                                        <label for="about_content_font_size" class="block text-xs font-medium text-gray-600">Ukuran Font (rem)</label>
                                        <input type="number" step="0.1" name="about_content_font_size" id="about_content_font_size" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $settings['about_content_font_size'] ?? '1' }}">
                                    </div>
                                    <div>
                                        <label for="about_content_font_family" class="block text-xs font-medium text-gray-600">Jenis Font</label>
                                        <select name="about_content_font_family" id="about_content_font_family" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="'Montserrat', sans-serif" {{ ($settings['about_content_font_family'] ?? '') == "'Montserrat', sans-serif" ? 'selected' : '' }}>Montserrat (Modern)</option>
                                            <option value="'Playfair Display', serif" {{ ($settings['about_content_font_family'] ?? '') == "'Playfair Display', serif" ? 'selected' : '' }}>Playfair Display (Elegan)</option>
                                            <option value="'Lora', serif" {{ ($settings['about_content_font_family'] ?? '') == "'Lora', serif" ? 'selected' : '' }}>Lora (Klasik)</option>
                                            <option value="'Poppins', sans-serif" {{ ($settings['about_content_font_family'] ?? '') == "'Poppins', sans-serif" ? 'selected' : '' }}>Poppins (Santai)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-6 border-t border-gray-200">

                        {{-- MICE Layout Icons --}}
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold border-b pb-2">MICE Layout Icons</h3>
                            <p class="text-sm text-gray-600">Unggah gambar/ikon yang akan ditampilkan di halaman detail MICE. Direkomendasikan gambar kotak (rasio 1:1) dengan latar belakang transparan atau putih.</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                                @php
                                    $layouts = ['classroom', 'theatre', 'ushape', 'round', 'board'];
                                @endphp
                                @foreach($layouts as $layout)
                                    @php $key = 'layout_icon_' . $layout; @endphp
                                    <div class="p-4 border rounded-md bg-gray-50 space-y-2">
                                        <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 font-bold capitalize">{{ str_replace('_', ' ', $layout) }}</label>
                                        <input type="file" name="{{ $key }}" id="{{ $key }}" class="block w-full text-sm text-gray-500">
                                        @if(isset($settings[$key]))
                                            <img src="{{ asset('storage/' . $settings[$key]) }}" alt="{{ $layout }} icon" class="mt-2 rounded-md h-24 w-24 object-contain border p-1">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-brand-red text-white rounded-md hover:opacity-90 transition-opacity">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>