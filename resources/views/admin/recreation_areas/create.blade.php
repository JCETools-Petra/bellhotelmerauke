<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            {{ __('Tambah Recreation Area') }}

        </h2>

    </x-slot>

 

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('admin.recreation-areas.store') }}" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="mb-4">

                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Recreation Area</label>

                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>

                            @error('name')

                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>

                            @enderror

                        </div>

 

                        <div class="mb-4">

                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>

                            <textarea id="description" name="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description') }}</textarea>

                            @error('description')

                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>

                            @enderror

                        </div>

 

                        <div class="mb-4">

                            <label for="order" class="block text-sm font-medium text-gray-700">Urutan Tampilan</label>

                            <input type="number" id="order" name="order" value="{{ old('order', 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" min="0">

                            <p class="mt-1 text-sm text-gray-500">Semakin kecil angka, semakin atas urutannya</p>

                            @error('order')

                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>

                            @enderror

                        </div>

 

                        <div class="mb-4">

                            <label class="flex items-center">

                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-brand-red shadow-sm focus:border-brand-red focus:ring focus:ring-brand-red focus:ring-opacity-50">

                                <span class="ml-2 text-sm text-gray-600">Aktif (tampilkan di website)</span>

                            </label>

                        </div>

 

                        <div class="mb-4">

                            <label for="images" class="block text-sm font-medium text-gray-700">Gambar</label>

                            <input type="file" id="images" name="images[]" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brand-red file:text-white hover:file:bg-brand-red/90">

                            <p class="mt-1 text-sm text-gray-500">Anda dapat memilih beberapa gambar sekaligus</p>

                            @error('images')

                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>

                            @enderror

                        </div>

 

                        <div id="captions-container" class="mb-4 hidden">

                            <label class="block text-sm font-medium text-gray-700 mb-2">Caption Gambar (Opsional)</label>

                            <div id="captions-list"></div>

                        </div>

 

                        <div class="flex gap-2">

                            <button type="submit" class="px-4 py-2 bg-brand-red text-white rounded-md hover:opacity-90 transition-opacity">

                                Simpan

                            </button>

                            <a href="{{ route('admin.recreation-areas.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:opacity-90 transition-opacity">

                                Batal

                            </a>

                        </div>

                    </form>

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