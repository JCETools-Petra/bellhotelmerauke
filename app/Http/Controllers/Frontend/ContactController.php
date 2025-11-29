<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
// Kita tidak memerlukan 'use Cache' atau 'use App\Models\ContactSetting' lagi

class ContactController extends Controller
{
    /**
     * Menampilkan halaman kontak.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Tidak perlu mengambil data di sini.
        // Variabel $settings sudah disediakan secara global untuk semua view
        // oleh ViewServiceProvider. Cukup kembalikan view-nya saja.
        return view('frontend.contact.index');
    }
}