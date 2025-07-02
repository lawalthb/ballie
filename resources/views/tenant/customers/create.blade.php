@extends('layouts.tenant')


@section('title', 'Add Customer')
@section('page-title', 'Add New Customer')

@section('page-description', 'Create a new customer record in your database.')

@section('content')











<div class="space-y-6">
    <form action="{{ route('tenant.customers.store', ['tenant' => tenant()->slug]) }}" method="POST">
        @csrf

        <!-- Customer Type Selection -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Type</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="relative">
                    <input type="radio" id="individual" name="customer_type" value="individual" class="hidden peer" checked>
                    <label for="individual" class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>









                        <div>
                            <p class="text-lg font-medium text-gray-900">Individual</p>
                            <p class="text-sm text-gray-500">Personal customer account</p>
                        </div>

                    </label>
                </div>












                <div class="relative">
                    <input type="radio" id="business" name="customer_type" value="business" class="hidden peer">
                    <label for="business" class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>







                        <div>
                            <p class="text-lg font-medium text-gray-900">Business</p>
                            <p class="text-sm text-gray-500">Company or organization</p>
                        </div>
                    </label>
                </div>
            </div>









        </div>

        <!-- Customer Information -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Individual Fields -->
                <div id="individual-fields">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('first_name') }}">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('last_name') }}">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>





















                </div>

                <!-- Business Fields -->
                <div id="business-fields" class="hidden">
                    <div>


                        <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
                        <input type="text" name="company_name" id="company_name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('company_name') }}">
                        @error('company_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>




                </div>

                <!-- Common Fields -->
                <div class="md:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('phone') }}">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                            <input type="text" name="mobile" id="mobile" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('mobile') }}">
                            @error('mobile')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                            <select name="currency" id="currency" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="NGN" selected>Nigerian Naira (₦)</option>
                                <option value="USD">US Dollar ($)</option>
                                <option value="EUR">Euro (€)</option>
                                <option value="GBP">British Pound (£)</option>
                            </select>
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <!-- Address Information -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>






            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="address_line1" class="block text-sm font-medium text-gray-700">Address Line 1</label>
                    <input type="text" name="address_line1" id="address_line1" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('address_line1') }}">
                    @error('address_line1')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>





                <div class="md:col-span-2">
                    <label for="address_line2" class="block text-sm font-medium text-gray-700">Address Line 2</label>
                    <input type="text" name="address_line2" id="address_line2" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('address_line2') }}">
                    @error('address_line2')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>





                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                    <input type="text" name="city" id="city" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('city') }}">
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>





                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700">State/Province</label>
                    <input type="text" name="state" id="state" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('state') }}">
                    @error('state')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>





                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal/ZIP Code</label>
                    <input type="text" name="postal_code" id="postal_code" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('postal_code') }}">
                    @error('postal_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>














                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                    <select name="country" id="country" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="Nigeria" selected>Nigeria</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Kenya">Kenya</option>
                        <option value="South Africa">South Africa</option>
                        <option value="United States">United States</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="Canada">Canada</option>
                    </select>
                    @error('country')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>




        <!-- Additional Information -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>














            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="payment_terms" class="block text-sm font-medium text-gray-700">Payment Terms</label>
                    <select name="payment_terms" id="payment_terms" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="due_on_receipt">Due on Receipt</option>
                        <option value="net_7">Net 7 Days</option>
                        <option value="net_15">Net 15 Days</option>
                        <option value="net_30" selected>Net 30 Days</option>
                        <option value="net_60">Net 60 Days</option>
                    </select>
                    @error('payment_terms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>












                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>





                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>












        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('tenant.customers.index', ['tenant' => tenant()->slug]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cancel
            </a>

            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Create Customer
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const individualRadio = document.getElementById('individual');
        const businessRadio = document.getElementById('business');
        const individualFields = document.getElementById('individual-fields');
        const businessFields = document.getElementById('business-fields');

        // Function to toggle fields based on customer type
        function toggleCustomerTypeFields() {
            if (individualRadio.checked) {
                individualFields.classList.remove('hidden');
                businessFields.classList.add('hidden');
            } else {
                individualFields.classList.add('hidden');
                businessFields.classList.remove('hidden');
            }
        }

        // Add event listeners
        individualRadio.addEventListener('change', toggleCustomerTypeFields);
        businessRadio.addEventListener('change', toggleCustomerTypeFields);

        // Initialize fields on page load
        toggleCustomerTypeFields();
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
