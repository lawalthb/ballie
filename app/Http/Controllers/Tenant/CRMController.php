<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CRMController extends Controller
{
    public function index()
    {
        return view('tenant.crm.index');
    }

    public function customers()
    {
        return view('tenant.crm.customers');
    }

    public function leads()
    {
        return view('tenant.crm.leads');
    }

    public function salesPipeline()
    {
        return view('tenant.crm.sales-pipeline');
    }

    public function contacts()
    {
        return view('tenant.crm.contacts');
    }

    public function activities()
    {
        return view('tenant.crm.activities');
    }
}
