<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant; // Pastikan Model Restaurant di-import

class RestaurantController extends Controller
{
    /**
     * Menampilkan daftar semua restoran.
     */
    public function index()
    {
        $restaurants = Restaurant::with('images')->latest()->paginate(9);
        return view('frontend.restaurants.index', compact('restaurants'));
    }

    /**
     * ==========================================================
     * TAMBAHKAN METODE BARU DI BAWAH INI
     * ==========================================================
     */
    public function show($slug)
    {
        // 1. Ambil data satu restoran berdasarkan slug-nya, atau tampilkan error 404 jika tidak ditemukan
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();

        // 2. Tampilkan view 'show.blade.php' dan kirim data restoran ke dalamnya
        return view('frontend.restaurants.show', compact('restaurant'));
    }
}