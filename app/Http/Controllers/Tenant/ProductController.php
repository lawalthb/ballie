<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request, Tenant $tenant)
    {
        $products = Product::paginate(15);
        return view('tenant.products.index', compact('products', 'tenant'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create(Tenant $tenant)
    {
        return view('tenant.products.create', compact('tenant'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'category' => 'nullable|string|max:100',
            'tax_rate' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $product = Product::create($validated);

        return redirect()
            ->route('tenant.products.show', ['tenant' => $tenant->slug, 'product' => $product->id])
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product
     */
    public function show(Tenant $tenant, Product $product)
    {
        return view('tenant.products.show', compact('tenant', 'product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Tenant $tenant, Product $product)
    {
        return view('tenant.products.edit', compact('tenant', 'product'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Tenant $tenant, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'category' => 'nullable|string|max:100',
            'tax_rate' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $product->update($validated);

        return redirect()
            ->route('tenant.products.show', ['tenant' => $tenant->slug, 'product' => $product->id])
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product
     */
    public function destroy(Tenant $tenant, Product $product)
    {
        $product->delete();

        return redirect()
            ->route('tenant.products.index', ['tenant' => $tenant->slug])
            ->with('success', 'Product deleted successfully.');
    }
}