<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnboardingCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = app('tenant');

        if (!$tenant->onboarding_completed) {
            return redirect()->route('tenant.onboarding', ['tenant' => $tenant->slug]);
        }

        return $next($request);
    }
}
