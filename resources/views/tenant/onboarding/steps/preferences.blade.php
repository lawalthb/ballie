@extends('layouts.tenant-onboarding')

@section('title', 'Business Preferences - Ballie Setup')

@section('content')
<!-- Progress Steps -->
<div class="mb-8">
    <div class="flex items-center justify-center space-x-8">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-brand-green text-white rounded-full flex items-center justify-center font-semibold">✓</div>
            <span class="ml-3 text-sm font-medium text-brand-green">Company Info</span>
        </div>
        <div class="w-16 h-1 bg-brand-green rounded"></div>
        <div class="flex items-center">
            <div class="w-10 h-10 bg-brand-blue text-white rounded-full flex items-center justify-center font-semibold">2</div>
            <span class="ml-3 text-sm font-medium text-brand-blue">Preferences</span>
        </div>
        <div class="w-16 h-1 bg-brand-blue rounded"></div>
        <div class="flex items-center">
            <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">3</div>
            <span class="ml-3 text-sm font-medium text-gray-500">Team Setup</span>
        </div>
        <div class="w-16 h-1 bg-gray-200 rounded"></div>
        <div class="flex items-center">
            <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">4</div>
            <span class="ml-3 text-sm font-medium text-gray-500">Complete</span>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-gradient-to-br from-brand-teal to-brand-green rounded-lg flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Configure your preferences</h2>
        <p class="text-gray-600">Set up your business preferences to customize how Ballie works for you.</p>
    </div>

    <form method="POST" action="{{ route('tenant.onboarding.save-step', ['tenant' => $tenant->slug, 'step' => 'preferences']) }}">
        @csrf

        <div class="space-y-8">
            <!-- Currency & Localization -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-brand-gold mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Currency & Localization
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Primary Currency</label>
                        <select id="currency" name="currency"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent">
                            <option value="NGN" {{ old('currency', 'NGN') == 'NGN' ? 'selected' : '' }}>Nigerian Naira (₦)</option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>US Dollar ($)</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                            <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>British Pound (£)</option>
                        </select>
                    </div>

                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                        <select id="timezone" name="timezone"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent">
                            <option value="Africa/Lagos" {{ old('timezone', 'Africa/Lagos') == 'Africa/Lagos' ? 'selected' : '' }}>West Africa Time (WAT)</option>
                            <option value="UTC" {{ old('timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                        </select>
                    </div>

                    <div>
                        <label for="date_format" class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                        <select id="date_format" name="date_format"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent">
                            <option value="d/m/Y" {{ old('date_format', 'd/m/Y') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (31/12/2024)</option>
                            <option value="m/d/Y" {{ old('date_format') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY (12/31/2024)</option>
                            <option value="Y-m-d" {{ old('date_format') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (2024-12-31)</option>
                        </select>
                    </div>

                    <div>
                        <label for="number_format" class="block text-sm font-medium text-gray-700 mb-2">Number Format</label>
                        <select id="number_format" name="number_format"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent">
                            <option value="1,234.56" {{ old('number_format', '1,234.56') == '1,234.56' ? 'selected' : '' }}>1,234.56</option>
                            <option value="1.234,56" {{ old('number_format') == '1.234,56' ? 'selected' : '' }}>1.234,56</option>
                            <option value="1 234.56" {{ old('number_format') == '1 234.56' ? 'selected' : '' }}>1 234.56</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Business Settings -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-brand-blue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h2M7 7h10M7 11h10M7 15h10"></path>
                    </svg>
                    Business Settings
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="financial_year_start" class="block text-sm font-medium text-gray-700 mb-2">Financial Year Start</label>
                        <select id="financial_year_start" name="financial_year_start"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent">
                            <option value="01" {{ old('financial_year_start', '01') == '01' ? 'selected' : '' }}>January</option>
                            <option value="04" {{ old('financial_year_start') == '04' ? 'selected' : '' }}>April</option>
                            <option value="07" {{ old('financial_year_start') == '07' ? 'selected' : '' }}>July</option>
                            <option value="10" {{ old('financial_year_start') == '10' ? 'selected' : '' }}>October</option>
                        </select>
                    </div>

                    <div>
                        <label for="invoice_prefix" class="block text-sm font-medium text-gray-700 mb-2">Invoice Prefix</label>
                        <input type="text" id="invoice_prefix" name="invoice_prefix"
                               value="{{ old('invoice_prefix', 'INV') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent"
                               placeholder="INV">
                        <p class="text-xs text-gray-500 mt-1">Example: INV-001, INV-002</p>
                    </div>

                    <div>
                        <label for="quote_prefix" class="block text-sm font-medium text-gray-700 mb-2">Quote Prefix</label>
                        <input type="text" id="quote_prefix" name="quote_prefix"
                               value="{{ old('quote_prefix', 'QUO') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent"
                               placeholder="QUO">
                        <p class="text-xs text-gray-500 mt-1">Example: QUO-001, QUO-002</p>
                    </div>

                    <div>
                        <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-2">Default Payment Terms</label>
                        <select id="payment_terms" name="payment_terms"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent">
                            <option value="0" {{ old('payment_terms', '30') == '0' ? 'selected' : '' }}>Due on Receipt</option>
                            <option value="7" {{ old('payment_terms') == '7' ? 'selected' : '' }}>Net 7 days</option>
                            <option value="15" {{ old('payment_terms') == '15' ? 'selected' : '' }}>Net 15 days</option>
                            <option value="30" {{ old('payment_terms', '30') == '30' ? 'selected' : '' }}>Net 30 days</option>
                            <option value="60" {{ old('payment_terms') == '60' ? 'selected' : '' }}>Net 60 days</option>
                            <option value="90" {{ old('payment_terms') == '90' ? 'selected' : '' }}>Net 90 days</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tax Settings -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-brand-purple mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Tax Settings
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="default_tax_rate" class="block text-sm font-medium text-gray-700 mb-2">Default VAT Rate (%)</label>
                        <input type="number" id="default_tax_rate" name="default_tax_rate"
                               value="{{ old('default_tax_rate', '7.5') }}" step="0.01" min="0" max="100"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent"
                               placeholder="7.5">
                        <p class="text-xs text-gray-500 mt-1">Nigerian VAT is currently 7.5%</p>
                    </div>

                    <div>
                        <label for="tax_inclusive" class="block text-sm font-medium text-gray-700 mb-2">Tax Display</label>
                        <select id="tax_inclusive" name="tax_inclusive"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent">
                            <option value="0" {{ old('tax_inclusive', '0') == '0' ? 'selected' : '' }}>Tax Exclusive (Add tax to price)</option>
                            <option value="1" {{ old('tax_inclusive') == '1' ? 'selected' : '' }}>Tax Inclusive (Include tax in price)</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="enable_withholding_tax" value="1"
                               {{ old('enable_withholding_tax') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                        <span class="ml-2 text-sm text-gray-700">Enable Withholding Tax calculations</span>
                    </label>
                </div>
            </div>

            <!-- Features to Enable -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-brand-green mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Features to Enable
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-white cursor-pointer">
                        <input type="checkbox" name="features[]" value="inventory"
                               {{ in_array('inventory', old('features', ['inventory', 'invoicing', 'customers'])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">Inventory Management</div>
                            <div class="text-xs text-gray-500">Track stock levels and products</div>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-white cursor-pointer">
                        <input type="checkbox" name="features[]" value="invoicing"
                               {{ in_array('invoicing', old('features', ['inventory', 'invoicing', 'customers'])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">Invoicing & Billing</div>
                            <div class="text-xs text-gray-500">Create and send invoices</div>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-white cursor-pointer">
                        <input type="checkbox" name="features[]" value="customers"
                               {{ in_array('customers', old('features', ['inventory', 'invoicing', 'customers'])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">Customer Management</div>
                            <div class="text-xs text-gray-500">Manage customer relationships</div>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-white cursor-pointer">
                        <input type="checkbox" name="features[]" value="payroll"
                               {{ in_array('payroll', old('features', [])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">Payroll Management</div>
                            <div class="text-xs text-gray-500">Manage employee salaries</div>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-white cursor-pointer">
                        <input type="checkbox" name="features[]" value="pos"
                               {{ in_array('pos', old('features', [])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">Point of Sale (POS)</div>
                            <div class="text-xs text-gray-500">In-store sales system</div>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-white cursor-pointer">
                        <input type="checkbox" name="features[]" value="reports"
                               {{ in_array('reports', old('features', ['reports'])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">Advanced Reports</div>
                            <div class="text-xs text-gray-500">Business analytics and insights</div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('tenant.onboarding.step', ['tenant' => $tenant->slug, 'step' => 'company']) }}"
               class="text-gray-600 hover:text-brand-blue font-medium">
                ← Back to Company Info
            </a>

            <div class="flex space-x-4">
                <button type="button" onclick="skipStep()"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                    Skip This Step
                </button>
                <button type="submit"
                        class="px-8 py-3 bg-brand-blue text-white rounded-lg hover:bg-brand-dark-purple font-medium">
                    Continue →
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function skipStep() {
    if (confirm('Are you sure you want to skip this step? Default preferences will be applied.')) {
        window.location.href = "{{ route('tenant.onboarding.step', ['tenant' => $tenant->slug, 'step' => 'team']) }}";
    }
}
</script>
@endsection