<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheControlMiddleware
{
    /**
     * Apply cache-control headers based on the request path.
     *
     * This middleware runs in the 'web' middleware group.
     *
     * - Static assets (storage/*): cache for 30 days (immutable)
     * - Admin HTML pages: no-cache (data must be fresh)
     * - Everything else: short public cache (5 min)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$response instanceof Response) {
            return $response;
        }

        $path = $request->path();

        // Cache static storage files (images) for 30 days
        if (str_starts_with($path, 'storage/')) {
            $response->headers->set('Cache-Control', 'public, max-age=2592000, immutable');
            return $response;
        }

        // Admin pages: no caching (order status, product edits must be fresh)
        if (str_starts_with($path, 'admin/')) {
            $response->headers->set('Cache-Control', 'no-cache, private, no-store, must-revalidate');
            return $response;
        }

        // HTML pages: short public cache (5 minutes)
        $response->headers->set('Cache-Control', 'public, max-age=300, must-revalidate');

        return $response;
    }
}
