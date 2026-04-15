<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TenantAuthenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        // Central domain - skip tenant auth
        if ($request->getHost() === config('app.url_host')) {
            return $next($request);
        }

        // Exclude public tenant pages
        if ($request->is('tlogin') || $request->is('tshowLogin')) {
            return $next($request);
        }

        // Check tenant login
        if (tenancy()->initialized) {
            // tenant domain → enforce tenant auth
            if (!Auth::guard('tenant')->check()) {
                return redirect()->route('tshowLogin');
            }
        }

        return $next($request);
    }
}
