<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboarding
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = app('tenant');

        // If tenant hasn't completed onboarding, redirect to onboarding
        if (!$tenant->onboarding_completed) {
            // Allow access to onboarding routes
            if ($request->routeIs('tenant.onboarding*')) {
                return $next($request);
            }

            return redirect()->route('tenant.onboarding', ['tenant' => $tenant->slug]);
        }

        // If onboarding is completed but trying to access onboarding routes
        if ($request->routeIs('tenant.onboarding*')) {
            return redirect()->route('tenant.dashboard', ['tenant' => $tenant->slug]);
        }

        return $next($request);
    }
}
