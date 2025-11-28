<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika pengguna sudah login DAN memiliki peran 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Jika ya, izinkan akses
            return $next($request);
        }

        // Jika tidak, tolak akses dengan halaman "403 Forbidden"
        abort(403, 'ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.');
    }
}