<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Affiliate Management') }}
            </h2>
            <a href="{{ route('admin.commissions.create') }}" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 transition">
                Add Manual Commission
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
                <div class="p-4 text-gray-900 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Nama / Email</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room %</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MICE %</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daftar</th>
                                <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($affiliates as $affiliate)
                                <tr class="hover:bg-gray-50">
                                    {{-- Kolom 1: Nama & Kontak Digabung agar Hemat Tempat --}}
                                    <td class="px-3 py-3 align-top">
                                        <div class="font-bold text-gray-900">{{ $affiliate->user->name }}</div>
                                        <div class="text-xs text-gray-500 break-all">{{ $affiliate->user->email }}</div>
                                        <div class="text-xs text-gray-400 mt-1">{{ $affiliate->user->phone }}</div>
                                    </td>

                                    {{-- Kolom 2: Kode Referal --}}
                                    <td class="px-3 py-3 whitespace-nowrap align-top">
                                        <span class="px-2 py-1 font-mono text-xs bg-gray-100 text-gray-800 rounded border border-gray-200">
                                            {{ $affiliate->referral_code }}
                                        </span>
                                    </td>
                                    
                                    {{-- Form Update (Membungkus sisa kolom) --}}
                                    <form action="{{ route('admin.affiliates.update', $affiliate->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        
                                        {{-- Kolom 3: Room % --}}
                                        <td class="px-3 py-3 whitespace-nowrap align-top">
                                            <input type="number" step="0.01" name="commission_rate" value="{{ $affiliate->commission_rate }}" 
                                                   class="w-16 border-gray-300 rounded-md shadow-sm text-xs focus:ring-indigo-500 focus:border-indigo-500 p-1.5 text-center" 
                                                   title="Komisi Kamar">
                                        </td>

                                        {{-- Kolom 4: MICE % --}}
                                        <td class="px-3 py-3 whitespace-nowrap align-top">
                                            <input type="number" step="0.01" name="mice_commission_rate" value="{{ $affiliate->mice_commission_rate }}" 
                                                   class="w-16 border-gray-300 rounded-md shadow-sm text-xs focus:ring-indigo-500 focus:border-indigo-500 p-1.5 text-center"
                                                   title="Komisi MICE">
                                        </td>

                                        {{-- Kolom 5: Status --}}
                                        <td class="px-3 py-3 whitespace-nowrap align-top">
                                            <select name="status" class="border-gray-300 rounded-md shadow-sm text-xs focus:ring-indigo-500 focus:border-indigo-500 py-1 px-1 w-24">
                                                <option value="pending" @selected($affiliate->status == 'pending')>Pending</option>
                                                <option value="active" @selected($affiliate->status == 'active')>Active</option>
                                                <option value="inactive" @selected($affiliate->status == 'inactive')>Inactive</option>
                                            </select>
                                        </td>

                                        {{-- Kolom 6: Tanggal --}}
                                        <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-500 align-middle">
                                            {{ $affiliate->created_at->format('d/m/y') }}
                                        </td>

                                        {{-- Kolom 7: Tombol Simpan --}}
                                        <td class="px-3 py-3 whitespace-nowrap text-right align-middle">
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded shadow text-xs font-bold transition-colors">
                                                Simpan
                                            </button>
                                        </td>
                                    </form>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada pendaftar affiliate.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4 px-2">
                        {{ $affiliates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>