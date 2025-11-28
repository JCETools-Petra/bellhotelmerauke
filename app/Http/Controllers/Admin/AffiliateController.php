<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    public function index()
    {
        $affiliates = Affiliate::with('user')->latest()->paginate(15);
        return view('admin.affiliates.index', compact('affiliates'));
    }

    public function update(Request $request, Affiliate $affiliate)
    {
        $request->validate([
            'status' => 'required|in:pending,active,inactive',
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $affiliate->update([
            'status' => $request->status,
            'commission_rate' => $request->commission_rate,
        ]);

        return back()->with('success', 'Affiliate status has been updated.');
    }
}