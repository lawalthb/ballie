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
      @include('tenant.accounting.vouchers.partials.voucher-entries')
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