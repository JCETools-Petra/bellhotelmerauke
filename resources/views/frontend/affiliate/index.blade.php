<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Affiliate Management') }}
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
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Referal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Komisi (%)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($affiliates as $affiliate)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $affiliate->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $affiliate->user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono bg-gray-100">{{ $affiliate->referral_code }}</td>
                                    <form action="{{ route('admin.affiliates.update', $affiliate->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" step="0.01" name="commission_rate" value="{{ $affiliate->commission_rate }}" class="w-24 border-gray-300 rounded-md shadow-sm">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select name="status" class="border-gray-300 rounded-md shadow-sm">
                                                <option value="pending" @selected($affiliate->status == 'pending')>Pending</option>
                                                <option value="active" @selected($affiliate->status == 'active')>Active</option>
                                                <option value="inactive" @selected($affiliate->status == 'inactive')>Inactive</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $affiliate->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button type="submit" class="text-indigo-600 hover:text-indigo-900">Update</button>
                                        </td>
                                    </form>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada pendaftar affiliate.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $affiliates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>