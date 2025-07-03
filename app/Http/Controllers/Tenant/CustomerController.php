<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Tenant $tenant)
    {
        $customers = Customer::where('tenant_id', $tenant->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalCustomers = Customer::where('tenant_id', $tenant->id)->count();
        $totalRevenue = Customer::where('tenant_id', $tenant->id)->sum('total_spent') ?? 0;
        $openInvoices = 0; // This would come from your Invoice model
        $avgPaymentDays = 0; // This would be calculated from your payment data

        return view('tenant.customers.index', compact(
            'customers',
            'totalCustomers',
            'totalRevenue',
            'openInvoices',
            'avgPaymentDays',
            'tenant'
        ));
    }

    /**
     * Show the form for creating a new customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Tenant $tenant)
    {
        return view('tenant.customers.create', compact('tenant'));
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Tenant $tenant)
    {
        $validator = Validator::make($request->all(), [
            'customer_type' => 'required|in:individual,business',
            'first_name' => 'required_if:customer_type,individual|string|max:255',
            'last_name' => 'required_if:customer_type,individual|string|max:255',
            'company_name' => 'required_if:customer_type,business|string|max:255',
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

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $customer = new Customer($request->all());
            $customer->tenant_id = $tenant->id;
            $customer->status = 'active';
            $customer->save();

            return redirect()->route('tenant.customers.index', ['tenant' => $tenant->slug])
                ->with('success', 'Customer created successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating customer: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'An error occurred while creating the customer. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified customer.
     *
     * @param  \App\Models\Tenant  $tenant
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Tenant $tenant, Customer $customer)
    {
        // Ensure the customer belongs to the tenant
        if ($customer->tenant_id !== $tenant->id) {
            abort(404);
        }

        return view('tenant.customers.show', compact('customer', 'tenant'));
    }

    /**
     * Show the form for editing the specified customer.
     *
     * @param  \App\Models\Tenant  $tenant
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Tenant $tenant, Customer $customer)
    {
        // Ensure the customer belongs to the tenant
        if ($customer->tenant_id !== $tenant->id) {
            abort(404);
        }

        return view('tenant.customers.edit', compact('customer', 'tenant'));
    }

    /**
     * Update the specified customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tenant  $tenant
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tenant $tenant, Customer $customer)
    {
        // Ensure the customer belongs to the tenant
        if ($customer->tenant_id !== $tenant->id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'customer_type' => 'required|in:individual,business',
            'first_name' => 'required_if:customer_type,individual|string|max:255',
            'last_name' => 'required_if:customer_type,individual|string|max:255',
            'company_name' => 'required_if:customer_type,business|string|max:255',
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
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $customer->update($request->all());

            return redirect()->route('tenant.customers.index', ['tenant' => $tenant->slug])
                ->with('success', 'Customer updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating customer: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'An error occurred while updating the customer. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified customer from storage.
     *
     * @param  \App\Models\Tenant  $tenant
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tenant $tenant, Customer $customer)
    {
        // Ensure the customer belongs to the tenant
        if ($customer->tenant_id !== $tenant->id) {
            abort(404);
        }

        // Check if the customer has related records before deleting
        $hasRelatedRecords = $customer->invoices()->exists();

        if ($hasRelatedRecords) {
            return redirect()->route('tenant.customers.index', ['tenant' => $tenant->slug])
                ->with('error', 'This customer cannot be deleted because they have related records.');
        }

        try {
            $customer->delete();

            return redirect()->route('tenant.customers.index', ['tenant' => $tenant->slug])
                ->with('success', 'Customer deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting customer: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'An error occurred while deleting the customer. Please try again.');
        }
    }

    /**
     * Toggle customer status.
     *
     * @param  \App\Models\Tenant  $tenant
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(Tenant $tenant, Customer $customer)
    {
        // Ensure the customer belongs to the tenant
        if ($customer->tenant_id !== $tenant->id) {
            abort(404);
        }

        $customer->status = $customer->status === 'active' ? 'inactive' : 'active';
        $customer->save();

        return redirect()->back()
            ->with('success', 'Customer status updated successfully.');
    }

    /**
     * Get customer data for AJAX requests.
     *
     * @param  \App\Models\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function getData(Tenant $tenant)
    {
        $customers = Customer::where('tenant_id', $tenant->id)
            ->select(['id', 'first_name', 'last_name', 'company_name', 'email', 'phone', 'status', 'created_at'])
            ->get();

        return response()->json($customers);
    }

    /**
     * Search customers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, Tenant $tenant)
    {
        $query = $request->get('q');

        $customers = Customer::where('tenant_id', $tenant->id)
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', '%' . $query . '%')
                  ->orWhere('last_name', 'like', '%' . $query . '%')
                  ->orWhere('company_name', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%');
            })
            ->get();

        return response()->json($customers);
    }

    /**
     * Export customers.
     *
     * @param  \App\Models\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function export(Tenant $tenant)
    {
        // Implementation for exporting customers
        // This would typically return a CSV or Excel file
        return response()->json(['message' => 'Export functionality not yet implemented']);
    }

    /**
     * Get customer statistics.
     *
     * @param  \App\Models\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function getStats(Tenant $tenant)
    {
        $stats = [
            'total_customers' => Customer::where('tenant_id', $tenant->id)->count(),
            'active_customers' => Customer::where('tenant_id', $tenant->id)->where('status', 'active')->count(),
            'inactive_customers' => Customer::where('tenant_id', $tenant->id)->where('status', 'inactive')->count(),
            'individual_customers' => Customer::where('tenant_id', $tenant->id)->where('customer_type', 'individual')->count(),
            'business_customers' => Customer::where('tenant_id', $tenant->id)->where('customer_type', 'business')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Bulk actions on customers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function bulkAction(Request $request, Tenant $tenant)
    {
        $action = $request->get('action');
        $customerIds = $request->get('customer_ids', []);

        if (empty($customerIds)) {
            return redirect()->back()->with('error', 'No customers selected.');
        }

        $customers = Customer::where('tenant_id', $tenant->id)
            ->whereIn('id', $customerIds)
            ->get();

        switch ($action) {
            case 'activate':
                $customers->each(function ($customer) {
                    $customer->update(['status' => 'active']);
                });
                $message = 'Selected customers have been activated.';
                break;

            case 'deactivate':
                $customers->each(function ($customer) {
                    $customer->update(['status' => 'inactive']);
                });
                $message = 'Selected customers have been deactivated.';
                break;

            case 'delete':
                $customers->each(function ($customer) {
                    if (!$customer->invoices()->exists()) {
                        $customer->delete();
                    }
                });
                $message = 'Selected customers have been deleted (except those with invoices).';
                break;

            default:
                return redirect()->back()->with('error', 'Invalid action selected.');
        }

        return redirect()->back()->with('success', $message);
    }
}
