@extends('layouts.app') 

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-6 flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pesan Paket MICE (Pay at Hotel)') }}
            </h2>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($miceKits as $kit)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col h-full">
                @if($kit->image)
                <img src="{{ asset('storage/' . $kit->image) }}" class="w-full h-48 object-cover" alt="{{ $kit->name }}">
                @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">
                    No Image
                </div>
                @endif
                
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold mb-2">{{ $kit->name }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($kit->description, 100) }}</p>
                        <p class="text-xl font-bold text-indigo-600">Rp {{ number_format($kit->price, 0, ',', '.') }} / pax</p>
                    </div>

                    <div class="mt-4">
                        <button type="button" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                data-bs-toggle="modal" 
                                data-bs-target="#modalBook-{{ $kit->id }}">
                            Booking Sekarang
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
                 id="modalBook-{{ $kit->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog relative w-auto pointer-events-none">
                    <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                        
                        <form action="{{ route('affiliate.mice_booking.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="mice_kit_id" value="{{ $kit->id }}">

                            <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                                <h5 class="text-xl font-medium leading-normal text-gray-800">
                                    Booking: {{ $kit->name }}
                                </h5>
                                <button type="button" class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            
                            <div class="modal-body relative p-4">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Check-in</label>
                                    <input type="date" name="check_in_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Pax (Orang)</label>
                                    <input type="number" name="pax" min="1" value="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Catatan (Optional)</label>
                                    <textarea name="note" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3"></textarea>
                                </div>
                                <div class="text-sm text-gray-500">
                                    *Pembayaran akan dilakukan saat check-in di hotel (Pay at Hotel).
                                </div>
                            </div>
                            
                            <div class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
                                <button type="button" class="px-6 py-2.5 bg-gray-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-800 active:shadow-lg transition duration-150 ease-in-out" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-indigo-700 hover:shadow-lg focus:bg-indigo-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-indigo-800 active:shadow-lg transition duration-150 ease-in-out ml-1">Konfirmasi Pesanan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection