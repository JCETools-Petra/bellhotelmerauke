<?php

namespace App\Http\Middleware;

use App\Models\WebsiteVisit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackWebsiteVisit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tracking for admin routes and API routes to avoid clutter
        if (!$request->is('admin/*') && !$request->is('api/*')) {
            // Track visit after the response is sent (non-blocking)
            app()->terminating(function () use ($request) {
                try {
                    WebsiteVisit::create([
                        'user_id' => auth()->id(),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'url' => $request->fullUrl(),
                        'referrer' => $request->header('referer'),
                    ]);
                } catch (\Exception $e) {
                    // Silently fail to prevent disrupting the application
                    logger()->error('Failed to track website visit: ' . $e->getMessage());
                }
            });
        }

        return $next($request);
    }
}
