<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class AffiliatePageController extends Controller
{
    /**
     * Menampilkan halaman editor untuk "Apa Itu Affiliate".
     */
    public function index()
    {
        // Ambil konten saat ini dari database
        $content = settings('affiliate_page_content', ''); // Defaultnya string kosong
        return view('admin.affiliate_page.index', compact('content'));
    }

    /**
     * Menyimpan konten halaman "Apa Itu Affiliate" yang sudah diupdate.
     */
    public function update(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'affiliate_page_content' => 'required|string',
        ]);

        // Simpan atau perbarui data di database
        Setting::updateOrCreate(
            ['key' => 'affiliate_page_content'],
            ['value' => $validated['affiliate_page_content']]
        );

        // Hapus cache agar perubahan langsung terlihat di frontend
        Cache::forget('site_settings');

        return redirect()->route('admin.affiliate_page.index')->with('success', 'Konten halaman affiliate berhasil diperbarui.');
    }
}