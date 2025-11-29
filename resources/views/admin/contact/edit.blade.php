<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contact Page Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.contact.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            {{-- Address --}}
                            <div>
                                <label for="contact_address" class="block text-sm font-medium">Alamat Kantor</label>
                                <textarea name="contact_address" id="contact_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ $settings['contact_address'] ?? '' }}</textarea>
                            </div>

                            {{-- Phone and Email --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium">Nomor Telepon</label>
                                    <input type="text" name="contact_phone" id="contact_phone" class="mt-1 block w-full rounded-md" value="{{ $settings['contact_phone'] ?? '' }}">
                                </div>
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium">Alamat Email</label>
                                    <input type="email" name="contact_email" id="contact_email" class="mt-1 block w-full rounded-md" value="{{ $settings['contact_email'] ?? '' }}">
                                </div>
                            </div>

                            {{-- Google Maps Embed --}}
                            <div>
                                <label for="contact_maps_embed" class="block text-sm font-medium">Kode Semat (Embed) Google Maps</label>
                                <textarea name="contact_maps_embed" id="contact_maps_embed" rows="5" class="mt-1 block w-full rounded-md">{{ $settings['contact_maps_embed'] ?? '' }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Buka Google Maps, cari lokasi, klik "Share" > "Embed a map", lalu salin dan tempel kode HTML di sini.</p>
                            </div>

                            <hr class="my-4">

                            {{-- SEO Settings --}}
                            <h4 class="text-lg font-semibold">SEO Settings</h4>
                            <div>
                                <label for="contact_seo_title" class="block text-sm font-medium">SEO Title</label>
                                <input type="text" name="contact_seo_title" id="contact_seo_title" class="mt-1 block w-full rounded-md" value="{{ $settings['contact_seo_title'] ?? '' }}">
                            </div>
                            <div>
                                <label for="contact_seo_description" class="block text-sm font-medium">SEO Meta Description</label>
                                <textarea name="contact_seo_description" id="contact_seo_description" rows="2" class="mt-1 block w-full rounded-md">{{ $settings['contact_seo_description'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">Save Contact Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>