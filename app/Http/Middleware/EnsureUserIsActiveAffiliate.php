<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActiveAffiliate
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user sudah login DAN memiliki data affiliate yang statusnya 'active'
        if (Auth::check() && Auth::user()->affiliate && Auth::user()->affiliate->status == 'active') {
            return $next($request);
        }

        // Jika tidak, tolak akses dan kembalikan ke halaman utama
        return redirect('/')->with('error', 'You do not have permission to access this page.');
    }
}