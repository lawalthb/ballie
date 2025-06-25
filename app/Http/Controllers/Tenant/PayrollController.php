<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        return view('tenant.payroll.index');
    }

    public function employees()
    {
        return view('tenant.payroll.employees');
    }

    public function payslips()
    {
        return view('tenant.payroll.payslips');
    }

    public function taxReports()
    {
        return view('tenant.payroll.tax-reports');
    }

    public function pensionReports()
    {
        return view('tenant.payroll.pension-reports');
    }
}
