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
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantSlug = $request->route('tenant');

        // If $tenantSlug is a string, fetch the Tenant model
        if (is_string($tenantSlug)) {
            $tenant = Tenant::where('slug', $tenantSlug)->firstOrFail();
        } else {
            $tenant = $tenantSlug;
        }

        if (!$tenant->onboarding_completed_at) {
            return redirect()->route('tenant.onboarding.index', ['tenant' => $tenant->slug])
                ->with('warning', 'Please complete the onboarding process first.');
        }

        return $next($request);
    }
}
