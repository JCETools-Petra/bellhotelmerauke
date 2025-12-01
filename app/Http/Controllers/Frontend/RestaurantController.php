<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Menampilkan daftar semua restoran.
     */
    public function index()
    {
        // PERBAIKAN:
        // 1. Hapus 'where is_active' karena kolom tidak ada di database
        // 2. Gunakan 'latest()' karena kolom 'order' juga tidak ada
        // 3. Gunakan 'get()' agar menampilkan semua restoran di satu halaman (cocok dengan desain baru)
        $restaurants = Restaurant::with('images')
            ->latest() 
            ->get();

        return view('frontend.restaurants.index', compact('restaurants'));
    }

    /**
     * Menampilkan detail restoran.
     */
    public function show($slug)
    {
        // PERBAIKAN:
        // Hapus 'where is_active' di sini juga
        $restaurant = Restaurant::where('slug', $slug)
            ->with('images')
            ->firstOrFail();

        return view('frontend.restaurants.show', compact('restaurant'));
    }
}