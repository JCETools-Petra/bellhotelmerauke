<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New MICE Room') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.mice.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Nama, Dimensi, Ukuran --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">MICE Room Name</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('name') }}" required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label for="dimension" class="block text-sm font-medium text-gray-700">Dimension (e.g., 15 x 16)</label>
                                <input type="text" name="dimension" id="dimension" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('dimension') }}">
                            </div>
                            <div>
                                <label for="size_sqm" class="block text-sm font-medium text-gray-700">Size in SQM² (e.g., 240 M²)</label>
                                <input type="text" name="size_sqm" id="size_sqm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('size_sqm') }}">
                            </div>
                        </div>

                        <hr class="my-6">
                        
                        {{-- Spesifikasi Layout Dinamis --}}
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Layout Specifications</h3>
                        <div id="specifications-container" class="space-y-4">
                            {{-- Baris spesifikasi akan ditambahkan oleh JavaScript --}}
                        </div>
                        <button type="button" id="add-specification" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Layout</button>
                        
                        <hr class="my-6">
                        
                        {{-- Rate, Deskripsi, Fasilitas --}}
                        <div class="mb-4">
                            <label for="rate_details" class="block text-sm font-medium text-gray-700">Rate Details</label>
                            <textarea name="rate_details" id="rate_details" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('rate_details') }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description') }}</textarea>
                        </div>
                         <div class="mb-4">
                            <label for="facilities" class="block text-sm font-medium text-gray-700">Facilities (one per line)</label>
                            <textarea name="facilities" id="facilities" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('facilities') }}</textarea>
                        </div>

                        <hr class="my-6">
                        
                        {{-- Gambar-gambar Ruangan --}}
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-medium text-gray-700">Upload Images</label>
                            <input type="file" name="images[]" id="images" class="mt-1 block w-full" multiple>
                        </div>
                        
                        <hr class="my-6">
                        
                        {{-- Status & Tombol Submit --}}
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_available" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" checked>
                                <span class="ml-2 text-sm text-gray-600">Available for booking</span>
                            </label>
                        </div>
                        <div>
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:opacity-90 transition-opacity">Save MICE Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('specifications-container');
        let specificationIndex = 0;

        // Fungsi untuk menambah baris baru
        function addSpecificationRow() {
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
        }

        // Menambah baris saat tombol diklik
        document.getElementById('add-specification').addEventListener('click', addSpecificationRow);

        // Menghapus baris
        container.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-specification')) {
                e.target.closest('.specification-row').remove();
            }
        });

        // Tambah satu baris secara otomatis saat halaman dimuat
        addSpecificationRow();
    });
    </script>
    @endpush
</x-app-layout>