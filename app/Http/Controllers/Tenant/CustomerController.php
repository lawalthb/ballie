<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(Request $request, Tenant $tenant)
    {
        $customers = Customer::paginate(15);
        return view('tenant.customers.index', compact('customers', 'tenant'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create(Tenant $tenant)
    {
        return view('tenant.customers.create', compact('tenant'));
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $customer = Customer::create($validated);

        return redirect()
            ->route('tenant.customers.show', ['tenant' => $tenant->slug, 'customer' => $customer->id])
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer
     */
    public function show(Tenant $tenant, Customer $customer)
    {
        return view('tenant.customers.show', compact('tenant', 'customer'));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(Tenant $tenant, Customer $customer)
    {
        return view('tenant.customers.edit', compact('tenant', 'customer'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, Tenant $tenant, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()
            ->route('tenant.customers.show', ['tenant' => $tenant->slug, 'customer' => $customer->id])
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer
     */
    public function destroy(Tenant $tenant, Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('tenant.customers.index', ['tenant' => $tenant->slug])
            ->with('success', 'Customer deleted successfully.');
    }
}