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
            'mice_commission_rate' => 'required|numeric|min:0|max:100', // Validasi baru
        ]);

        $affiliate->update([
            'status' => $request->status,
            'commission_rate' => $request->commission_rate,
            'mice_commission_rate' => $request->mice_commission_rate, // Simpan data baru
        ]);

        return back()->with('success', 'Informasi Affiliate (Status & Komisi) berhasil diperbarui.');
    }
}