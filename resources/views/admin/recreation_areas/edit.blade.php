<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            {{ __('Edit Recreation Area') }}

        </h2>

    </x-slot>

 

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))

                <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-md">

                    {{ session('success') }}

                </div>

            @endif

 

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('admin.recreation-areas.update', $recreationArea) }}" method="POST" enctype="multipart/form-data">

                        @csrf

                        @method('PUT')

 

                        <div class="mb-4">

                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Recreation Area</label>

                            <input type="text" id="name" name="name" value="{{ old('name', $recreationArea->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('name') border-red-500 @enderror" required>

                            @error('name')

                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>

                            @enderror

                        </div>

 

                        <div class="mb-4">

                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>

                            <textarea id="description" name="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('description') border-red-500 @enderror">{{ old('description', $recreationArea->description) }}</textarea>

                            @error('description')

                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>

                            @enderror

                        </div>

 

                        <div class="mb-4">

                            <label for="order" class="block text-sm font-medium text-gray-700">Urutan Tampilan</label>

                            <input type="number" id="order" name="order" value="{{ old('order', $recreationArea->order) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" min="0">

                            <p class="mt-1 text-sm text-gray-500">Semakin kecil angka, semakin atas urutannya</p>

                            @error('order')

                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>

                            @enderror

                        </div>

 

                        <div class="mb-4">

                            <label class="flex items-center">

                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $recreationArea->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-brand-red shadow-sm focus:border-brand-red focus:ring focus:ring-brand-red focus:ring-opacity-50">

                                <span class="ml-2 text-sm text-gray-600">Aktif (tampilkan di website)</span>

                            </label>

                        </div>

 

                        @if ($recreationArea->images->isNotEmpty())

                            <div class="mb-4">

                                <p class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini:</p>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                                    @foreach ($recreationArea->images as $image)

                                        <div class="relative group">

                                            <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $recreationArea->name }}" class="w-full h-32 object-cover rounded-md">

                                            @if ($image->caption)

                                                <p class="text-xs text-gray-600 mt-1">{{ $image->caption }}</p>

                                            @endif

                                            <button

                                                type="submit"

                                                form="delete-image-{{ $image->id }}"

                                                class="bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity absolute top-1 right-1"

                                                onclick="return confirm('Apakah Anda yakin ingin menghapus gambar ini?')"

                                                aria-label="Hapus gambar"

                                            >

                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">

                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />

                                                </svg>

                                            </button>

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        @endif

 

                        <div class="mb-4">

                            <label for="images" class="block text-sm font-medium text-gray-700">Tambah Gambar Baru</label>

                            <input type="file" id="images" name="images[]" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brand-red file:text-white hover:file:bg-brand-red/90">

                            <p class="text-xs text-gray-500 mt-1">Anda dapat memilih beberapa file sekaligus.</p>

                        </div>

 

                        <div id="captions-container" class="mb-4 hidden">

                            <label class="block text-sm font-medium text-gray-700 mb-2">Caption Gambar Baru (Opsional)</label>

                            <div id="captions-list"></div>

                        </div>

 

                        <div class="flex gap-2">

                            <button type="submit" class="px-4 py-2 bg-brand-red text-white rounded-md hover:opacity-90 transition-opacity">

                                Perbarui

                            </button>

                            <a href="{{ route('admin.recreation-areas.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:opacity-90 transition-opacity">

                                Batal

                            </a>

                        </div>

                    </form>

 

                    {{-- Form DELETE gambar diletakkan di luar form utama (hidden) --}}

                    @if ($recreationArea->images->isNotEmpty())

                        @foreach ($recreationArea->images as $image)

                            <form id="delete-image-{{ $image->id }}"

                                  action="{{ route('admin.recreation-areas.image.destroy', ['image' => $image->id]) }}"

                                  method="POST" class="hidden">

                                @csrf

                                @method('DELETE')

                            </form>

                        @endforeach

                    @endif

                </div>

            </div>

        </div>

    </div>

 

    <script>

        document.getElementById('images').addEventListener('change', function(e) {

            const files = e.target.files;

            const container = document.getElementById('captions-container');

            const list = document.getElementById('captions-list');

 

            if (files.length > 0) {

                container.classList.remove('hidden');

                list.innerHTML = '';

 

                for (let i = 0; i < files.length; i++) {

                    const div = document.createElement('div');

                    div.className = 'mb-2';

                    div.innerHTML = `

                        <label class="block text-xs text-gray-600 mb-1">${files[i].name}</label>

                        <input type="text" name="captions[]" placeholder="Caption untuk gambar ini" class="w-full rounded-md border-gray-300 shadow-sm text-sm">

                    `;

                    list.appendChild(div);

                }

            } else {

                container.classList.add('hidden');

            }

        });

    </script>

</x-app-layout>