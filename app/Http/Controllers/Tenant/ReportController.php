<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('tenant.reports.index');
    }

    public function financial()
    {
        return view('tenant.reports.financial');
    }

    public function sales()
    {
        return view('tenant.reports.sales');
    }

    public function inventory()
    {
        return view('tenant.reports.inventory');
    }

    public function customer()
    {
        return view('tenant.reports.customer');
    }

    public function tax()
    {
        return view('tenant.reports.tax');
    }
}
