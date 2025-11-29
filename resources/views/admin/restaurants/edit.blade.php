<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Restoran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Menampilkan pesan sukses --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-md">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.restaurants.update', $restaurant) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Restoran</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $restaurant->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('name') border-red-500 @enderror" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea id="description" name="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('description') border-red-500 @enderror">{{ old('description', $restaurant->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        @if ($restaurant->images->isNotEmpty())
                            <div class="mb-4">
                                <p class="block text-sm font-medium text-gray-700">Gambar Saat Ini:</p>
                                <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach ($restaurant->images as $image)
                                        <div class="relative group">
                                            <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $restaurant->name }}" class="w-full h-32 object-cover rounded-md">
                                            
                                            {{-- PERBAIKAN: Hindari form bersarang. Tombol menunjuk ke form hidden di luar form utama. --}}
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
                            <input type="file" id="images" name="images[]" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brand-red file:text-white hover:file:bg-brand-red/90">
                            <p class="text-xs text-gray-500 mt-1">Anda dapat memilih beberapa file sekaligus.</p>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-brand-red text-white rounded-md hover:opacity-90 transition-opacity">
                            Perbarui
                        </button>
                    </form>

                    {{-- Form DELETE gambar diletakkan di luar form utama (hidden) --}}
                    @if ($restaurant->images->isNotEmpty())
                        @foreach ($restaurant->images as $image)
                            <form id="delete-image-{{ $image->id }}"
                                  action="{{ route('admin.restaurants.image.destroy', ['image' => $image->id]) }}"
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
</x-app-layout>
