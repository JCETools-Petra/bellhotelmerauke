<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MiceRoom;

class MiceController extends Controller
{
    /**
     * Display a listing of the MICE rooms.
     */
    public function index()
    {
        // Ambil semua MICE Room yang tersedia
        // Menggunakan 'with('images')' agar gambar muncul (Eager Loading)
        $miceRooms = MiceRoom::where('is_available', true)
                             ->with('images') 
                             ->latest() // Ganti orderBy('order') dengan latest()
                             ->get();

        return view('frontend.mice.index', compact('miceRooms'));
    }

    /**
     * Display the specified MICE room.
     */
    public function show($slug)
    {
        // Ubah nama variabel dari $room menjadi $mice
        $mice = MiceRoom::where('slug', $slug)
                        ->where('is_available', true)
                        ->with('images')
                        ->firstOrFail();

        // Kirim sebagai 'mice'
        return view('frontend.mice.show', compact('mice'));
    }
}