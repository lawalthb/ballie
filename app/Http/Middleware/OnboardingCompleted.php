<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class OnboardingCompleted
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get tenant from route parameter
        $tenant = $request->route('tenant');

        // Ensure tenant is a valid Tenant model instance
        if (!($tenant instanceof Tenant)) {
            $tenant = Tenant::where('slug', $tenant)->first();
        }

        // If no valid tenant found, redirect to home
        if (!$tenant) {
            return redirect()->route('home');
        }

        // Check onboarding status
        if (!$tenant->onboarding_completed_at) {
            // Don't redirect if already on onboarding routes
            if (!$request->routeIs('onboarding.*')) {
                return redirect()->route('tenant.onboarding.index', ['tenant' => $tenant->slug]);
            }
        }

        return $next($request);
    }
}
