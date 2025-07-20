@extends('layouts.tenant')

@section('title', 'Create Voucher - ' . $tenant->name)

@section('content')
<div class="space-y-6" x-data="voucherForm()">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                @if(isset($voucher))
                    Duplicate Voucher
                @else
                    Create New Voucher
                @endif
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                @if(isset($voucher))
                    Creating a copy of voucher {{ $voucher->voucher_number }}
                @else
                    Create a new accounting voucher entry
                @endif
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('tenant.accounting.vouchers.index', ['tenant' => $tenant->slug]) }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Vouchers
            </a>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('tenant.accounting.vouchers.store', ['tenant' => $tenant->slug]) }}" class="space-y-6">
        @csrf

        <!-- Voucher Header -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Voucher Information</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Voucher Type -->
                    <div>
                        <label for="voucher_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Voucher Type <span class="text-red-500">*</span>
                        </label>
                        <select name="voucher_type_id"
                                id="voucher_type_id"
                                x-model="voucherTypeId"
                                @change="updateVoucherType()"
                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 rounded-lg @error('voucher_type_id') border-red-300 @enderror"
                                required>
                            <option value="">Select Voucher Type</option>
                            @foreach($voucherTypes as $type)
                                <option value="{{ $type->id }}"
                                        {{ (old('voucher_type_id', $selectedType?->id ?? (isset($voucher) ? $voucher->voucher_type_id : '')) == $type->id) ? 'selected' : '' }}>
                                    {{ $type->name }} ({{ $type->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('voucher_type_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Voucher Date -->
                    <div>
                        <label for="voucher_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Voucher Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="voucher_date"
                               id="voucher_date"
                               value="{{ old('voucher_date', isset($voucher) ? $voucher->voucher_date->format('Y-m-d') : date('Y-m-d')) }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('voucher_date') border-red-300 @enderror"
                               required>
                        @error('voucher_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reference Number -->
                    <div>
                        <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Reference Number
                        </label>
                        <input type="text"
                               name="reference_number"
                               id="reference_number"
                               value="{{ old('reference_number', isset($voucher) ? $voucher->reference_number : '') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('reference_number') border-red-300 @enderror"
                               placeholder="Optional reference">
                        @error('reference_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Voucher Number Preview -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Voucher Number
                        </label>
                        <div class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-500">
                            <span x-text="voucherNumberPreview"></span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Auto-generated on save</p>
                    </div>
                </div>

                <!-- Narration -->
                <div class="mt-6">
                    <label for="narration" class="block text-sm font-medium text-gray-700 mb-2">
                        Narration
                    </label>
                    <textarea name="narration"
                              id="narration"
                              rows="3"
                              class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('narration') border-red-300 @enderror"
                              placeholder="Enter voucher description or narration">{{ old('narration', isset($voucher) ? $voucher->narration : '') }}</textarea>
                    @error('narration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Voucher Entries -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Voucher Entries</h3>
                    <button type="button"
                            @click="addEntry()"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-primary-700 bg-primary-100 hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Entry
                    </button>
                </div>
            </div>
            <div class="p-6">
                @error('entries')
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    </div>
                @enderror

                <!-- Entries Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ledger Account <span class="text-red-500">*</span>
                                </th>
                                <th class="text-left py-3 px-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Particulars
                                </th>
                                <th class="text-right py-3 px-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Debit Amount
                                </th>
                                <th class="text-right py-3 px-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Credit Amount
                                </th>
                                <th class="text-center py-3 px-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(entry, index) in entries" :key="index">
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 px-2">
                                        <select :name="`entries[${index}][ledger_account_id]`"
                                                x-model="entry.ledger_account_id"
                                                @change="updateEntryAccount(index)"
                                                class="block w-full pl-3 pr-10 py-2 text-sm border border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 rounded-md"
                                                required>
                                            <option value="">Select Account</option>
                                            @foreach($ledgerAccounts as $account)
                                                <option value="{{ $account->id }}">
                                                    {{ $account->name }} ({{ $account->accountGroup->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-3 px-2">
                                        <input type="text"
                                               :name="`entries[${index}][particulars]`"
                                               x-model="entry.particulars"
                                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                               placeholder="Entry description">
                                    </td>
                                    <td class="py-3 px-2">
                                        <input type="number"
                                               :name="`entries[${index}][debit_amount]`"
                                               x-model="entry.debit_amount"
                                               @input="updateTotals(); clearCredit(index)"
                                               step="0.01"
                                               min="0"
                                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-right"
                                               placeholder="0.00">
                                    </td>
                                    <td class="py-3 px-2">
                                        <input type="number"
                                               :name="`entries[${index}][credit_amount]`"
                                               x-model="entry.credit_amount"
                                               @input="updateTotals(); clearDebit(index)"
                                               step="0.01"
                                               min="0"
                                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-right"
                                               placeholder="0.00">
                                    </td>
                                    <td class="py-3 px-2 text-center">
                                        <button type="button"
                                                @click="removeEntry(index)"
                                                x-show="entries.length > 2"
                                                class="text-red-600 hover:text-red-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-300 bg-gray-50">
                                <td colspan="2" class="py-3 px-2 text-sm font-medium text-gray-900">
                                    Total
                                </td>
                                <td class="py-3 px-2 text-right text-sm font-medium text-gray-900">
                                    ₦<span x-text="formatNumber(totalDebits)"></span>
                                </td>
                                <td class="py-3 px-2 text-right text-sm font-medium text-gray-900">
                                    ₦<span x-text="formatNumber(totalCredits)"></span>
                                </td>
                                <td class="py-3 px-2"></td>
                            </tr>
                            <tr x-show="!isBalanced" class="bg-red-50">
                                <td colspan="5" class="py-2 px-2 text-center text-sm text-red-600">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Voucher is not balanced. Difference: ₦<span x-text="formatNumber(Math.abs(totalDebits - totalCredits))"></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Quick Entry Templates -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg" x-show="voucherTypeId">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Quick Entry Templates</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <template x-for="template in quickTemplates" :key="template.name">
                            <button type="button"
                                    @click="applyTemplate(template)"
                                    class="text-left p-3 border border-gray-200 rounded-lg hover:bg-white hover:shadow-sm transition-all">
                                <div class="text-sm font-medium text-gray-900" x-text="template.name"></div>
                                <div class="text-xs text-gray-500" x-text="template.description"></div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-500">
                    <span x-show="isBalanced" class="text-green-600">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Voucher is balanced
                    </span>
                    <span x-show="!isBalanced" class="text-red-600">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Voucher must be balanced to save
                    </span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('tenant.accounting.vouchers.index', ['tenant' => $tenant->slug]) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Cancel
                </a>
                <button type="submit"
                        :disabled="!isBalanced || entries.length < 2"
                        :class="{ 'opacity-50 cursor-not-allowed': !isBalanced || entries.length < 2 }"
                        class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Voucher
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function voucherForm() {
    return {
        voucherTypeId: '{{ old('voucher_type_id', $selectedType?->id ?? '') }}',
        voucherNumberPreview: 'Auto-generated',
        entries: [
            {
                ledger_account_id: '',
                particulars: '',
                debit_amount: '',
                credit_amount: ''
            },
            {
                ledger_account_id: '',
                particulars: '',
                debit_amount: '',
                credit_amount: ''
            }
        ],
        totalDebits: 0,
        totalCredits: 0,
        quickTemplates: [],
        voucherTypes: @json($voucherTypes->keyBy('id')),

        get isBalanced() {
            return Math.abs(this.totalDebits - this.totalCredits) < 0.01 && this.totalDebits > 0;
        },

        init() {
            // Initialize with old input or duplicate data if available
            @if(old('entries'))
                this.entries = @json(old('entries'));
            @elseif(isset($duplicateData) && isset($duplicateData['entries']))
                this.entries = @json($duplicateData['entries']);
            @endif

            this.updateTotals();
            this.updateVoucherType();
        },

        addEntry() {
            this.entries.push({
                ledger_account_id: '',
                particulars: '',
                debit_amount: '',
                credit_amount: ''
            });
        },

        removeEntry(index) {
            if (this.entries.length > 2) {
                this.entries.splice(index, 1);
                this.updateTotals();
            }
        },

        clearDebit(index) {
            if (this.entries[index].credit_amount) {
                this.entries[index].debit_amount = '';
            }
        },

        clearCredit(index) {
            if (this.entries[index].debit_amount) {
                this.entries[index].credit_amount = '';
            }
        },

        updateTotals() {
            this.totalDebits = this.entries.reduce((sum, entry) => {
                return sum + (parseFloat(entry.debit_amount) || 0);
            }, 0);

            this.totalCredits = this.entries.reduce((sum, entry) => {
                return sum + (parseFloat(entry.credit_amount) || 0);
            }, 0);
        },

        updateVoucherType() {
            if (this.voucherTypeId && this.voucherTypes[this.voucherTypeId]) {
                const voucherType = this.voucherTypes[this.voucherTypeId];
                this.voucherNumberPreview = voucherType.prefix + 'XXXX';
                this.loadQuickTemplates(voucherType.code);
            } else {
                this.voucherNumberPreview = 'Auto-generated';
                this.quickTemplates = [];
            }
        },

        loadQuickTemplates(typeCode) {
            const templates = {
                'JOURNAL': [
                    { name: 'Adjustment Entry', description: 'General adjustment between accounts' },
                    { name: 'Accrual Entry', description: 'Record accrued expenses or income' },
                    { name: 'Depreciation', description: 'Monthly depreciation entry' }
                ],
                'PAYMENT': [
                    { name: 'Supplier Payment', description: 'Payment to supplier/vendor' },
                    { name: 'Expense Payment', description: 'Direct expense payment' },
                    { name: 'Loan Payment', description: 'Loan installment payment' }
                ],
                'RECEIPT': [
                    { name: 'Customer Receipt', description: 'Receipt from customer' },
                    { name: 'Cash Sales', description: 'Direct cash sales receipt' },
                    { name: 'Other Income', description: 'Miscellaneous income receipt' }
                ],
                'SALES': [
                    { name: 'Credit Sales', description: 'Sales on credit terms' },
                    { name: 'Cash Sales', description: 'Direct cash sales' },
                    { name: 'Service Income', description: 'Service revenue recognition' }
                ],
                'PURCHASE': [
                    { name: 'Inventory Purchase', description: 'Purchase of goods for resale' },
                    { name: 'Asset Purchase', description: 'Purchase of fixed assets' },
                    { name: 'Expense Purchase', description: 'Purchase of consumables/expenses' }
                ]
            };

            this.quickTemplates = templates[typeCode] || [];
        },

        applyTemplate(template) {
            alert('Template: ' + template.name + '\nThis feature can be customized to auto-fill common entries.');
        },

        updateEntryAccount(index) {
            if (!this.entries[index].particulars && this.entries[index].ledger_account_id) {
                const accountSelect = document.querySelector(`select[name="entries[${index}][ledger_account_id]"]`);
                if (accountSelect && accountSelect.selectedIndex > 0) {
                    const selectedOption = accountSelect.options[accountSelect.selectedIndex];
                    if (selectedOption && selectedOption.text) {
                        this.entries[index].particulars = 'Being ' + selectedOption.text.split(' (')[0];
                    }
                }
            }
        },

        formatNumber(num) {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(num || 0);
        }
    }
}
</script>
@endpush
@endsection