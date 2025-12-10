<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\MiceKit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MiceKitController extends Controller
{
    /**
     * Menampilkan halaman Digital MICE Kit untuk afiliasi.
     */
    public function index()
    {
        $miceKits = MiceKit::latest()->get();
        return view('frontend.affiliate.mice_kit.index', compact('miceKits'));
    }

    /**
     * Menangani unduhan file yang aman (bisa untuk semua tipe).
     */
    public function download($id)
    {
        $miceKit = MiceKit::findOrFail($id);

        if (!Storage::disk('private')->exists($miceKit->path_or_link)) {
            abort(404, 'File not found.');
        }

        // Mengunduh file dengan nama aslinya
        return Storage::disk('private')->download($miceKit->path_or_link, $miceKit->original_filename);
    }
    
    /**
     * Menampilkan pratinjau file gambar yang aman.
     */
    public function preview($id)
    {
        $miceKit = MiceKit::findOrFail($id);

        // Pastikan file ada
        if (!Storage::disk('private')->exists($miceKit->path_or_link)) {
            abort(404, 'File not found.');
        }
        
        $file = Storage::disk('private')->get($miceKit->path_or_link);
        $mimeType = Storage::disk('private')->mimeType($miceKit->path_or_link);

        return response($file, 200)->header('Content-Type', $mimeType);
    }
    
    /**
     * Mengalirkan file video yang aman (logika diperbaiki).
     */
    public function stream($id)
    {
        $miceKit = MiceKit::findOrFail($id);

        // ======================= AWAL PERUBAHAN LOGIKA =======================
        // Cek apakah file ada di storage. Tidak perlu lagi cek 'type' di sini.
        if (!Storage::disk('private')->exists($miceKit->path_or_link)) {
            abort(404, 'Video not found.');
        }
        // ======================== AKHIR PERUBAHAN LOGIKA =======================

        return Storage::disk('private')->response($miceKit->path_or_link);
    }
}