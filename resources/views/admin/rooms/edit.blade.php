<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Room: ') . $room->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.rooms.update', $room) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Room Name</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('name', $room->name) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price (IDR)</label>
                            <input type="number" name="price" id="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('price', $room->price) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="discount_percentage" class="block text-sm font-medium text-gray-700">Affiliate Discount (%)</label>
                            <input type="number" name="discount_percentage" id="discount_percentage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('discount_percentage', $room->discount_percentage) }}" min="0" max="100" step="0.01">
                             <p class="text-xs text-gray-500 mt-1">Diskon dalam persen untuk afiliasi (isi 0 jika tidak ada diskon).</p>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('description', $room->description) }}</textarea>
                        </div>

                         <div class="mb-4">
                            <label for="facilities" class="block text-sm font-medium text-gray-700">Facilities (one per line)</label>
                            <textarea name="facilities" id="facilities" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('facilities', $room->facilities) }}</textarea>
                        </div>

                        <hr class="my-6">
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-medium text-gray-700">Upload New Images</label>
                            <input type="file" name="images[]" id="images" class="mt-1 block w-full" multiple>
                        </div>
                        
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900">Existing Images</h3>
                            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                @forelse($room->images as $image)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $image->path) }}" class="rounded-md h-32 w-full object-cover">
                                        <a href="{{ route('admin.images.destroy', $image) }}" 
                                           class="absolute top-1 right-1 p-1 bg-brand-red text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity" 
                                           onclick="return confirm('Are you sure you want to delete this image?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>
                                @empty
                                    <p class="text-gray-500 col-span-full">No images uploaded yet.</p>
                                @endforelse
                            </div>
                        </div>
                        
                        <hr class="my-6">
                        
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_available" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ $room->is_available ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Available for booking</span>
                            </label>
                        </div>

                        <hr class="my-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
                            <div class="mb-4">
                                <label for="seo_title" class="block text-sm font-medium text-gray-700">SEO Title</label>
                                <input type="text" name="seo_title" id="seo_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('seo_title', $room->seo_title) }}">
                                <p class="text-xs text-gray-500 mt-1">Judul yang akan tampil di tab browser dan hasil pencarian Google (optimal 60 karakter).</p>
                            </div>
                            <div class="mb-4">
                                <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('meta_description', $room->meta_description) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Deskripsi singkat yang akan tampil di hasil pencarian Google (optimal 160 karakter).</p>
                            </div>

                        <div>
                            <button type="submit" class="px-4 py-2 bg-brand-red text-white rounded-md hover:opacity-90 transition-opacity">Update Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>