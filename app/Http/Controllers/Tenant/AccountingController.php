<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\AccountGroup;
use App\Models\LedgerAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Tenant;

class AccountingController extends Controller
{

     /**
     * Display the accounting dashboard
     */
    public function index(Request $request, Tenant $tenant)
    {
        $currentTenant = $tenant;
        $user = auth()->user();

        // You would typically load accounting data here
        // For example:
        // $totalRevenue = Invoice::where('tenant_id', $tenant->id)->sum('total');
        // $totalExpenses = Expense::where('tenant_id', $tenant->id)->sum('amount');
        // $recentInvoices = Invoice::where('tenant_id', $tenant->id)->latest()->take(5)->get();

        return view('tenant.accounting.index', [
            'currentTenant' => $currentTenant,
            'user' => $user,
            'tenant' => $currentTenant,
        ]);
    }

    
    public function chartOfAccounts()
    {
        $accountGroups = AccountGroup::where('tenant_id', tenant()->id)
            ->with(['children', 'ledgerAccounts' => function($query) {
                $query->where('is_active', true);
            }])
            ->whereNull('parent_id')
            ->active()
            ->get();

        return view('tenant.accounting.chart-of-accounts', compact('accountGroups'));
    }

    public function createLedgerAccount()
    {
        $accountGroups = AccountGroup::where('tenant_id', tenant()->id)
            ->active()
            ->get();

        return view('tenant.accounting.create-ledger', compact('accountGroups'));
    }

    public function storeLedgerAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20',
            'account_group_id' => 'required|exists:account_groups,id',
            'opening_balance' => 'nullable|numeric',
            'balance_type' => 'required|in:dr,cr',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $ledgerAccount = new LedgerAccount($request->all());
        $ledgerAccount->tenant_id = tenant()->id;
        $ledgerAccount->opening_balance = $request->opening_balance ?? 0;
        $ledgerAccount->save();

        return redirect()->route('tenant.accounting.chart-of-accounts', ['tenant' => tenant()->slug])
            ->with('success', 'Ledger account created successfully.');
    }

    public function editLedgerAccount($id)
    {
        $ledgerAccount = LedgerAccount::where('tenant_id', tenant()->id)
            ->findOrFail($id);

        $accountGroups = AccountGroup::where('tenant_id', tenant()->id)
            ->active()
            ->get();

        return view('tenant.accounting.edit-ledger', compact('ledgerAccount', 'accountGroups'));
    }

    public function updateLedgerAccount(Request $request, $id)
    {
        $ledgerAccount = LedgerAccount::where('tenant_id', tenant()->id)
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20',
            'account_group_id' => 'required|exists:account_groups,id',
            'opening_balance' => 'nullable|numeric',
            'balance_type' => 'required|in:dr,cr',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $ledgerAccount->update($request->all());

        return redirect()->route('tenant.accounting.chart-of-accounts', ['tenant' => tenant()->slug])
            ->with('success', 'Ledger account updated successfully.');
    }

    public function invoicesIndex(Request $request, Tenant $tenant)
    {
        return view('tenant.accounting.invoices.index', [
            'tenant' => $tenant
        ]);
    }

    public function createInvoice(Request $request, Tenant $tenant)
    {
        return view('tenant.accounting.invoices.create', [
            'tenant' => $tenant
        ]);
    }

    public function storeInvoice(Request $request, Tenant $tenant)
    {
        // Handle invoice creation logic here
        return redirect()->route('tenant.accounting.invoices.index', ['tenant' => $tenant->slug])
            ->with('success', 'Invoice created successfully.');
    }
}
