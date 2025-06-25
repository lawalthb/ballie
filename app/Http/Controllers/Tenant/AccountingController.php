<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function index()
    {
        return view('tenant.accounting.index');
    }

    public function chartOfAccounts()
    {
        return view('tenant.accounting.chart-of-accounts');
    }

    public function journalEntries()
    {
        return view('tenant.accounting.journal-entries');
    }

    public function trialBalance()
    {
        return view('tenant.accounting.trial-balance');
    }

    public function financialStatements()
    {
        return view('tenant.accounting.financial-statements');
    }
}
