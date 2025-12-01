<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class AffiliateController extends Controller
{
    public function create()
    {
        // Jika user sudah login dan sudah jadi affiliate, redirect ke dashboard
        if (Auth::check() && Auth::user()->affiliate) {
            return redirect()->route('affiliate.dashboard');
        }

        return view('frontend.affiliate.create');
    }

    public function store(Request $request)
    {
        // 1. Tentukan Validasi Berdasarkan Status Login
        if (Auth::check()) {
            // Jika sudah login, validasi data affiliate saja
            $validated = $request->validate([
                'phone' => ['required', 'string', 'max:20'],
                'bank_name' => ['required', 'string', 'max:50'],
                'bank_account_number' => ['required', 'string', 'max:50'],
                'bank_account_holder' => ['required', 'string', 'max:100'],
            ]);
            
            $user = Auth::user();
        } else {
            // Jika belum login (Guest), validasi data User + Affiliate
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'phone' => ['required', 'string', 'max:20'], // Phone bisa masuk ke user atau affiliate, kita simpan di keduanya untuk aman
                'bank_name' => ['required', 'string', 'max:50'],
                'bank_account_number' => ['required', 'string', 'max:50'],
                'bank_account_holder' => ['required', 'string', 'max:100'],
            ]);

            // Buat User Baru
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'user', // Default role user biasa, nanti jadi affiliate via relasi
            ]);
            
            // Login otomatis setelah register
            Auth::login($user);
        }

        // 2. Cek apakah user ini sudah jadi affiliate sebelumnya
        if ($user->affiliate) {
            return redirect()->route('affiliate.dashboard')->with('info', 'Anda sudah terdaftar sebagai affiliate.');
        }

        // 3. Simpan Data Affiliate (Termasuk Data Bank)
        // Pastikan tabel 'affiliates' punya kolom-kolom ini
        Affiliate::create([
            'user_id' => $user->id,
            'referral_code' => strtoupper(Str::random(10)),
            'commission_rate' => 5.00, // Default rate
            'status' => 'pending',     // Langsung aktif atau 'pending' tergantung kebijakan
            'bank_name' => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_holder' => $validated['bank_account_holder'],
        ]);
        
        // Opsional: Update no HP user jika belum ada
        if (empty($user->phone)) {
            $user->update(['phone' => $validated['phone']]);
        }

        return redirect()->route('affiliate.dashboard')->with('success', 'Selamat! Anda telah terdaftar sebagai Affiliate.');
    }
}