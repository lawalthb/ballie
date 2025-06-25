<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index()
    {
        return view('tenant.pos.index');
    }

    public function sales()
    {
        return view('tenant.pos.sales');
    }

    public function receipts()
    {
        return view('tenant.pos.receipts');
    }

    public function processSale(Request $request)
    {
        // Process POS sale logic
        return response()->json(['success' => true, 'message' => 'Sale processed successfully']);
    }
}
