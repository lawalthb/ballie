<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        return view('tenant.inventory.index');
    }

    public function products()
    {
        return view('tenant.inventory.products');
    }

    public function categories()
    {
        return view('tenant.inventory.categories');
    }

    public function suppliers()
    {
        return view('tenant.inventory.suppliers');
    }

    public function stockMovements()
    {
        return view('tenant.inventory.stock-movements');
    }

    public function lowStock()
    {
        return view('tenant.inventory.low-stock');
    }
}
