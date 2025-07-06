<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Tenant $tenant)
    {
        return view('tenant.inventory.index', compact('tenant'));
    }
}
