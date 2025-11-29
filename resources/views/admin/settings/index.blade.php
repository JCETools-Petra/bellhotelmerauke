<x-app-layout>
    {{-- Slot untuk header halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Website Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                {{-- Method spoofing for POST request --}}

                @if(session('success'))
                    <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @php
                    // Normalisasi nilai default dari $settings + dukung old()
                    $websiteTitle = old('website_title', $settings['website_title'] ?? '');
                    $logoHeight   = old('logo_height', $settings['logo_height'] ?? '40');
                    $showLogoText = old('show_logo_text', $settings['show_logo_text'] ?? '1');

                    $heroTitle    = old('hero_title', $settings['hero_title'] ?? '');
                    $heroSubtitle = old('hero_subtitle', $settings['hero_subtitle'] ?? '');

                    $addr         = old('contact_address', $settings['contact_address'] ?? '');
                    $mapsEmbed    = old('contact_maps_embed', $settings['contact_maps_embed'] ?? '');
                    $phone        = old('contact_phone', $settings['contact_phone'] ?? '');
                    $email        = old('contact_email', $settings['contact_email'] ?? '');
                    $fb           = old('contact_facebook', $settings['contact_facebook'] ?? '');
                    $ig           = old('contact_instagram', $settings['contact_instagram'] ?? '');
                    $li           = old('contact_linkedin', $settings['contact_linkedin'] ?? '');
                    $yt           = old('contact_youtube', $settings['contact_youtube'] ?? '');
                    $tt           = old('contact_tiktok', $settings['contact_tiktok'] ?? '');

                    $midMerchant  = old('midtrans_merchant_id', $settings['midtrans_merchant_id'] ?? '');
                    $midClientKey = old('midtrans_client_key', $settings['midtrans_client_key'] ?? '');
                    $midServerKey = old('midtrans_server_key', $settings['midtrans_server_key'] ?? '');
                    $midIsProd    = old('midtrans_is_production', $settings['midtrans_is_production'] ?? '0');

                    $waCustMsg    = old('whatsapp_customer_message', $settings['whatsapp_customer_message'] ?? 'Terima kasih! Pembayaran untuk booking ID: {booking_id} telah kami terima. Kamar Anda telah berhasil dipesan. Kami tunggu kedatangan Anda di Bell Hotel Merauke.');
                    $waAdminMsg   = old('whatsapp_admin_message', $settings['whatsapp_admin_message'] ?? "✅ *Konfirmasi Pembayaran Baru!*\n\n*Booking ID:* {booking_id}\n*Nama Tamu:* {guest_name}\n*Telepon:* {guest_phone}\n*Email:* {guest_email}\n*Check-in:* {checkin_date}\n*Check-out:* {checkout_date}");

                    $currentMethod = old('booking_method', $settings['booking_method'] ?? 'direct');

                    // Variabel untuk Running Text
                    $runningTextEnabled = old('running_text_enabled', $settings['running_text_enabled'] ?? '0');
                    $runningTextContent = old('running_text_content', $settings['running_text_content'] ?? '');
                    $runningTextUrl     = old('running_text_url', $settings['running_text_url'] ?? '');
                @endphp
                
                {{-- General Settings --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-3 mb-4">General Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="website_title" class="block font-medium text-sm text-gray-700">Website Title</label>
                            <input type="text" name="website_title" id="website_title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $websiteTitle }}" required>
                        </div>
                        <div>
                            <label for="logo_height" class="block font-medium text-sm text-gray-700">Logo Height (px)</label>
                            <input type="number" name="logo_height" id="logo_height" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $logoHeight }}" required>
                        </div>
                        <div>
                            <label for="logo" class="block font-medium text-sm text-gray-700">Upload Logo</label>
                            <input type="file" name="logo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @if(isset($settings['logo_path']))
                                <img src="{{ asset('storage/' . $settings['logo_path']) }}" alt="Current Logo" class="mt-2 h-12 border rounded">
                            @endif
                        </div>
                        <div>
                            <label for="favicon" class="block font-medium text-sm text-gray-700">Upload Favicon</label>
                            <input type="file" name="favicon" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @if(isset($settings['favicon_path']))
                                <img src="{{ asset('storage/' . $settings['favicon_path']) }}" alt="Current Favicon" class="mt-2 h-8 border rounded">
                            @endif
                        </div>
                        <div class="md:col-span-2">
                            <input type="hidden" name="show_logo_text" value="0">
                            <label for="show_logo_text" class="flex items-center">
                                <input type="checkbox" name="show_logo_text" id="show_logo_text" value="1" {{ (string)$showLogoText === '1' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Show Website Title next to Logo</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Running Text Announcement --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-3 mb-4">Running Text Announcement</h3>
                    <div class="mb-4">
                        <input type="hidden" name="running_text_enabled" value="0">
                        <label for="running_text_enabled" class="flex items-center">
                            <input type="checkbox" name="running_text_enabled" id="running_text_enabled" value="1" {{ (string)$runningTextEnabled === '1' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Aktifkan Running Text</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="running_text_content" class="block font-medium text-sm text-gray-700">Teks yang Ditampilkan</label>
                            <input type="text" name="running_text_content" id="running_text_content" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $runningTextContent }}">
                        </div>
                        <div>
                            <label for="running_text_url" class="block font-medium text-sm text-gray-700">Link URL (jika diklik)</label>
                            <input type="url" name="running_text_url" id="running_text_url" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $runningTextUrl }}" placeholder="https://contoh.com/promo">
                        </div>
                    </div>
                </div>

                {{-- MICE Layout Icons --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-3 mb-4">MICE Layout Icons</h3>
                    <p class="text-sm text-gray-600 mb-6">Upload an icon for each MICE room layout. These icons will be displayed on all MICE detail pages.</p>
                    
                    @php
                        $layout_icons = [
                            'layout_icon_classroom' => 'Classroom Layout Icon',
                            'layout_icon_theatre'   => 'Theatre Layout Icon',
                            'layout_icon_ushape'    => 'U-Shape Layout Icon',
                            'layout_icon_round'     => 'Round Table Layout Icon',
                            'layout_icon_board'     => 'Board Room Layout Icon',
                        ];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($layout_icons as $key => $label)
                        <div class="border p-4 rounded-md">
                            <label for="{{ $key }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                            <input type="file" name="{{ $key }}" id="{{ $key }}" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"/>
                            
                            @if(isset($settings[$key]) && $settings[$key])
                            <div class="mt-4">
                                <p class="text-xs text-gray-500 mb-1">Current Icon:</p>
                                <img src="{{ asset('storage/' . $settings[$key]) }}" class="rounded-md h-20 w-20 object-contain border p-1">
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Metode Booking --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-3 mb-4">Metode Booking</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="booking_method" class="block font-medium text-sm text-gray-700">Metode Booking Aktif</label>
                            <select name="booking_method" id="booking_method" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="direct" {{ $currentMethod === 'direct' ? 'selected' : '' }}>
                                    Direct Booking (Pembayaran via Midtrans)
                                </option>
                                <option value="manual" {{ $currentMethod === 'manual' ? 'selected' : '' }}>
                                    Manual Booking (Follow-up via WhatsApp)
                                </option>
                            </select>
                            <p class="mt-2 text-xs text-gray-500">Pilih metode yang akan digunakan oleh pelanggan di halaman depan.</p>
                            <div class="mt-2 text-xs">
                                @if($currentMethod === 'manual')
                                    <span class="px-2 py-1 rounded bg-yellow-50 text-yellow-700 border border-yellow-200">Saat ini: Manual — form booking akan mengirim notifikasi WhatsApp ke admin (tanpa halaman pembayaran).</span>
                                @else
                                    <span class="px-2 py-1 rounded bg-blue-50 text-blue-700 border border-blue-200">Saat ini: Direct — pelanggan akan diarahkan ke pembayaran Midtrans.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Homepage Hero Section --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-3 mb-4">Homepage Hero Section</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="hero_title" class="block font-medium text-sm text-gray-700">Hero Title</label>
                            <input type="text" name="hero_title" id="hero_title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $heroTitle }}">
                        </div>
                        <div>
                            <label for="hero_subtitle" class="block font-medium text-sm text-gray-700">Hero Subtitle</label>
                            <textarea name="hero_subtitle" id="hero_subtitle" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="3">{{ $heroSubtitle }}</textarea>
                        </div>
                        <div>
                            <label for="hero_image" class="block font-medium text-sm text-gray-700">Hero Background Image</label>
                            <input type="file" name="hero_image" id="hero_image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @if(isset($settings['hero_image_path']))
                                <img src="{{ asset('storage/' . $settings['hero_image_path']) }}" alt="Current Hero Image" class="mt-2 w-48 border rounded">
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Contact & Social Media --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-3 mb-4">Contact & Social Media</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="contact_address" class="block font-medium text-sm text-gray-700">Address</label>
                            <input type="text" name="contact_address" id="contact_address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $addr }}">
                        </div>
                        <div>
                            <label for="contact_maps_embed" class="block font-medium text-sm text-gray-700">Google Maps Embed Code</label>
                            <textarea name="contact_maps_embed" id="contact_maps_embed" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="4">{{ $mapsEmbed }}</textarea>
                            <p class="mt-2 text-xs text-gray-500">Buka Google Maps, cari lokasi Anda, klik "Share", lalu "Embed a map", dan salin kode HTML-nya ke sini.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="contact_phone" class="block font-medium text-sm text-gray-700">Phone Number</label>
                                <input type="text" name="contact_phone" id="contact_phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $phone }}">
                            </div>
                            <div>
                                <label for="contact_email" class="block font-medium text-sm text-gray-700">Email Address</label>
                                <input type="email" name="contact_email" id="contact_email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $email }}">
                            </div>
                        </div>
                        <hr>
                        <h4 class="text-md font-medium text-gray-800">Social Media Links</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="contact_facebook" class="block font-medium text-sm text-gray-700">Facebook URL</label>
                                <input type="url" name="contact_facebook" id="contact_facebook" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $fb }}">
                            </div>
                            <div>
                                <label for="contact_instagram" class="block font-medium text-sm text-gray-700">Instagram URL</label>
                                <input type="url" name="contact_instagram" id="contact_instagram" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $ig }}">
                            </div>
                            <div>
                                <label for="contact_linkedin" class="block font-medium text-sm text-gray-700">LinkedIn URL</label>
                                <input type="url" name="contact_linkedin" id="contact_linkedin" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $li }}">
                            </div>
                            <div>
                                <label for="contact_youtube" class="block font-medium text-sm text-gray-700">YouTube URL</label>
                                <input type="url" name="contact_youtube" id="contact_youtube" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $yt }}">
                            </div>
                            <div>
                                <label for="contact_tiktok" class="block font-medium text-sm text-gray-700">TikTok URL</label>
                                <input type="url" name="contact_tiktok" id="contact_tiktok" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $tt }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Midtrans Payment Gateway --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-3 mb-4">Midtrans Payment Gateway</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="midtrans_merchant_id" class="block font-medium text-sm text-gray-700">Merchant ID</label>
                                <input type="text" name="midtrans_merchant_id" id="midtrans_merchant_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $midMerchant }}">
                            </div>
                            <div>
                                <label for="midtrans_client_key" class="block font-medium text-sm text-gray-700">Client Key</label>
                                <input type="text" name="midtrans_client_key" id="midtrans_client_key" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $midClientKey }}">
                            </div>
                        </div>
                        <div>
                            <label for="midtrans_server_key" class="block font-medium text-sm text-gray-700">Server Key</label>
                            <input type="text" name="midtrans_server_key" id="midtrans_server_key" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $midServerKey }}">
                        </div>
                        <div>
                            <input type="hidden" name="midtrans_is_production" value="0">
                            <label for="midtrans_is_production" class="flex items-center">
                                <input type="checkbox" name="midtrans_is_production" id="midtrans_is_production" value="1" {{ (string)$midIsProd === '1' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Aktifkan Mode Produksi (Live)</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500">Hanya aktifkan jika Anda sudah menggunakan akun Midtrans produksi (bukan sandbox).</p>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="fonnte_api_key" class="block font-medium text-sm text-gray-700">Fonnte API Key (Token)</label>
                    <input type="text" name="fonnte_api_key" id="fonnte_api_key" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('fonnte_api_key', $settings['fonnte_api_key'] ?? '') }}" placeholder="Masukkan token Fonnte Anda di sini">
                </div>
                {{-- Pengaturan Notifikasi WhatsApp (Pay at Hotel) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-3 mb-4">Notifikasi WhatsApp (Pay at Hotel)</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="whatsapp_admin_receiver" class="block font-medium text-sm text-gray-700">Nomor WA Admin Utama</label>
                            <input type="text" name="whatsapp_admin_receiver" id="whatsapp_admin_receiver" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('whatsapp_admin_receiver', $settings['whatsapp_admin_receiver'] ?? '') }}" placeholder="Contoh: 081234567890">
                        </div>
                        <div>
                            <label for="whatsapp_supervisor_receivers" class="block font-medium text-sm text-gray-700">Nomor WA Supervisor Tambahan (pisahkan dengan koma)</label>
                            <input type="text" name="whatsapp_supervisor_receivers" id="whatsapp_supervisor_receivers" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('whatsapp_supervisor_receivers', $settings['whatsapp_supervisor_receivers'] ?? '') }}" placeholder="Contoh: 0812..., 0813...">
                        </div>
                        <div>
                            <label for="whatsapp_pay_at_hotel_admin_template" class="block font-medium text-sm text-gray-700">Template Pesan ke Admin/Supervisor</label>
                            <textarea name="whatsapp_pay_at_hotel_admin_template" id="whatsapp_pay_at_hotel_admin_template" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="6">{{ old('whatsapp_pay_at_hotel_admin_template', $settings['whatsapp_pay_at_hotel_admin_template'] ?? "🔔 *Booking Baru - Bayar di Hotel*\n\nSeorang tamu telah melakukan reservasi melalui afiliasi dan akan membayar di hotel.\n\n*Booking ID:* {booking_id}\n*Afiliasi:* {affiliate_name}\n\n*Detail Tamu:*\n*Nama:* {guest_name}\n*Telepon:* {guest_phone}\n*Email:* {guest_email}\n\n*Detail Menginap:*\n*Kamar:* {room_name}\n*Check-in:* {checkin_date}\n*Check-out:* {checkout_date}\n*Total Biaya:* {total_price}") }}</textarea>
                        </div>
                        <div>
                            <label for="whatsapp_pay_at_hotel_customer_template" class="block font-medium text-sm text-gray-700">Template Pesan ke Customer</label>
                            <textarea name="whatsapp_pay_at_hotel_customer_template" id="whatsapp_pay_at_hotel_customer_template" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="4">{{ old('whatsapp_pay_at_hotel_customer_template', $settings['whatsapp_pay_at_hotel_customer_template'] ?? "Terima kasih, {guest_name}!\n\nBooking Anda di Bell Hotel Merauke dengan ID #{booking_id} telah kami konfirmasi.\n\nSilakan lakukan pembayaran saat Anda tiba di hotel. Kami tunggu kedatangan Anda!") }}</textarea>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Variabel yang tersedia: <code>{booking_id}</code>, <code>{affiliate_name}</code>, <code>{guest_name}</code>, <code>{guest_phone}</code>, <code>{guest_email}</code>, <code>{room_name}</code>, <code>{checkin_date}</code>, <code>{checkout_date}</code>, <code>{total_price}</code>
                        </p>
                    </div>
                </div>
                {{-- Pengaturan Notifikasi WhatsApp --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-3 mb-4">Pengaturan Notifikasi WhatsApp</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="whatsapp_customer_message" class="block font-medium text-sm text-gray-700">Template Pesan ke Pelanggan</label>
                            <textarea name="whatsapp_customer_message" id="whatsapp_customer_message" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="5">{{ $waCustMsg }}</textarea>
                            <p class="mt-2 text-xs text-gray-500">
                                Variabel yang tersedia: <code>{guest_name}</code>, <code>{booking_id}</code>
                            </p>
                        </div>
                        <div>
                            <label for="whatsapp_admin_message" class="block font-medium text-sm text-gray-700">Template Pesan ke Admin</label>
                            <textarea name="whatsapp_admin_message" id="whatsapp_admin_message" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="8">{{ $waAdminMsg }}</textarea>
                            <p class="mt-2 text-xs text-gray-500">
                                Variabel yang tersedia: <code>{booking_id}</code>, <code>{guest_name}</code>, <code>{guest_phone}</code>, <code>{guest_email}</code>, <code>{checkin_date}</code>, <code>{checkout_date}</code>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Terms and Conditions --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-3 mb-4">Terms and Conditions Page</h3>
                    <div>
                        <label for="terms_and_conditions_editor" class="block font-medium text-sm text-gray-700 mb-1">Page Content</label>
                        <textarea name="terms_and_conditions" id="terms_and_conditions_editor" class="form-control" rows="15">{{ old('terms_and_conditions', $settings['terms_and_conditions'] ?? '') }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        {{-- CKEditor 5 --}}
        <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                ClassicEditor
                    .create( document.querySelector( '#terms_and_conditions_editor' ) )
                    .catch( error => { console.error( error ); } );
            });
        </script>
    @endpush
</x-app-layout>