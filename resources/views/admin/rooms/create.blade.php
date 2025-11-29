<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Room') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- PERUBAHAN 1: Menambahkan ID pada form --}}
                    <form id="create-room-form" action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Room Name</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price (IDR)</label>
                            <input type="number" name="price" id="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('price') }}" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="discount_percentage" class="block text-sm font-medium text-gray-700">Affiliate Discount (%)</label>
                            <input type="number" name="discount_percentage" id="discount_percentage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('discount_percentage', 0) }}" min="0" max="100" step="0.01">
                            <p class="text-xs text-gray-500 mt-1">Diskon dalam persen untuk afiliasi (isi 0 jika tidak ada diskon).</p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('description') }}</textarea>
                        </div>

                         <div class="mb-4">
                            <label for="facilities" class="block text-sm font-medium text-gray-700">Facilities (one per line)</label>
                            <textarea name="facilities" id="facilities" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('facilities') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                            <input type="file" name="images[]" id="images" class="mt-1 block w-full" multiple>
                        </div>
                        
                        {{-- PERUBAHAN 2: Menambahkan elemen Progress Bar --}}
                        <div id="progress-container" class="w-full bg-gray-200 rounded-full h-2.5 my-4 hidden">
                            <div id="progress-bar" class="bg-blue-600 h-2.5 rounded-full text-center text-white text-xs" style="width: 0%"></div>
                        </div>
                        <div id="progress-text" class="text-sm text-gray-600 mb-4"></div>
                        {{-- Akhir Tambahan --}}

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_available" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" checked>
                                <span class="ml-2 text-sm text-gray-600">Available for booking</span>
                            </label>
                        </div>

                        <div>
                            {{-- PERUBAHAN 3: Menambahkan ID pada tombol submit --}}
                            <button type="submit" id="submit-button" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Save Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- PERUBAHAN 4: Menambahkan Script JavaScript --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('create-room-form');
            const submitButton = document.getElementById('submit-button');
            const progressContainer = document.getElementById('progress-container');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            const fileInput = document.getElementById('images');

            form.addEventListener('submit', function (event) {
                // Hanya jalankan logika AJAX jika ada file yang dipilih
                if (fileInput.files.length === 0) {
                    return; // Biarkan form submit secara normal jika tidak ada file
                }

                // Mencegah form submit secara normal
                event.preventDefault();

                // Menonaktifkan tombol submit
                submitButton.disabled = true;
                submitButton.textContent = 'Uploading...';

                const formData = new FormData(form);
                
                // Menampilkan progress bar
                progressContainer.classList.remove('hidden');
                progressText.textContent = '';

                const config = {
                    onUploadProgress: function (progressEvent) {
                        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        progressBar.style.width = percentCompleted + '%';
                        progressText.textContent = `Upload in progress... ${percentCompleted}%`;
                    }
                };

                axios.post(form.action, formData, config)
                    .then(function (response) {
                        progressText.textContent = 'Upload complete! Redirecting...';
                        progressBar.style.width = '100%';
                        progressBar.classList.remove('bg-blue-600');
                        progressBar.classList.add('bg-green-500');

                        // Redirect ke halaman index setelah berhasil
                        window.location.href = "{{ route('admin.rooms.index') }}";
                    })
                    .catch(function (error) {
                        progressText.textContent = 'An error occurred during upload. Please try again.';
                        progressBar.style.width = '100%';
                        progressBar.classList.remove('bg-blue-600');
                        progressBar.classList.add('bg-red-500');
                        
                        // Aktifkan kembali tombol submit
                        submitButton.disabled = false;
                        submitButton.textContent = 'Save Room';

                        if (error.response && error.response.status === 422) {
                            // Menampilkan error validasi dari Laravel
                            let errorMessages = 'Please fix the following errors:\n';
                            for (const key in error.response.data.errors) {
                                errorMessages += `- ${error.response.data.errors[key][0]}\n`;
                            }
                            alert(errorMessages);
                        } else {
                            alert('An unexpected error occurred. Please check the console for details.');
                            console.error(error);
                        }
                    });
            });
        });
    </script>
    @endpush
</x-app-layout>