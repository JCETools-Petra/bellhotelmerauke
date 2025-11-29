<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MaintenanceController extends Controller
{
    /**
     * Menampilkan halaman pengaturan maintenance mode.
     */
    public function index()
    {
        // Cek apakah aplikasi sedang dalam maintenance mode
        $isDown = app()->isDownForMaintenance();

        return view('admin.maintenance.index', compact('isDown'));
    }

    /**
     * Mengaktifkan atau menonaktifkan maintenance mode.
     */
    public function update(Request $request)
    {
        if ($request->has('maintenance_mode')) {
            // Aktifkan Maintenance Mode
            Artisan::call('down');
            $message = 'Maintenance mode telah diaktifkan.';
        } else {
            // Nonaktifkan Maintenance Mode
            Artisan::call('up');
            $message = 'Maintenance mode telah dinonaktifkan.';
        }

        return redirect()->route('admin.maintenance.index')->with('success', $message);
    }
}