<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\AffiliateReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliateReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:500',
        ]);

        $affiliate = Auth::user()->affiliate;

        if (!$affiliate) {
            return back()->with('error', 'Akun afiliasi tidak ditemukan.');
        }

        // Gunakan updateOrCreate agar 1 affiliate hanya punya 1 review (bisa diedit)
        AffiliateReview::updateOrCreate(
            ['affiliate_id' => $affiliate->id],
            [
                'rating' => $request->rating,
                'review' => $request->review,
                'is_visible' => false, // Reset ke hidden jika diedit agar admin cek ulang
            ]
        );

        return back()->with('success', 'Review berhasil dikirim dan menunggu persetujuan Admin.');
    }
}