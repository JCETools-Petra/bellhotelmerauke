<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function terms()
    {
        // Ambil data dari helper settings()
        $termsContent = settings('terms_and_conditions', 'Konten belum diatur.');
        return view('pages.terms', ['content' => $termsContent]);
    }

    public function affiliateInfo()
    {
        // Ambil data dari helper settings()
        $affiliateContent = settings('affiliate_page_content', 'Konten belum diatur.');
        return view('pages.affiliate_info', ['content' => $affiliateContent]);
    }
}