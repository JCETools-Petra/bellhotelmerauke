<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Harga Khusus') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 border border-green-300 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form untuk Menambah/Mengubah Harga --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-4">Tambah Harga Khusus</h3>
                <form action="{{ route('admin.price-overrides.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Pilih Tanggal</label>
                            <input type="date" name="date" id="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-md font-medium text-gray-800 mb-2">Masukkan Harga Baru (Rp)</h4>
                        <p class="text-sm text-gray-500 mb-4">Isi harga hanya untuk kamar yang ingin diubah. Biarkan kosong jika ingin menggunakan harga normal.</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach ($rooms as $room)
                                <div>
                                    <label for="price_{{ $room->id }}" class="block text-sm font-medium text-gray-700">{{ $room->name }}</label>
                                    <input type="number" name="prices[{{ $room->id }}]" id="price_{{ $room->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Normal: {{ number_format($room->price) }}">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Simpan Harga</button>
                    </div>
                </form>
            </div>

            {{-- Tabel Daftar Harga Khusus yang Sudah Ada --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-4">Daftar Harga Khusus Aktif</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe Kamar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Khusus</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($overrides as $override)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($override->date)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $override->room->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold">Rp {{ number_format($override->price) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <form action="{{ route('admin.price-overrides.destroy', $override) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada harga khusus yang diatur.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $overrides->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>