<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">

                {{ __('Manajemen Recreation Area') }}

            </h2>

            <a href="{{ route('admin.recreation-areas.create') }}" class="px-4 py-2 bg-brand-red text-white rounded-md hover:opacity-90 transition-opacity">

                {{ __('Tambah Recreation Area') }}

            </a>

        </div>

    </x-slot>

 

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))

                <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-md">

                    {{ session('success') }}

                </div>

            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>

                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>

                            </tr>

                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            @forelse ($recreationAreas as $area)

                                <tr>

                                    <td class="px-6 py-4 whitespace-nowrap">{{ $area->order }}</td>

                                    <td class="px-6 py-4 whitespace-nowrap">{{ $area->name }}</td>

                                    <td class="px-6 py-4">{{ Str::limit($area->description, 50) }}</td>

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        @if ($area->is_active)

                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">

                                                Aktif

                                            </span>

                                        @else

                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">

                                                Tidak Aktif

                                            </span>

                                        @endif

                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        @if ($area->images->isNotEmpty())

                                            <img src="{{ asset('storage/' . $area->images->first()->path) }}" alt="{{ $area->name }}" class="w-24 h-24 object-cover rounded-md">

                                        @endif

                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                        <a href="{{ route('admin.recreation-areas.edit', $area) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>

                                        <form action="{{ route('admin.recreation-areas.destroy', $area) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Apakah Anda yakin ingin menghapus recreation area ini?')">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data recreation area.</td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>