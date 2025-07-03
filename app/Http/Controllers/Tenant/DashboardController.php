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

        // Sample chart data (in a real app, this would come from your database)
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'revenue' => [120000, 190000, 300000, 500000, 200000, 300000, 450000, 600000, 750000, 850000, 900000, 1000000],
            'expenses' => [80000, 120000, 180000, 250000, 150000, 200000, 280000, 350000, 400000, 450000, 500000, 550000]
        ];

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
            'chartData' => $chartData,
            // Pass other data as needed
        ]);
    }
}
