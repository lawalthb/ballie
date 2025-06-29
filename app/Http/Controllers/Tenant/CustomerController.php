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

    public function index( Tenant $currentTenant)
    {

        $customers = Customer::where('tenant_id',$currentTenant->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalCustomers = Customer::where('tenant_id',$currentTenant->id)->count();
       // $totalRevenue = Customer::where('tenant_id',$currentTenant->id)->sum('total_spent') ?? 500;
        $totalRevenue =  500;
        $openInvoices = 0; // This would come from your Invoice model
        $avgPaymentDays = 0; // This would be calculated from your payment data

        return view('tenant.customers.index', compact(
            'customers',
            'totalCustomers',
            'totalRevenue',
            'openInvoices',
            'avgPaymentDays',
            'currentTenant'
        ));
    }

    /**

     * Show the form for creating a new customer.
     *
     * @return \Illuminate\Http\Response
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



        $customer = new Customer($request->all());
        $customer->tenant_id =$currentTenant->id;
        $customer->status = 'active';
        $customer->save();

        return redirect()->route('tenant.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**

     * Display the specified customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {

        $customer = Customer::where('tenant_id',$currentTenant->id)
            ->findOrFail($id);

        return view('tenant.customers.show', compact('customer'));
    }

    /**

     * Show the form for editing the specified customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {

        $customer = Customer::where('tenant_id',$currentTenant->id)
            ->findOrFail($id);

        return view('tenant.customers.edit', compact('customer'));
    }

    /**

     * Update the specified customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {


        $customer = Customer::where('tenant_id',$currentTenant->id)
            ->findOrFail($id);

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



        $customer->update($request->all());

        return redirect()->route('tenant.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**

     * Remove the specified customer from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $customer = Customer::where('tenant_id',$currentTenant->id)
            ->findOrFail($id);

        // Check if the customer has related records before deleting
        // This is just a placeholder - you would check for invoices, payments, etc.
        $hasRelatedRecords = false; // Replace with actual check

        if ($hasRelatedRecords) {
            return redirect()->route('tenant.customers.index')
                ->with('error', 'This customer cannot be deleted because they have related records.');
        }

        $customer->delete();

        return redirect()->route('tenant.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
