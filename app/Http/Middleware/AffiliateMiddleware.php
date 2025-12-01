<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Affiliate;
use App\Models\AffiliateVisit;

class AffiliateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah ada parameter 'ref' di URL
        if ($request->has('ref')) {
            $referralCode = $request->query('ref');

            $affiliate = Affiliate::where('referral_code', $referralCode)
                                  ->where('status', 'active')
                                  ->first();

            if ($affiliate) {
                // Buat cookie yang berlaku selama 30 hari (43200 menit)
                $cookie = cookie('affiliate_id', $affiliate->id, 43200);

                // Catat kunjungan
                AffiliateVisit::create([
                    'affiliate_id' => $affiliate->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                // Lanjutkan ke halaman yang dituju DAN "tempelkan" cookie
                return $next($request)->withCookie($cookie);
            }
        }

        // Jika tidak ada 'ref' di URL, lanjutkan saja seperti biasa tanpa melakukan apa-apa
        return $next($request);
    }
}