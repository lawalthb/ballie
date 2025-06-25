<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;

class DashboardController extends Controller
{
    public function index(Tenant $tenant)
    {
        $user = Auth::user();

        // Sample dashboard data - replace with actual data from your models
        $dashboardData = [
            'total_revenue' => 2450000,
            'total_invoices' => 247,
            'pending_payments' => 850000,
            'overdue_invoices' => 16,
            'recent_invoices' => [
                ['id' => 'INV-2024-001', 'customer' => 'Adebayo Enterprises', 'amount' => 125000, 'status' => 'paid'],
                ['id' => 'INV-2024-002', 'customer' => 'Kemi Okafor Ltd', 'amount' => 85500, 'status' => 'sent'],
                ['id' => 'INV-2024-003', 'customer' => 'Lagos Trading Co.', 'amount' => 67200, 'status' => 'overdue'],
            ],
            'low_stock_items' => [
                ['name' => 'Office Paper A4', 'current_stock' => 5, 'reorder_level' => 20],
                ['name' => 'Printer Ink Cartridge', 'current_stock' => 2, 'reorder_level' => 10],
                ['name' => 'Stapler', 'current_stock' => 1, 'reorder_level' => 5],
            ],
            'recent_activities' => [
                ['type' => 'invoice_created', 'description' => 'Invoice INV-2024-005 created for Port Harcourt Ltd', 'time' => '2 hours ago'],
                ['type' => 'payment_received', 'description' => 'Payment of ₦125,000 received from Adebayo Enterprises', 'time' => '4 hours ago'],
                ['type' => 'product_added', 'description' => 'New product "Wireless Mouse" added to inventory', 'time' => '6 hours ago'],
            ]
        ];

        return view('tenant.dashboard.index', compact('dashboardData', 'tenant'));
    }
}
