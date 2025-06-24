<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Helpers\TenantHelper;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tenant = TenantHelper::getCurrentTenant();
        $user = auth()->user();

        // Basic dashboard stats (you'll expand this later)
        $stats = [
            'total_customers' => 0, // Will be implemented with actual models
            'total_invoices' => 0,
            'total_products' => 0,
            'monthly_revenue' => 0,
        ];

        $trialDaysRemaining = TenantHelper::getTrialDaysRemaining();

        return view('tenant.dashboard', compact('tenant', 'user', 'stats', 'trialDaysRemaining'));
    }
}
