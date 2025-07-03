<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Customer;
use App\Models\Product;

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

        // Get dashboard statistics
        $customerCount = Customer::where('tenant_id', $currentTenant->id)->count();
        $activeCustomers = Customer::where('tenant_id', $currentTenant->id)
            ->where('status', 'active')
            ->count();

        // You can add more statistics as needed
        // $productCount = Product::where('tenant_id', $currentTenant->id)->count();
        // $invoiceCount = Invoice::where('tenant_id', $currentTenant->id)->count();
        // $totalRevenue = Invoice::where('tenant_id', $currentTenant->id)->sum('total');

        // Sample chart data - replace with actual data from your models
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'revenue' => [120000, 190000, 300000, 500000, 200000, 300000, 450000, 600000, 750000, 850000, 900000, 1000000],
            'expenses' => [80000, 120000, 180000, 250000, 150000, 200000, 280000, 350000, 400000, 450000, 500000, 550000]
        ];

        return view('tenant.dashboard.index', [
            'currentTenant' => $currentTenant,
            'user' => $user,
            'tenant' => $currentTenant,
            'customerCount' => $customerCount,
            'activeCustomers' => $activeCustomers,
            'chartData' => $chartData,
            // Add other data as needed
        ]);
    }
}
