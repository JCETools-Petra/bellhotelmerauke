<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function terms()
    {
        // Tidak perlu mengambil data manual, karena $settings sudah global (ViewServiceProvider)
        // PERBAIKAN: Arahkan ke frontend.pages.terms
        return view('frontend.pages.terms');
    }

    public function affiliateInfo()
    {
        // Ambil data dari helper settings()
        $affiliateContent = settings('affiliate_page_content', 'Konten belum diatur.');
        return view('pages.affiliate_info', ['content' => $affiliateContent]);
    }
}