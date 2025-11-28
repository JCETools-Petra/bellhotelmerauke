<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit MICE Room: ') . $miceRoom->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.mice.update', $miceRoom->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        {{-- Nama, Dimensi, Ukuran --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">MICE Room Name</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('name', $miceRoom->name) }}" required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label for="dimension" class="block text-sm font-medium text-gray-700">Dimension (e.g., 15 x 16)</label>
                                <input type="text" name="dimension" id="dimension" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('dimension', $miceRoom->dimension) }}">
                            </div>
                            <div>
                                <label for="size_sqm" class="block text-sm font-medium text-gray-700">Size in SQM² (e.g., 240 M²)</label>
                                <input type="text" name="size_sqm" id="size_sqm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('size_sqm', $miceRoom->size_sqm) }}">
                            </div>
                        </div>

                        <hr class="my-6">

                        {{-- Spesifikasi Layout Dinamis --}}
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Layout Specifications</h3>
                        <div id="specifications-container" class="space-y-4">
                             @if ($miceRoom->specifications && is_string($miceRoom->specifications))
                                @foreach (json_decode($miceRoom->specifications, true) as $index => $spec)
                                <div class="grid grid-cols-1 md:grid-cols-10 gap-4 specification-row items-center">
                                    <div class="md:col-span-3">
                                        <label class="text-sm font-medium text-gray-700">Layout Name</label>
                                        <input type="text" name="specifications[{{ $index }}][key]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="e.g., Classroom" value="{{ $spec['key'] ?? '' }}">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-sm font-medium text-gray-700">Capacity (Pax)</label>
                                        <input type="text" name="specifications[{{ $index }}][value]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="e.g., 150 Pax" value="{{ $spec['value'] ?? '' }}">
                                    </div>
                                    <div class="md:col-span-4">
                                        <label class="text-sm font-medium text-gray-700">Layout Image</label>
                                        <input type="file" name="specifications[{{ $index }}][image]" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
                                        <input type="hidden" name="specifications[{{ $index }}][image_path]" value="{{ $spec['image'] ?? '' }}">
                                        @if(!empty($spec['image']))
                                            <img src="{{ asset($spec['image']) }}" alt="{{ $spec['key'] ?? '' }}" width="50" class="mt-2 rounded">
                                        @endif
                                    </div>
                                    <div class="md:col-span-1 pt-6">
                                        <button type="button" class="px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 remove-specification">Remove</button>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" id="add-specification" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Layout</button>
                        
                        <hr class="my-6">

                        {{-- Rate, Deskripsi, Fasilitas --}}
                        <div class="mb-4">
                            <label for="rate_details" class="block text-sm font-medium text-gray-700">Rate Details</label>
                            <textarea name="rate_details" id="rate_details" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('rate_details', $miceRoom->rate_details) }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $miceRoom->description) }}</textarea>
                        </div>
                         <div class="mb-4">
                            <label for="facilities" class="block text-sm font-medium text-gray-700">Facilities (one per line)</label>
                            <textarea name="facilities" id="facilities" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('facilities', $miceRoom->facilities) }}</textarea>
                        </div>

                        <hr class="my-6">
                        
                        {{-- Gambar-gambar Ruangan --}}
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-medium text-gray-700">Upload New Images</label>
                            <input type="file" name="images[]" id="images" class="mt-1 block w-full" multiple>
                        </div>
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900">Existing Images</h3>
                            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                @forelse($miceRoom->images as $image)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $image->path) }}" class="rounded-md h-32 w-full object-cover">
                                        <a href="{{ route('admin.images.destroy', $image->id) }}"
                                           class="absolute top-1 right-1 p-1 bg-red-600 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                                           onclick="event.preventDefault(); if(confirm('Are you sure?')) { document.getElementById('delete-image-{{ $image->id }}').submit(); }">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>
                                    <form id="delete-image-{{ $image->id }}" action="{{ route('admin.images.destroy', $image->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @empty
                                    <p class="text-gray-500 col-span-full">No images uploaded yet.</p>
                                @endforelse
                            </div>
                        </div>
                        
                        <hr class="my-6">

                        {{-- Status & Tombol Submit --}}
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_available" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ $miceRoom->is_available ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Available for booking</span>
                            </label>
                        </div>
                        <div>
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:opacity-90 transition-opacity">Update MICE Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let specificationIndex = {{ $miceRoom->specifications ? count(json_decode($miceRoom->specifications, true)) : 0 }};

            document.getElementById('add-specification').addEventListener('click', function () {
                const container = document.getElementById('specifications-container');
                const newRow = document.createElement('div');
                newRow.className = 'grid grid-cols-1 md:grid-cols-10 gap-4 specification-row items-center';
                newRow.innerHTML = `
                    <div class="md:col-span-3">
                        <label class="text-sm font-medium text-gray-700">Layout Name</label>
                        <input type="text" name="specifications[${specificationIndex}][key]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="e.g., Classroom">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-700">Capacity (Pax)</label>
                        <input type="text" name="specifications[${specificationIndex}][value]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="e.g., 150 Pax">
                    </div>
                    <div class="md:col-span-4">
                        <label class="text-sm font-medium text-gray-700">Layout Image</label>
                        <input type="file" name="specifications[${specificationIndex}][image]" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
                    </div>
                    <div class="md:col-span-1 pt-6">
                        <button type="button" class="px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 remove-specification">Remove</button>
                    </div>
                `;
                container.appendChild(newRow);
                specificationIndex++;
            });

            container.addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('remove-specification')) {
                    e.target.closest('.specification-row').remove();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>