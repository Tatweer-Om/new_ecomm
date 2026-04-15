<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Modules\Dashboard\Http\Controllers\Api\v1\DashboardController;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $permission)
    {
        $user = auth('tenant')->user();

        if (!$user || $user->$permission != 1) {
            return redirect()->action([DashboardController::class, 'index']);
        }

        return $next($request);
    }

}
