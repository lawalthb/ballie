<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index(Request $request, Tenant $tenant)
    {

        // Get current tenant from route parameter
        $currentTenant = $tenant;

        // Get authenticated user
        $user = auth()->user();

        // You would typically load dashboard data here
        // For example:
        // $invoiceCount = Invoice::count();
        // $customerCount = Customer::count();
        // $productCount = Product::count();
        // $recentInvoices = Invoice::latest()->take(5)->get();
        // $recentCustomers = Customer::latest()->take(5)->get();

        return view('tenant.dashboard.index', [
            'currentTenant' => $currentTenant,
            'user' => $user,
            'tenant' => $currentTenant,
            // Pass other data as needed
        ]);
    }
}
