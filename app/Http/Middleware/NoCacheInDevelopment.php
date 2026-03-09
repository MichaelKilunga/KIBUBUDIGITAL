<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoCacheInDevelopment
{
    /**
     * Handle an incoming request.
     * In local/development environments, instruct the browser not to cache
     * any responses so that code changes are immediately visible on reload.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (app()->environment('local', 'development')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
