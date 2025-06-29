@extends('layouts.tenant')

@section('title', 'Edit Customer')
@section('page-title', 'Edit Customer')
@section('page-description', 'Update customer information in your database.')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl p-8 shadow-lg">
        <form action="{{ route('tenant.customers.update', $customer->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Customer Type Selection -->
            <div class="bg-gray-50 p-4 rounded-xl mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Type</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition-all duration-200">
                        <input type="radio" name="customer_type" value="individual" class="h-5 w-5 text-blue-600" {{ $customer->customer_type == 'individual' ? 'checked' : '' }}>
                        <div class="ml-4">
                            <span class="block text-base font-medium text-gray-900">Individual</span>
                            <span class="block text-sm text-gray-500">Personal customer account</span>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition-all duration-200">
                        <input type="radio" name="customer_type" value="business" class="h-5 w-5 text-blue-600" {{ $customer->customer_type == 'business' ? 'checked' : '' }}>
                        <div class="ml-4">
                            <span class="block text-base font-medium text-gray-900">Business</span>
                            <span class="block text-sm text-gray-500">Company or organization</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="individual-field {{ $customer->customer_type == 'business' ? 'hidden' : '' }}">
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('first_name', $customer->first_name) }}" {{ $customer->customer_type == 'individual' ? 'required' : '' }}>
                    </div>

                    <div class="individual-field {{ $customer->customer_type == 'business' ? 'hidden' : '' }}">
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('last_name', $customer->last_name) }}" {{ $customer->customer_type == 'individual' ? 'required' : '' }}>
                    </div>

                    <div class="business-field {{ $customer->customer_type == 'individual' ? 'hidden' : '' }}">
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                        <input type="text" name="company_name" id="company_name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('company_name', $customer->company_name) }}" {{ $customer->customer_type == 'business' ? 'required' : '' }}>
                    </div>

                    <div class="business-field {{ $customer->customer_type == 'individual' ? 'hidden' : '' }}">
                        <label for="tax_id" class="block text-sm font-medium text-gray-700 mb-1">Tax ID / VAT Number</label>
                        <input type="text" name="tax_id" id="tax_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('tax_id', $customer->tax_id) }}">
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('email', $customer->email) }}" required>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" name="phone" id="phone" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('phone', $customer->phone) }}">
                    </div>

                    <div>
                        <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                        <input type="tel" name="mobile" id="mobile" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('mobile', $customer->mobile) }}">
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 md:col-span-2">
                        <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                        <input type="text" name="address_line1" id="address_line1" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('address_line1', $customer->address_line1) }}">
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label for="address_line2" class="block text-sm font-medium text-gray-700 mb-1">Address Line 2 (Optional)</label>
                        <input type="text" name="address_line2" id="address_line2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('address_line2', $customer->address_line2) }}">
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" name="city" id="city" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('city', $customer->city) }}">
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                        <input type="text" name="state" id="state" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('state', $customer->state) }}">
                    </div>

                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal/Zip Code</label>
                        <input type="text" name="postal_code" id="postal_code" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('postal_code', $customer->postal_code) }}">
                    </div>

                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <select name="country" id="country" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Country</option>
                            <option value="Nigeria" {{ old('country', $customer->country) == 'Nigeria' ? 'selected' : '' }}>Nigeria</option>
                            <option value="Ghana" {{ old('country', $customer->country) == 'Ghana' ? 'selected' : '' }}>Ghana</option>
                            <option value="Kenya" {{ old('country', $customer->country) == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                            <option value="South Africa" {{ old('country', $customer->country) == 'South Africa' ? 'selected' : '' }}>South Africa</option>
                            <option value="United States" {{ old('country', $customer->country) == 'United States' ? 'selected' : '' }}>United States</option>
                            <option value="United Kingdom" {{ old('country', $customer->country) == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                            <!-- Add more countries as needed -->
                        </select>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Preferred Currency</label>
                        <select name="currency" id="currency" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="NGN" {{ old('currency', $customer->currency) == 'NGN' ? 'selected' : '' }}>Nigerian Naira (₦)</option>
                            <option value="USD" {{ old('currency', $customer->currency) == 'USD' ? 'selected' : '' }}>US Dollar ($)</option>
                            <option value="EUR" {{ old('currency', $customer->currency) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                            <option value="GBP" {{ old('currency', $customer->currency) == 'GBP' ? 'selected' : '' }}>British Pound (£)</option>
                            <option value="GHS" {{ old('currency', $customer->currency) == 'GHS' ? 'selected' : '' }}>Ghanaian Cedi (₵)</option>
                            <option value="KES" {{ old('currency', $customer->currency) == 'KES' ? 'selected' : '' }}>Kenyan Shilling (KSh)</option>
                            <option value="ZAR" {{ old('currency', $customer->currency) == 'ZAR' ? 'selected' : '' }}>South African Rand (R)</option>
                        </select>
                    </div>

                    <div>
                        <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-1">Payment Terms</label>
                        <select name="payment_terms" id="payment_terms" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Payment Terms</option>
                            <option value="due_on_receipt" {{ old('payment_terms', $customer->payment_terms) == 'due_on_receipt' ? 'selected' : '' }}>Due on Receipt</option>
                            <option value="net_7" {{ old('payment_terms', $customer->payment_terms) == 'net_7' ? 'selected' : '' }}>Net 7 Days</option>
                            <option value="net_15" {{ old('payment_terms', $customer->payment_terms) == 'net_15' ? 'selected' : '' }}>Net 15 Days</option>
                            <option value="net_30" {{ old('payment_terms', $customer->payment_terms) == 'net_30' ? 'selected' : '' }}>Net 30 Days</option>
                            <option value="net_60" {{ old('payment_terms', $customer->payment_terms) == 'net_60' ? 'selected' : '' }}>Net 60 Days</option>
                            <option value="custom" {{ old('payment_terms', $customer->payment_terms) == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" id="notes" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $customer->notes) }}</textarea>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="active" {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('tenant.customers.index') }}" class="px-6 py-3 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl text-white hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    Update Customer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customerTypeRadios = document.querySelectorAll('input[name="customer_type"]');
        const individualFields = document.querySelectorAll('.individual-field');
        const businessFields = document.querySelectorAll('.business-field');

        // Function to toggle fields based on customer type
        function toggleCustomerTypeFields() {
            const customerType = document.querySelector('input[name="customer_type"]:checked').value;

            if (customerType === 'individual') {
                individualFields.forEach(field => field.classList.remove('hidden'));
                businessFields.forEach(field => field.classList.add('hidden'));

                // Make individual fields required
                document.getElementById('first_name').setAttribute('required', '');
                document.getElementById('last_name').setAttribute('required', '');

                // Make business fields not required
                document.getElementById('company_name').removeAttribute('required');

            } else {
                individualFields.forEach(field => field.classList.add('hidden'));
                businessFields.forEach(field => field.classList.remove('hidden'));

                // Make business fields required
                document.getElementById('company_name').setAttribute('required', '');

                // Make individual fields not required
                document.getElementById('first_name').removeAttribute('required');
                document.getElementById('last_name').removeAttribute('required');
            }
        }

        // Initial toggle based on default selection
        toggleCustomerTypeFields();

        // Add event listeners to radio buttons
        customerTypeRadios.forEach(radio => {
            radio.addEventListener('change', toggleCustomerTypeFields);
        });

        // Form validation enhancement
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            const customerType = document.querySelector('input[name="customer_type"]:checked').value;

            if (customerType === 'individual') {
                const firstName = document.getElementById('first_name').value.trim();
                const lastName = document.getElementById('last_name').value.trim();

                if (!firstName || !lastName) {
                    event.preventDefault();
                    alert('Please fill in both first and last name for individual customers.');
                }
            } else {
                const companyName = document.getElementById('company_name').value.trim();

                if (!companyName) {
                    event.preventDefault();
                    alert('Please enter a company name for business customers.');
                }
            }

            const email = document.getElementById('email').value.trim();
            if (!email) {
                event.preventDefault();
                alert('Please enter an email address.');
            }
        });

        // Enhanced select inputs with search functionality
        const enhanceSelect = (selectId) => {
            const select = document.getElementById(selectId);
            if (!select) return;

            // Add a subtle animation when focusing on select elements
            select.addEventListener('focus', function() {
                this.classList.add('ring-2', 'ring-blue-200');
            });

            select.addEventListener('blur', function() {
                this.classList.remove('ring-2', 'ring-blue-200');
            });
        };

        // Enhance select inputs
        enhanceSelect('country');
        enhanceSelect('currency');
        enhanceSelect('payment_terms');
        enhanceSelect('status');

        // Add animation to form sections
        const formSections = document.querySelectorAll('form > div');
        formSections.forEach((section, index) => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            section.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            section.style.transitionDelay = (index * 0.1) + 's';

            setTimeout(() => {
                section.style.opacity = '1';
                section.style.transform = 'translateY(0)';
            }, 100);
        });
    });
</script>

<style>
    /* Custom styles for the form */
    input:focus, select:focus, textarea:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        transition: all 0.2s ease;
    }

    /* Animated label effect */
    .form-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    /* Custom checkbox and radio styles */
    input[type="radio"] {
        position: relative;
        cursor: pointer;
    }

    input[type="radio"]:checked {
        border-color: #3b82f6;
        background-color: #3b82f6;
    }

    input[type="radio"]:checked:after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 0.5rem;
        height: 0.5rem;
        border-radius: 50%;
        background-color: white;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .grid-cols-2 {
            grid-template-columns: 1fr;
        }
    }

    /* Animated background for the form */
    .bg-white {
        position: relative;
        overflow: hidden;
    }

    .bg-white::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.03) 0%, transparent 70%);
        opacity: 0;
        animation: pulse 15s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes pulse {
        0%, 100% { opacity: 0; transform: scale(0.8); }
        50% { opacity: 1; transform: scale(1); }
    }
</style>
@endsection
