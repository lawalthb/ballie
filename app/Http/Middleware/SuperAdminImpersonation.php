<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminImpersonation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if super admin is impersonating
        if (session()->has('impersonating')) {
            $impersonationData = session('impersonating');

            // Add impersonation banner data to views
            view()->share('impersonating', $impersonationData);
        }

        return $next($request);
    }
}
