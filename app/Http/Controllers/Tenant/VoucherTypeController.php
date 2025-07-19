<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\VoucherType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VoucherTypeController extends Controller
{
    public function index()
    {
        $voucherTypes = VoucherType::where('tenant_id', tenant()->id)
            ->orderBy('is_system_defined', 'desc')
            ->orderBy('name')
            ->paginate(15);

        $systemVoucherTypes = VoucherType::where('tenant_id', tenant()->id)
            ->where('is_system_defined', true)
            ->count();

        $customVoucherTypes = VoucherType::where('tenant_id', tenant()->id)
            ->where('is_system_defined', false)
            ->count();

        return view('tenant.accounting.voucher-types.index', compact(
            'voucherTypes',
            'systemVoucherTypes',
            'customVoucherTypes'
        ));
    }

    public function create()
    {
        $primaryVoucherTypes = $this->getPrimaryVoucherTypes();

        return view('tenant.accounting.voucher-types.create', compact('primaryVoucherTypes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('voucher_types')->where(function ($query) {
                    return $query->where('tenant_id', tenant()->id);
                })
            ],
            'code' => [
                'required',
                'string',
                'max:30',
                'alpha_dash',
                Rule::unique('voucher_types')->where(function ($query) {
                    return $query->where('tenant_id', tenant()->id);
                })
            ],
            'abbreviation' => 'required|string|max:5|alpha',
            'description' => 'nullable|string|max:1000',
            'numbering_method' => 'required|in:auto,manual',
            'prefix' => 'nullable|string|max:10',
            'starting_number' => 'required|integer|min:1',
            'has_reference' => 'boolean',
            'affects_inventory' => 'boolean',
            'affects_cashbank' => 'boolean',
            'is_active' => 'boolean',
            'primary_voucher_type' => 'nullable|string|in:journal,payment,receipt,contra,sales,purchase,credit_note,debit_note',
            'default_accounts' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $voucherType = VoucherType::create([
                'tenant_id' => tenant()->id,
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'abbreviation' => strtoupper($request->abbreviation),
                'description' => $request->description,
                'numbering_method' => $request->numbering_method,
                'prefix' => $request->prefix,
                'starting_number' => $request->starting_number,
                'current_number' => $request->starting_number - 1,
                'has_reference' => $request->boolean('has_reference'),
                'affects_inventory' => $request->boolean('affects_inventory'),
                'affects_cashbank' => $request->boolean('affects_cashbank'),
                'is_system_defined' => false,
                'is_active' => $request->boolean('is_active', true),
                'default_accounts' => $request->default_accounts ? json_encode($request->default_accounts) : null,
            ]);

            DB::commit();

            return redirect()->route('tenant.accounting.voucher-types.index', ['tenant' => tenant()->slug])
                ->with('success', 'Voucher type created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create voucher type: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show($id)
    {
        $voucherType = VoucherType::where('tenant_id', tenant()->id)->findOrFail($id);

        $voucherCount = $voucherType->vouchers()->count();
        $lastVoucherNumber = $voucherType->getNextVoucherNumber();

        return view('tenant.accounting.voucher-types.show', compact(
            'voucherType',
            'voucherCount',
            'lastVoucherNumber'
        ));
    }

    public function edit($id)
    {
        $voucherType = VoucherType::where('tenant_id', tenant()->id)->findOrFail($id);

        if ($voucherType->is_system_defined) {
            return redirect()->route('tenant.accounting.voucher-types.show', [
                'tenant' => tenant()->slug,
                'voucher_type' => $id
            ])->with('error', 'System-defined voucher types cannot be edited.');
        }

        $primaryVoucherTypes = $this->getPrimaryVoucherTypes();

        return view('tenant.accounting.voucher-types.edit', compact('voucherType', 'primaryVoucherTypes'));
    }

    public function update(Request $request, $id)
    {
        $voucherType = VoucherType::where('tenant_id', tenant()->id)->findOrFail($id);

        if ($voucherType->is_system_defined) {
            return redirect()->back()
                ->with('error', 'System-defined voucher types cannot be updated.');
        }

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('voucher_types')->where(function ($query) use ($id) {
                    return $query->where('tenant_id', tenant()->id)->where('id', '!=', $id);
                })
            ],
            'code' => [
                'required',
                'string',
                'max:30',
                'alpha_dash',
                Rule::unique('voucher_types')->where(function ($query) use ($id) {
                    return $query->where('tenant_id', tenant()->id)->where('id', '!=', $id);
                })
            ],
            'abbreviation' => 'required|string|max:5|alpha',
            'description' => 'nullable|string|max:1000',
            'numbering_method' => 'required|in:auto,manual',
            'prefix' => 'nullable|string|max:10',
            'starting_number' => 'required|integer|min:1',
            'has_reference' => 'boolean',
            'affects_inventory' => 'boolean',
            'affects_cashbank' => 'boolean',
            'is_active' => 'boolean',
            'default_accounts' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $voucherType->update([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'abbreviation' => strtoupper($request->abbreviation),
                'description' => $request->description,
                'numbering_method' => $request->numbering_method,
                'prefix' => $request->prefix,
                'starting_number' => $request->starting_number,
                'has_reference' => $request->boolean('has_reference'),
                'affects_inventory' => $request->boolean('affects_inventory'),
                'affects_cashbank' => $request->boolean('affects_cashbank'),
                'is_active' => $request->boolean('is_active', true),
                'default_accounts' => $request->default_accounts ? json_encode($request->default_accounts) : null,
            ]);

            DB::commit();

            return redirect()->route('tenant.accounting.voucher-types.show', [
                'tenant' => tenant()->slug,
                'voucher_type' => $voucherType->id
            ])->with('success', 'Voucher type updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update voucher type: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $voucherType = VoucherType::where('tenant_id', tenant()->id)->findOrFail($id);

        if ($voucherType->is_system_defined) {
            return redirect()->back()
                ->with('error', 'System-defined voucher types cannot be deleted.');
        }

        if ($voucherType->vouchers()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete voucher type that has associated vouchers.');
        }

        $voucherType->delete();

        return redirect()->route('tenant.accounting.voucher-types.index', ['tenant' => tenant()->slug])
            ->with('success', 'Voucher type deleted successfully.');
    }

    public function toggle($id)
    {
        $voucherType = VoucherType::where('tenant_id', tenant()->id)->findOrFail($id);

        $voucherType->update([
            'is_active' => !$voucherType->is_active
        ]);

        $status = $voucherType->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Voucher type {$status} successfully.");
    }

    public function resetNumbering(Request $request, $id)
    {
        $voucherType = VoucherType::where('tenant_id', tenant()->id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'starting_number' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $voucherType->resetNumbering($request->starting_number);

        return redirect()->back()
            ->with('success', 'Voucher numbering reset successfully.');
    }

    private function getPrimaryVoucherTypes()
    {
        return [
            'journal' => [
                'name' => 'Journal Voucher',
                'description' => 'For general journal entries and adjustments',
                'affects_inventory' => false,
                'affects_cashbank' => false,
                'has_reference' => false
            ],
            'payment' => [
                'name' => 'Payment Voucher',
                'description' => 'For recording payments made',
                'affects_inventory' => false,
                'affects_cashbank' => true,
                'has_reference' => true
            ],
            'receipt' => [
                'name' => 'Receipt Voucher',
                'description' => 'For recording receipts received',
                'affects_inventory' => false,
                'affects_cashbank' => true,
                'has_reference' => true
            ],
            'contra' => [
                'name' => 'Contra Voucher',
                'description' => 'For transfers between cash and bank accounts',
                'affects_inventory' => false,
                'affects_cashbank' => true,
                'has_reference' => true
            ],
            'sales' => [
                'name' => 'Sales Voucher',
                'description' => 'For recording sales transactions',
                'affects_inventory' => true,
                'affects_cashbank' => false,
                'has_reference' => true
            ],
            'purchase' => [
                'name' => 'Purchase Voucher',
                'description' => 'For recording purchase transactions',
                'affects_inventory' => true,
                'affects_cashbank' => false,
                'has_reference' => true
            ],
            'credit_note' => [
                'name' => 'Credit Note',
                'description' => 'For sales returns and allowances',
                'affects_inventory' => true,
                'affects_cashbank' => false,
                'has_reference' => true
            ],
            'debit_note' => [
                'name' => 'Debit Note',
                'description' => 'For purchase returns and claims',
                'affects_inventory' => true,
                'affects_cashbank' => false,
                'has_reference' => true
            ]
        ];
    }
}