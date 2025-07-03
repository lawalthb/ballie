<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index(Request $request)
    {

        $query = Customer::where('tenant_id', tenant()->id);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('company_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by customer type
        if ($request->has('customer_type') && $request->customer_type) {
            $query->where('customer_type', $request->customer_type);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        switch ($sortBy) {
            case 'name':
                $query->orderByRaw('COALESCE(company_name, CONCAT(first_name, " ", last_name)) ' . $sortOrder);
                break;
            case 'total_spent':
                $query->orderBy('total_spent', $sortOrder);
                break;
            case 'last_invoice':
                $query->orderBy('last_invoice_date', $sortOrder);
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
                break;
        }

        $customers = $query->paginate(15)->withQueryString();

        // Calculate statistics
        $totalCustomers = Customer::where('tenant_id', tenant()->id)->count();
        $totalRevenue = Customer::where('tenant_id', tenant()->id)->sum('total_spent');
        $activeCustomers = Customer::where('tenant_id', tenant()->id)->where('status', 1)->count();
        $newCustomersThisMonth = Customer::where('tenant_id', tenant()->id)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        // Top customers
        $topCustomers = Customer::where('tenant_id', tenant()->id)
            ->where('status', 'active')
            ->orderBy('total_spent', 'desc')
            ->limit(5)
            ->get();

        return view('tenant.customers.index', compact(
            'customers',
            'totalCustomers',
            'totalRevenue',
            'activeCustomers',
            'newCustomersThisMonth',
            'topCustomers'
        ));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('tenant.customers.create');
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'customer_type' => 'required|in:individual,business',
            'first_name' => 'required_if:customer_type,individual|string|max:255',
            'last_name' => 'required_if:customer_type,individual|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'currency' => 'nullable|string|max:3',
            'payment_terms' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        // If validation fails, redirect back with errors and input
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Create the new customer
            $customer = new Customer($request->all());
            $customer->tenant_id = tenant()->id;
            $customer->status = 'active';
            $customer->save();

            // Redirect with success message
            return redirect()->route('tenant.customers.index', ['tenant' => tenant()->slug])
                ->with('success', 'Customer created successfully.');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error creating customer: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()
                ->with('error', 'An error occurred while creating the customer. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified customer.
     */
    public function show($id)
    {
        $customer = Customer::where('tenant_id', tenant()->id)->findOrFail($id);

        // Get customer's invoice summary (you would implement this based on your invoice model)
        $invoiceStats = [
            'total_invoices' => 0,
            'total_amount' => 0,
            'paid_amount' => 0,
            'outstanding_amount' => $customer->total_spent ?? 0,
        ];

        // Get recent transactions (you would implement this based on your transaction models)
        $recentTransactions = collect();

        return view('tenant.customers.show', compact('customer', 'invoiceStats', 'recentTransactions'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit($id)
    {
        $customer = Customer::where('tenant_id', tenant()->id)->findOrFail($id);
        return view('tenant.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::where('tenant_id', tenant()->id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'customer_type' => 'required|in:individual,business',
            'first_name' => 'required_if:customer_type,individual|string|max:255',
            'last_name' => 'required_if:customer_type,individual|string|max:255',
            'company_name' => 'required_if:customer_type,business|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->id . ',id,tenant_id,' . tenant()->id,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'currency' => 'nullable|string|max:3',
            'payment_terms' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please correct the errors below.');
        }

        try {
            DB::beginTransaction();

            $customer->update($request->all());

            DB::commit();

            return redirect()->route('tenant.customers.index', ['tenant' => tenant()->slug])
                ->with('success', 'Customer updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update customer. Please try again.');
        }
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy($id)
    {
        $customer = Customer::where('tenant_id', tenant()->id)->findOrFail($id);

        try {
            // Check if the customer has related records before deleting
            // This is where you would check for invoices, payments, etc.
            $hasInvoices = false; // Replace with actual check: $customer->invoices()->exists();
            $hasPayments = false; // Replace with actual check: $customer->payments()->exists();

            if ($hasInvoices || $hasPayments) {
                return redirect()->route('tenant.customers.index', ['tenant' => tenant()->slug])
                    ->with('warning', 'This customer cannot be deleted because they have related invoices or payments. You can deactivate them instead.');
            }

            DB::beginTransaction();

            $customer->delete();

            DB::commit();

            return redirect()->route('tenant.customers.index', ['tenant' => tenant()->slug])
                ->with('success', 'Customer deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('tenant.customers.index', ['tenant' => tenant()->slug])
                ->with('error', 'Failed to delete customer. Please try again.');
        }
    }

    /**
     * Get customer data for AJAX requests
     */
    public function getData(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $query = Customer::where('tenant_id', tenant()->id);

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('company_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $customers = $query->select([
            'id',
            'customer_type',
            'first_name',
            'last_name',
            'company_name',
            'email',
            'phone',
            'status'
        ])->get();

        return response()->json($customers);
    }

    /**
     * Export customers to CSV
     */
    public function export(Request $request)
    {
        $query = Customer::where('tenant_id', tenant()->id);

        // Apply same filters as index
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('company_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->has('customer_type') && $request->customer_type) {
            $query->where('customer_type', $request->customer_type);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $customers = $query->get();

        $filename = 'customers_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Type',
                'Name',
                'Email',
                'Phone',
                'Address',
                'City',
                'State',
                'Country',
                'Total Spent',
                'Status',
                'Created At'
            ]);

            // Add customer data
            foreach ($customers as $customer) {
                $name = $customer->customer_type === 'individual'
                    ? $customer->first_name . ' ' . $customer->last_name
                    : $customer->company_name;

                $address = trim($customer->address_line1 . ' ' . $customer->address_line2);

                fputcsv($file, [
                    $customer->id,
                    ucfirst($customer->customer_type),
                    $name,
                    $customer->email,
                    $customer->phone,
                    $address,
                    $customer->city,
                    $customer->state,
                    $customer->country,
                    $customer->total_spent,
                    ucfirst($customer->status),
                    $customer->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Toggle customer status
     */
    public function toggleStatus($id)
    {
        try {
            $customer = Customer::where('tenant_id', tenant()->id)->findOrFail($id);

            $customer->status = $customer->status === 'active' ? 'inactive' : 'active';
            $customer->save();

            $statusText = $customer->status === 'active' ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "Customer {$statusText} successfully.",
                'status' => $customer->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer status.'
            ], 500);
        }
    }

    /**
     * Get customer statistics for dashboard
     */
    public function getStats()
    {
        $tenantId = tenant()->id;

        $stats = [
            'total' => Customer::where('tenant_id', $tenantId)->count(),
            'active' => Customer::where('tenant_id', $tenantId)->where('status', 'active')->count(),
            'inactive' => Customer::where('tenant_id', $tenantId)->where('status', 'inactive')->count(),
            'new_this_month' => Customer::where('tenant_id', $tenantId)
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count(),
            'total_revenue' => Customer::where('tenant_id', $tenantId)->sum('total_spent'),
            'average_spent' => Customer::where('tenant_id', $tenantId)
                ->where('total_spent', '>', 0)
                ->avg('total_spent'),
        ];

        // Get growth percentage
        $lastMonthCustomers = Customer::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth()
            ])
            ->count();

        if ($lastMonthCustomers > 0) {
            $stats['growth_percentage'] = round((($stats['new_this_month'] - $lastMonthCustomers) / $lastMonthCustomers) * 100, 1);
        } else {
            $stats['growth_percentage'] = $stats['new_this_month'] > 0 ? 100 : 0;
        }

        return response()->json($stats);
    }

    /**
     * Search customers for autocomplete
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $customers = Customer::where('tenant_id', tenant()->id)
            ->where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('company_name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->select([
                'id',
                'customer_type',
                'first_name',
                'last_name',
                'company_name',
                'email'
            ])
            ->limit(10)
            ->get()
            ->map(function($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->customer_type === 'individual'
                        ? $customer->first_name . ' ' . $customer->last_name
                        : $customer->company_name,
                    'email' => $customer->email,
                    'type' => $customer->customer_type
                ];
            });

        return response()->json($customers);
    }

    /**
     * Bulk operations on customers
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'customer_ids' => 'required|array|min:1',
            'customer_ids.*' => 'exists:customers,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request data.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $customers = Customer::where('tenant_id', tenant()->id)
                ->whereIn('id', $request->customer_ids);

            switch ($request->action) {
                case 'activate':
                    $customers->update(['status' => 'active']);
                    $message = 'Customers activated successfully.';
                    break;

                case 'deactivate':
                    $customers->update(['status' => 'inactive']);
                    $message = 'Customers deactivated successfully.';
                    break;

                case 'delete':
                    // Check for related records before deleting
                    $customerIds = $customers->pluck('id');

                    // You would implement these checks based on your models
                    $hasRelatedRecords = false; // Check for invoices, payments, etc.

                    if ($hasRelatedRecords) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Some customers cannot be deleted because they have related records.'
                        ], 400);
                    }

                    $customers->delete();
                    $message = 'Customers deleted successfully.';
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action. Please try again.'
            ], 500);
        }
    }
}
