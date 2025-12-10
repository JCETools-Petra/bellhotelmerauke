<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateReview;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index()
    {
        $reviews = AffiliateReview::with(['affiliate.user'])->latest()->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleVisibility($id)
    {
        $review = AffiliateReview::findOrFail($id);
        $review->is_visible = !$review->is_visible;
        $review->save();

        $status = $review->is_visible ? 'ditampilkan' : 'disembunyikan';
        return back()->with('success', "Review berhasil $status.");
    }

    public function destroy($id)
    {
        $review = AffiliateReview::findOrFail($id);
        $review->delete();

        return back()->with('success', 'Review berhasil dihapus.');
    }
}