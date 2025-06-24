<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class TenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenantSlug = $request->route('tenant');

        if (!$tenantSlug) {
            abort(404, 'Tenant not specified');
        }

        $tenant = Tenant::where('slug', $tenantSlug)->first();

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        if (!$tenant->canAccess()) {
            abort(403, 'Tenant access suspended or expired');
        }

        // Set tenant in the application context
        app()->instance('tenant', $tenant);

        // Share tenant with all views
        view()->share('tenant', $tenant);

        // Set tenant context for the request
        $request->merge(['current_tenant' => $tenant]);

        return $next($request);
    }
}
