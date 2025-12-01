<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    /**
     * Menampilkan halaman kontak.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Variabel $settings sudah disediakan secara global oleh ViewServiceProvider.
        // Jadi kita cukup mereturn view saja.
        return view('frontend.contact.index');
    }
}