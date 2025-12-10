<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pengaturan Maintenance Mode
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.maintenance.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <h3 class="text-lg font-medium text-gray-900">Status Website</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Gunakan tombol di bawah ini untuk mengaktifkan atau menonaktifkan mode perbaikan untuk seluruh website (kecuali halaman admin).
                            </p>
                            
                            <div class="custom-control custom-switch mt-4">
                                <input type="checkbox" class="custom-control-input" id="maintenanceSwitch" name="maintenance_mode" {{ $isDown ? 'checked' : '' }}>
                                <label class="custom-control-label" for="maintenanceSwitch">
                                    @if($isDown)
                                        <span class="text-danger font-weight-bold">Maintenance Mode AKTIF</span>
                                    @else
                                        <span class="text-success font-weight-bold">Website LIVE</span>
                                    @endif
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>