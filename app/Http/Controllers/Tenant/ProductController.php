<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request, Tenant $tenant)
    {
        $query = Product::where('tenant_id', $tenant->id);

        // Apply filters
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        if ($request->has('status') && $request->status != '') {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'low_stock':
                    $query->where('maintain_stock', true)
                          ->whereColumn('current_stock', '<=', 'reorder_level');
                    break;
                case 'out_of_stock':
                    $query->where('maintain_stock', true)
                          ->where('current_stock', '<=', 0);
                    break;
            }
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get stats
        $stats = [
            'total_products' => Product::where('tenant_id', tenant()->id)->count(),
            'total_items' => Product::where('tenant_id', tenant()->id)->where('type', 'item')->count(),
            'total_services' => Product::where('tenant_id', tenant()->id)->where('type', 'service')->count(),
            'low_stock' => Product::where('tenant_id', tenant()->id)
                                 ->where('maintain_stock', true)
                                 ->whereColumn('current_stock', '<=', 'reorder_level')
                                 ->count(),
        ];

        // Get unique categories
        $categories = Product::where('tenant_id', tenant()->id)
                            ->whereNotNull('category')
                            ->distinct()
                            ->pluck('category')
                            ->filter()
                            ->sort();

        return view('tenant.products.index', compact('products', 'stats', 'categories', 'tenant'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(Tenant $tenant)
    {
        return view('tenant.products.create', compact('tenant'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:item,service',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'hsn_code' => 'nullable|string|max:20',
            'purchase_rate' => 'required|numeric|min:0',
            'sales_rate' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'minimum_selling_price' => 'nullable|numeric|min:0',
            'primary_unit' => 'required|string|max:20',
            'secondary_unit' => 'nullable|string|max:20',
            'opening_stock' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|numeric|min:0',
            'maximum_stock' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'tax_type' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'weight' => 'nullable|numeric|min:0',
            'weight_unit' => 'nullable|string|max:10',
            'barcode' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'maintain_stock' => 'boolean',
            'is_active' => 'boolean',
            'is_saleable' => 'boolean',
            'is_purchasable' => 'boolean',
            'track_serial_numbers' => 'boolean',
            'track_batch_numbers' => 'boolean',
            'perishable' => 'boolean',
            'tax_inclusive' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $productData = $request->all();
            $productData['tenant_id'] = tenant()->id;
            $productData['created_by'] = auth()->id();

                    // Handle image upload
                    if ($request->hasFile('image')) {
                        // Delete old image if exists
                        if ($product->image_path) {
                            Storage::disk('public')->delete($product->image_path);
                        }

                        $image = $request->file('image');
                        $imagePath = $image->store('products', 'public');
                        $productData['image_path'] = $imagePath;
                    }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('products', 'public');
                $productData['image_path'] = $imagePath;
            }

            // Set default values for checkboxes
            $productData['maintain_stock'] = $request->has('maintain_stock');
            $productData['is_active'] = $request->has('is_active');
            $productData['is_saleable'] = $request->has('is_saleable');
            $productData['is_purchasable'] = $request->has('is_purchasable');
            $productData['track_serial_numbers'] = $request->has('track_serial_numbers');
            $productData['track_batch_numbers'] = $request->has('track_batch_numbers');
            $productData['perishable'] = $request->has('perishable');
            $productData['tax_inclusive'] = $request->has('tax_inclusive');

            // For services, set maintain_stock to false
            if ($productData['type'] === 'service') {
                $productData['maintain_stock'] = false;
                $productData['opening_stock'] = 0;
                $productData['current_stock'] = 0;
                $productData['available_stock'] = 0;
            } else {
                $productData['current_stock'] = $productData['opening_stock'] ?? 0;
                $productData['available_stock'] = $productData['opening_stock'] ?? 0;
            }

            $product = Product::create($productData);

            // Create opening stock movement if there's opening stock
            if ($product->type === 'item' && $product->maintain_stock && $product->opening_stock > 0) {
                StockMovement::create([
                    'tenant_id' => tenant()->id,
                    'product_id' => $product->id,
                    'type' => 'opening_stock',
                    'quantity' => $product->opening_stock,
                    'old_stock' => 0,
                    'new_stock' => $product->opening_stock,
                    'rate' => $product->purchase_rate,
                    'reference' => 'Opening Stock',
                    'remarks' => 'Opening stock entry',
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return redirect()->route('tenant.products.index', ['tenant' => tenant()->slug])
                ->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded image if product creation failed
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return redirect()->back()
                ->with('error', 'An error occurred while creating the product. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        // Ensure product belongs to current tenant
        if ($product->tenant_id !== tenant()->id) {
            abort(404);
        }

        $product->load(['stockMovements' => function($query) {
            $query->orderBy('created_at', 'desc')->take(20);
        }]);

        return view('tenant.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        // Ensure product belongs to current tenant
        if ($product->tenant_id !== tenant()->id) {
            abort(404);
        }

        return view('tenant.products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Ensure product belongs to current tenant
        if ($product->tenant_id !== tenant()->id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:item,service',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'hsn_code' => 'nullable|string|max:20',
            'purchase_rate' => 'required|numeric|min:0',
            'sales_rate' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'minimum_selling_price' => 'nullable|numeric|min:0',
            'primary_unit' => 'required|string|max:20',
            'secondary_unit' => 'nullable|string|max:20',
            'reorder_level' => 'nullable|numeric|min:0',
            'maximum_stock' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'tax_type' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'weight' => 'nullable|numeric|min:0',
            'weight_unit' => 'nullable|string|max:10',
            'barcode' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'maintain_stock' => 'boolean',
            'is_active' => 'boolean',
            'is_saleable' => 'boolean',
            'is_purchasable' => 'boolean',
            'track_serial_numbers' => 'boolean',
            'track_batch_numbers' => 'boolean',
            'perishable' => 'boolean',
            'tax_inclusive' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $productData = $request->all();
            $productData['updated_by'] = auth()->id();

                      // Handle image upload
                      if ($request->hasFile('image')) {
                        // Delete old image if exists
                        if ($product->image_path) {
                            Storage::disk('public')->delete($product->image_path);
                        }

                        $image = $request->file('image');
                        $imagePath = $image->store('products', 'public');
                        $productData['image_path'] = $imagePath;
                    }

                    // Set default values for checkboxes
                    $productData['maintain_stock'] = $request->has('maintain_stock');
                    $productData['is_active'] = $request->has('is_active');
                    $productData['is_saleable'] = $request->has('is_saleable');
                    $productData['is_purchasable'] = $request->has('is_purchasable');
                    $productData['track_serial_numbers'] = $request->has('track_serial_numbers');
                    $productData['track_batch_numbers'] = $request->has('track_batch_numbers');
                    $productData['perishable'] = $request->has('perishable');
                    $productData['tax_inclusive'] = $request->has('tax_inclusive');

                    // For services, set maintain_stock to false
                    if ($productData['type'] === 'service') {
                        $productData['maintain_stock'] = false;
                    }

                    $product->update($productData);

                    DB::commit();

                    return redirect()->route('tenant.products.index', ['tenant' => tenant()->slug])
                        ->with('success', 'Product updated successfully.');

                } catch (\Exception $e) {
                    DB::rollBack();

                    return redirect()->back()
                        ->with('error', 'An error occurred while updating the product. Please try again.')
                        ->withInput();
                }
            }

            /**
             * Remove the specified product from storage.
             */
            public function destroy(Product $product)
            {
                // Ensure product belongs to current tenant
                if ($product->tenant_id !== tenant()->id) {
                    abort(404);
                }

                try {
                    DB::beginTransaction();

                    // Check if product has any transactions
                    // You would check for invoices, purchase orders, etc.
                    // For now, we'll just check if it has stock movements
                    $hasTransactions = $product->stockMovements()->count() > 0;

                    if ($hasTransactions) {
                        return redirect()->route('tenant.products.index', ['tenant' => tenant()->slug])
                            ->with('error', 'Cannot delete product as it has transaction history.');
                    }

                    // Delete product image if exists
                    if ($product->image_path) {
                        Storage::disk('public')->delete($product->image_path);
                    }

                    $product->delete();

                    DB::commit();

                    return redirect()->route('tenant.products.index', ['tenant' => tenant()->slug])
                        ->with('success', 'Product deleted successfully.');

                } catch (\Exception $e) {
                    DB::rollBack();

                    return redirect()->route('tenant.products.index', ['tenant' => tenant()->slug])
                        ->with('error', 'An error occurred while deleting the product. Please try again.');
                }
            }

            /**
             * Bulk actions for products
             */
            public function bulkAction(Request $request)
            {
                $validator = Validator::make($request->all(), [
                    'action' => 'required|in:activate,deactivate,delete',
                    'products' => 'required|array|min:1',
                    'products.*' => 'exists:products,id',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->with('error', 'Invalid bulk action request.');
                }

                try {
                    DB::beginTransaction();

                    $products = Product::where('tenant_id', tenant()->id)
                                      ->whereIn('id', $request->products)
                                      ->get();

                    switch ($request->action) {
                        case 'activate':
                            $products->each(function ($product) {
                                $product->update(['is_active' => true]);
                            });
                            $message = 'Products activated successfully.';
                            break;

                        case 'deactivate':
                            $products->each(function ($product) {
                                $product->update(['is_active' => false]);
                            });
                            $message = 'Products deactivated successfully.';
                            break;

                        case 'delete':
                            foreach ($products as $product) {
                                // Check if product has transactions
                                $hasTransactions = $product->stockMovements()->count() > 0;

                                if (!$hasTransactions) {
                                    // Delete product image if exists
                                    if ($product->image_path) {
                                        Storage::disk('public')->delete($product->image_path);
                                    }
                                    $product->delete();
                                }
                            }
                            $message = 'Products deleted successfully (excluding those with transaction history).';
                            break;
                    }

                    DB::commit();

                    return redirect()->route('tenant.products.index', ['tenant' => tenant()->slug])
                        ->with('success', $message);

                } catch (\Exception $e) {
                    DB::rollBack();

                    return redirect()->back()
                        ->with('error', 'An error occurred while performing bulk action. Please try again.');
                }
            }

            /**
             * Export products to CSV
             */
            public function export(Request $request)
            {
                $query = Product::where('tenant_id', tenant()->id);

                // Apply same filters as index
                if ($request->has('type') && $request->type != '') {
                    $query->where('type', $request->type);
                }

                if ($request->has('category') && $request->category != '') {
                    $query->where('category', $request->category);
                }

                if ($request->has('status') && $request->status != '') {
                    switch ($request->status) {
                        case 'active':
                            $query->where('is_active', true);
                            break;
                        case 'inactive':
                            $query->where('is_active', false);
                            break;
                        case 'low_stock':
                            $query->where('maintain_stock', true)
                                  ->whereColumn('current_stock', '<=', 'reorder_level');
                            break;
                        case 'out_of_stock':
                            $query->where('maintain_stock', true)
                                  ->where('current_stock', '<=', 0);
                            break;
                    }
                }

                $products = $query->orderBy('name')->get();

                $filename = 'products_' . date('Y-m-d_H-i-s') . '.csv';

                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ];

                $callback = function() use ($products) {
                    $file = fopen('php://output', 'w');

                    // CSV headers
                    fputcsv($file, [
                        'Name', 'SKU', 'Type', 'Category', 'Brand', 'Model',
                        'Purchase Rate', 'Sales Rate', 'MRP', 'Primary Unit',
                        'Current Stock', 'Reorder Level', 'Tax Rate', 'Status'
                    ]);

                    // CSV data
                    foreach ($products as $product) {
                        fputcsv($file, [
                            $product->name,
                            $product->sku,
                            ucfirst($product->type),
                            $product->category,
                            $product->brand,
                            $product->model,
                            $product->purchase_rate,
                            $product->sales_rate,
                            $product->mrp,
                            $product->primary_unit,
                            $product->current_stock,
                            $product->reorder_level,
                            $product->tax_rate,
                            $product->is_active ? 'Active' : 'Inactive'
                        ]);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }
        }
