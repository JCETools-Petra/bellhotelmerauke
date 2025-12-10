<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Affiliate;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CaptureAffiliateReferral
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah ada parameter ?ref= di URL
        if ($request->has('ref')) {
            $referralCode = $request->query('ref');
            
            // Cari affiliate berdasarkan kode
            $affiliate = Affiliate::where('referral_code', $referralCode)
                            ->where('status', 'active') // Pastikan status aktif
                            ->first();

            if ($affiliate) {
                // Simpan ID affiliate ke Cookie selama 30 hari (43200 menit)
                // Nama cookie: 'referral_affiliate_id'
                Cookie::queue('referral_affiliate_id', $affiliate->id, 43200);
            }
        }

        return $next($request);
    }
}