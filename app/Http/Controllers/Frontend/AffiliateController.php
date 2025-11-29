<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Affiliate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AffiliateController extends Controller
{
    public function create()
    {
        return view('frontend.affiliate.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^08[0-9]{8,11}$/', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Buat user baru dengan semua data yang divalidasi
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'], // Pastikan ini ada
            'password' => Hash::make($validated['password']),
            'role' => 'affiliate',
        ]);

        // Buat data affiliate yang terhubung dengan user
        Affiliate::create([
            'user_id' => $user->id,
            'referral_code' => strtoupper(Str::random(8)),
            'status' => 'pending',
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Akun Anda akan kami review terlebih dahulu sebelum bisa digunakan.');
    }
}