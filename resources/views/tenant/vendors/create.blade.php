@extends('layouts.tenant')

@section('title', 'Add Vendor')
@section('page-title', 'Add New Vendor')
@section('page-description', 'Create a new vendor with automatic ledger account integration.')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <form action="{{ route('tenant.vendors.store', ['tenant' => tenant()->slug]) }}" method="POST" id="vendorForm">
            @csrf

            <!-- Vendor Type Selection -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-4">Vendor Type</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative">
                        <input type="radio" name="vendor_type" value="individual" class="sr-only peer" {{ old('vendor_type', 'business') == 'individual' ? 'checked' : '' }}>
                        <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">Individual</h3>
                                    <p class="text-sm text-gray-500">Personal vendor/freelancer</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="relative">
                        <input type="radio" name="vendor_type" value="business" class="sr-only peer" {{ old('vendor_type', 'business') == 'business' ? 'checked' : '' }}>
                        <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m4 0v-3.5a1.5 1.5 0 013 0V21m-4-3h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">Business</h3>
                                    <p class="text-sm text-gray-500">Company or organization</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                @error('vendor_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Individual Fields (Hidden by default) -->
            <div id="individualFields" class="space-y-6 mb-8" style="display: {{ old('vendor_type', 'business') == 'individual' ? 'block' : 'none' }}">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('first_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Business Fields (Visible by default) -->
            <div id="businessFields" class="space-y-6 mb-8" style="display: {{ old('vendor_type', 'business') == 'business' ? 'block' : 'none' }}">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('company_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tax_id" class="block text-sm font-medium text-gray-700 mb-2">Tax ID</label>
                        <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('tax_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="registration_number" class="block text-sm font-medium text-gray-700 mb-2">Registration Number</label>
                    <input type="text" name="registration_number" id="registration_number" value="{{ old('registration_number') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    @error('registration_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Contact Information -->
            <div class="space-y-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Contact Information</h3>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                        <input type="tel" name="mobile" id="mobile" value="{{ old('mobile') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('mobile')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                        <input type="url" name="website" id="website" value="{{ old('website') }}" placeholder="https://" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('website')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="space-y-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Address Information</h3>

                <div>
                    <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-2">Address Line 1</label>
                    <input type="text" name="address_line1" id="address_line1" value="{{ old('address_line1') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    @error('address_line1')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address_line2" class="block text-sm font-medium text-gray-700 mb-2">Address Line 2</label>
                    <input type="text" name="address_line2" id="address_line2" value="{{ old('address_line2') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    @error('address_line2')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <input type="text" name="city" id="city" value="{{ old('city') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('city')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                        <input type="text" name="state" id="state" value="{{ old('state') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('state')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('postal_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                    <input type="text" name="country" id="country" value="{{ old('country', 'Nigeria') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    @error('country')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Banking Information -->
            <div class="space-y-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Banking Information</h3>

                <div>
                    <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                    <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    @error('bank_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="bank_account_number" class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                        <input type="text" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('bank_account_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bank_account_name" class="block text-sm font-medium text-gray-700 mb-2">Account Name</label>
                        <input type="text" name="bank_account_name" id="bank_account_name" value="{{ old('bank_account_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('bank_account_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Financial Settings -->
            <div class="space-y-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Financial Settings</h3>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                        <select name="currency" id="currency" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            <option value="NGN" {{ old('currency', 'NGN') == 'NGN' ? 'selected' : '' }}>Nigerian Naira (NGN)</option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                            <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>British Pound (GBP)</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                        </select>
                        @error('currency')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-2">Payment Terms</label>
                        <select name="payment_terms" id="payment_terms" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            <option value="">Select payment terms</option>
                            <option value="Net 15" {{ old('payment_terms') == 'Net 15' ? 'selected' : '' }}>Net 15</option>
                            <option value="Net 30" {{ old('payment_terms') == 'Net 30' ? 'selected' : '' }}>Net 30</option>
                            <option value="Net 45" {{ old('payment_terms') == 'Net 45' ? 'selected' : '' }}>Net 45</option>
                            <option value="Net 60" {{ old('payment_terms') == 'Net 60' ? 'selected' : '' }}>Net 60</option>
                            <option value="COD" {{ old('payment_terms') == 'COD' ? 'selected' : '' }}>Cash on Delivery</option>
                            <option value="Prepaid" {{ old('payment_terms') == 'Prepaid' ? 'selected' : '' }}>Prepaid</option>
                        </select>
                        @error('payment_terms')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="space-y-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Additional Information</h3>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200" placeholder="Any additional notes about this vendor...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Ledger Integration Notice -->
            <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 mb-8">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-purple-900">Automatic Ledger Integration</h4>
                        <p class="text-sm text-purple-700 mt-1">
                            A ledger account will be automatically created for this vendor in your Accounts Payable.
                            This enables proper tracking of all purchases and payments.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('tenant.vendors.index', ['tenant' => tenant()->slug]) }}" class="px-6 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 shadow-md">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Vendor
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const vendorTypeRadios = document.querySelectorAll('input[name="vendor_type"]');
    const individualFields = document.getElementById('individualFields');
    const businessFields = document.getElementById('businessFields');

    // Toggle fields based on vendor type
    vendorTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'individual') {
                individualFields.style.display = 'block';
                businessFields.style.display = 'none';

                // Clear business fields
                document.getElementById('company_name').value = '';
                document.getElementById('tax_id').value = '';
                document.getElementById('registration_number').value = '';
            } else {
                individualFields.style.display = 'none';
                businessFields.style.display = 'block';

                // Clear individual fields
                document.getElementById('first_name').value = '';
                document.getElementById('last_name').value = '';
            }
        });
    });

    // Form validation
    const form = document.getElementById('vendorForm');
    form.addEventListener('submit', function(e) {
        const vendorType = document.querySelector('input[name="vendor_type"]:checked').value;
        let isValid = true;
        let errorMessage = '';

        if (vendorType === 'individual') {
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();

            if (!firstName || !lastName) {
                isValid = false;
                errorMessage = 'First name and last name are required for individual vendors.';
            }
        } else {
            const companyName = document.getElementById('company_name').value.trim();

            if (!companyName) {
                isValid = false;
                errorMessage = 'Company name is required for business vendors.';
            }
        }

        const email = document.getElementById('email').value.trim();
        if (!email) {
            isValid = false;
            errorMessage = 'Email address is required.';
        }

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
        }
    });

    // Auto-populate bank account name
    const companyNameField = document.getElementById('company_name');
    const firstNameField = document.getElementById('first_name');
    const lastNameField = document.getElementById('last_name');
    const bankAccountNameField = document.getElementById('bank_account_name');

    function updateBankAccountName() {
        const vendorType = document.querySelector('input[name="vendor_type"]:checked').value;

        if (vendorType === 'individual') {
            const firstName = firstNameField.value.trim();
            const lastName = lastNameField.value.trim();
            if (firstName && lastName) {
                bankAccountNameField.value = firstName + ' ' + lastName;
            }
        } else {
            const companyName = companyNameField.value.trim();
            if (companyName) {
                bankAccountNameField.value = companyName;
            }
        }
    }

    companyNameField.addEventListener('blur', updateBankAccountName);
    firstNameField.addEventListener('blur', updateBankAccountName);
    lastNameField.addEventListener('blur', updateBankAccountName);
});
</script>
@endsection