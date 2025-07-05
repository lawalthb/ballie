<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;

class CrmController extends Controller
{
    /**
     * Display the CRM dashboard
     */
    public function index(Request $request, Tenant $tenant)
    {
        $currentTenant = $tenant;
        $user = auth()->user();

        // You would typically load CRM data here
        // For example:
        // $totalCustomers = Customer::where('tenant_id', $tenant->id)->count();
        // $activeCustomers = Customer::where('tenant_id', $tenant->id)->where('status', 'active')->count();
        // $recentCustomers = Customer::where('tenant_id', $tenant->id)->latest()->take(5)->get();

        return view('tenant.crm.index', [
            'currentTenant' => $currentTenant,
            'user' => $user,
            'tenant' => $currentTenant,
        ]);
    }
}
