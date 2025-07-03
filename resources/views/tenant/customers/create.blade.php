@extends('layouts.tenant')

@section('title', 'Add Customer')
@section('page-title', 'Add New Customer')
@section('page-description', 'Create a new customer record in your database.')

@section('content')
<div class="space-y-6">
    <!-- Display any validation errors at the top of the form -->
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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

    <!-- Display success message if available -->
    @if (session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Display error message if available -->
    @if (session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('tenant.customers.store', ['tenant' => tenant()->slug]) }}" method="POST" id="customerForm">
        @csrf

        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-500">Complete all required fields</h3>
                <span class="text-sm font-medium text-blue-600" id="progress-indicator">0% Complete</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" id="progress-bar" style="width: 0%"></div>
            </div>
        </div>

        <!-- Customer Type Selection -->
        <div class="bg-white rounded-2xl p-6 shadow-lg transition-all duration-300 hover:shadow-xl">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 mr-2 text-sm font-semibold">1</span>
                Customer Type
                <span class="text-red-500 ml-1">*</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="relative">
                    <input type="radio" id="individual" name="customer_type" value="individual" class="hidden peer" {{ old('customer_type', 'individual') === 'individual' ? 'checked' : '' }}>
                    <label for="individual" class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 {{ $errors->has('customer_type') ? 'border-red-300' : 'border-gray-300' }}">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7 7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-medium text-gray-900">Individual</p>
                            <p class="text-sm text-gray-500">Personal customer account</p>
                        </div>
                    </label>
                </div>

                <div class="relative">
                    <input type="radio" id="business" name="customer_type" value="business" class="hidden peer" {{ old('customer_type') === 'business' ? 'checked' : '' }}>
                    <label for="business" class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 {{ $errors->has('customer_type') ? 'border-red-300' : 'border-gray-300' }}">
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
                @error('customer_type')
                    <div class="md:col-span-2 mt-1">
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    </div>
                @enderror
            </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white rounded-2xl p-6 shadow-lg transition-all duration-300 hover:shadow-xl">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 mr-2 text-sm font-semibold">2</span>
                Customer Information
                <span class="text-red-500 ml-1">*</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Individual Fields -->
                <div id="individual-fields" class="transition-all duration-300 {{ old('customer_type') === 'business' ? 'hidden' : '' }}">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" id="first_name"
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('first_name') ? 'border-red-300' : 'border-gray-300' }}"
                                value="{{ old('first_name') }}" placeholder="John">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="hidden text-sm text-red-600 mt-1 field-error" id="first_name-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="last_name" id="last_name"
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('last_name') ? 'border-red-300' : 'border-gray-300' }}"
                                value="{{ old('last_name') }}" placeholder="Doe">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="hidden text-sm text-red-600 mt-1 field-error" id="last_name-error"></div>
                        </div>
                    </div>
                </div>

                <!-- Business Fields -->
                <div id="business-fields" class="hidden transition-all duration-300 {{ old('customer_type') === 'business' ? 'block' : 'hidden' }}">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Company Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="company_name" id="company_name"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('company_name') ? 'border-red-300' : 'border-gray-300' }}"
                            value="{{ old('company_name') }}" placeholder="Acme Corporation">
                        @error('company_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="hidden text-sm text-red-600 mt-1 field-error" id="company_name-error"></div>
                    </div>

                    <div class="mt-4">
                        <label for="tax_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Tax ID / VAT Number
                        </label>
                        <input type="text" name="tax_id" id="tax_id"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('tax_id') ? 'border-red-300' : 'border-gray-300' }}"
                            value="{{ old('tax_id') }}" placeholder="123456789">
                        @error('tax_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Common Fields -->
                <div class="md:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email"
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('email') ? 'border-red-300' : 'border-gray-300' }}"
                                value="{{ old('email') }}" placeholder="customer@example.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="hidden text-sm text-red-600 mt-1 field-error" id="email-error"></div>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Phone Number
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">+</span>
                                </div>
                                <input type="text" name="phone" id="phone"
                                    class="pl-7 mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    value="{{ old('phone') }}" placeholder="2341234567890">
                            </div>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">
                                Mobile Number
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">+</span>
                                </div>
                                <input type="text" name="mobile" id="mobile"
                                    class="pl-7 mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    value="{{ old('mobile') }}" placeholder="2341234567890">
                            </div>
                            @error('mobile')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">
                                Currency
                            </label>
                            <select name="currency" id="currency"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="NGN" selected>Nigerian Naira (₦)</option>
                                <option value="USD">US Dollar ($)</option>
                                <option value="EUR">Euro (€)</option>
                                <option value="GBP">British Pound (£)</option>
                                <option value="GHS">Ghanaian Cedi (₵)</option>
                                <option value="KES">Kenyan Shilling (KSh)</option>
                                <option value="ZAR">South African Rand (R)</option>
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
        <div class="bg-white rounded-2xl p-6 shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 mr-2 text-sm font-semibold">3</span>
                    Address Information
                </h3>
                <button type="button" id="address-toggle" class="text-sm text-blue-600 hover:text-blue-800 focus:outline-none">
                    <span id="address-toggle-text">Hide Address</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline ml-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div id="address-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-1">
                        Address Line 1
                    </label>
                    <input type="text" name="address_line1" id="address_line1"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        value="{{ old('address_line1') }}" placeholder="123 Main Street">
                    @error('address_line1')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="address_line2" class="block text-sm font-medium text-gray-700 mb-1">
                        Address Line 2
                    </label>
                    <input type="text" name="address_line2" id="address_line2"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        value="{{ old('address_line2') }}" placeholder="Apartment, Suite, Unit, etc.">
                    @error('address_line2')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                        City
                    </label>
                    <input type="text" name="city" id="city"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        value="{{ old('city') }}" placeholder="Lagos">
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-1">
                        State/Province
                    </label>
                    <input type="text" name="state" id="state"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        value="{{ old('state') }}" placeholder="Lagos State">
                    @error('state')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">
                        Postal/ZIP Code
                    </label>
                    <input type="text" name="postal_code" id="postal_code"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        value="{{ old('postal_code') }}" placeholder="100001">
                    @error('postal_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">
                        Country
                    </label>
                    <select name="country" id="country"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="Nigeria" selected>Nigeria</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Kenya">Kenya</option>
                        <option value="South Africa">South Africa</option>
                        <option value="United States">United States</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="Canada">Canada</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('country')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="other-country-container" class="hidden">
                    <label for="other_country" class="block text-sm font-medium text-gray-700 mb-1">
                        Specify Country
                    </label>
                    <input type="text" name="other_country" id="other_country"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        placeholder="Enter country name">
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white rounded-2xl p-6 shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 mr-2 text-sm font-semibold">4</span>
                    Additional Information
                </h3>
                <button type="button" id="additional-toggle" class="text-sm text-blue-600 hover:text-blue-800 focus:outline-none">
                    <span id="additional-toggle-text">Hide Details</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline ml-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div id="additional-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-1">
                        Payment Terms
                    </label>
                    <select name="payment_terms" id="payment_terms"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="due_on_receipt">Due on Receipt</option>
                        <option value="net_7">Net 7 Days</option>
                        <option value="net_15">Net 15 Days</option>
                        <option value="net_30" selected>Net 30 Days</option>
                        <option value="net_60">Net 60 Days</option>
                        <option value="custom">Custom</option>
                    </select>
                    @error('payment_terms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="custom-terms-container" class="hidden">
                    <label for="custom_terms" class="block text-sm font-medium text-gray-700 mb-1">
                        Custom Payment Terms
                    </label>
                    <input type="text" name="custom_terms" id="custom_terms"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        placeholder="Specify custom payment terms">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                        Status
                    </label>
                    <select name="status" id="status"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        placeholder="Add any additional notes about this customer">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="send_welcome" name="send_welcome" type="checkbox"
                                class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="send_welcome" class="font-medium text-gray-700">Send welcome email</label>
                            <p class="text-gray-500">Send an automated welcome email to this customer with their account details.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('tenant.customers.index', ['tenant' => tenant()->slug]) }}"
                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancel
            </a>

            <button type="button" id="save-draft"
                class="inline-flex items-center justify-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Save as Draft
            </button>

            <button type="submit"
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Create Customer
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Customer Type Toggle
        const individualRadio = document.getElementById('individual');
        const businessRadio = document.getElementById('business');
        const individualFields = document.getElementById('individual-fields');
        const businessFields = document.getElementById('business-fields');

        // Function to toggle fields based on customer type
        function toggleCustomerTypeFields() {
            if (individualRadio.checked) {
                individualFields.classList.remove('hidden');
                businessFields.classList.add('hidden');

                // Make individual fields required
                document.getElementById('first_name').setAttribute('required', '');
                document.getElementById('last_name').setAttribute('required', '');
                document.getElementById('company_name').removeAttribute('required');
            } else {
                individualFields.classList.add('hidden');
                businessFields.classList.remove('hidden');

                // Make business fields required
                document.getElementById('company_name').setAttribute('required', '');
                document.getElementById('first_name').removeAttribute('required');
                document.getElementById('last_name').removeAttribute('required');
            }

            updateProgressBar();
        }

        // Add event listeners
        individualRadio.addEventListener('change', toggleCustomerTypeFields);
        businessRadio.addEventListener('change', toggleCustomerTypeFields);

        // Initialize form state based on validation errors
        // If there are validation errors, we want to show the correct fields
        const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
        if (hasErrors) {
            // If we have errors, make sure the correct fields are shown
            toggleCustomerTypeFields();

            // Scroll to the first error
            const firstError = document.querySelector('.text-red-600');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            // Highlight fields with errors
            document.querySelectorAll('.border-red-300').forEach(field => {
                field.addEventListener('focus', function() {
                    this.classList.remove('border-red-300');
                });
            });
        }

        // Address toggle
        const addressToggle = document.getElementById('address-toggle');
        const addressFields = document.getElementById('address-fields');
        const addressToggleText = document.getElementById('address-toggle-text');
        const addressToggleIcon = addressToggle.querySelector('svg');

        addressToggle.addEventListener('click', function() {
            if (addressFields.classList.contains('hidden')) {
                addressFields.classList.remove('hidden');
                addressToggleText.textContent = 'Hide Address';
                addressToggleIcon.classList.remove('rotate-180');
            } else {
                addressFields.classList.add('hidden');
                addressToggleText.textContent = 'Show Address';
                addressToggleIcon.classList.add('rotate-180');
            }
        });

        // If there are address field errors, make sure the address section is visible
        const hasAddressErrors = {{ $errors->hasAny(['address_line1', 'address_line2', 'city', 'state', 'postal_code', 'country']) ? 'true' : 'false' }};
        if (hasAddressErrors && addressFields.classList.contains('hidden')) {
            addressFields.classList.remove('hidden');
            addressToggleText.textContent = 'Hide Address';
            addressToggleIcon.classList.remove('rotate-180');
        }

        // Additional info toggle
        const additionalToggle = document.getElementById('additional-toggle');
        const additionalFields = document.getElementById('additional-fields');
        const additionalToggleText = document.getElementById('additional-toggle-text');
        const additionalToggleIcon = additionalToggle.querySelector('svg');

        additionalToggle.addEventListener('click', function() {
            if (additionalFields.classList.contains('hidden')) {
                additionalFields.classList.remove('hidden');
                additionalToggleText.textContent = 'Hide Details';
                additionalToggleIcon.classList.remove('rotate-180');
            } else {
                additionalFields.classList.add('hidden');
                additionalToggleText.textContent = 'Show Details';
                additionalToggleIcon.classList.add('rotate-180');
            }
        });

        // If there are additional field errors, make sure the additional section is visible
        const hasAdditionalErrors = {{ $errors->hasAny(['payment_terms', 'notes', 'status']) ? 'true' : 'false' }};
        if (hasAdditionalErrors && additionalFields.classList.contains('hidden')) {
            additionalFields.classList.remove('hidden');
            additionalToggleText.textContent = 'Hide Details';
            additionalToggleIcon.classList.remove('rotate-180');
        }

        // Country selection
        const countrySelect = document.getElementById('country');
        const otherCountryContainer = document.getElementById('other-country-container');
        const otherCountryInput = document.getElementById('other_country');

        countrySelect.addEventListener('change', function() {
            if (this.value === 'Other') {
                otherCountryContainer.classList.remove('hidden');
                otherCountryInput.setAttribute('required', '');
            } else {
                otherCountryContainer.classList.add('hidden');
                otherCountryInput.removeAttribute('required');
            }
        });

        // Initialize country selection
        if (countrySelect.value === 'Other') {
            otherCountryContainer.classList.remove('hidden');
            otherCountryInput.setAttribute('required', '');
        }

        // Payment terms
        const paymentTermsSelect = document.getElementById('payment_terms');
        const customTermsContainer = document.getElementById('custom-terms-container');
        const customTermsInput = document.getElementById('custom_terms');

        paymentTermsSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customTermsContainer.classList.remove('hidden');
                customTermsInput.setAttribute('required', '');
            } else {
                customTermsContainer.classList.add('hidden');
                customTermsInput.removeAttribute('required');
            }
        });

        // Initialize payment terms selection
        if (paymentTermsSelect.value === 'custom') {
            customTermsContainer.classList.remove('hidden');
            customTermsInput.setAttribute('required', '');
        }

        // Form validation
        const form = document.getElementById('customerForm');
        const requiredFields = form.querySelectorAll('[required]');
        const progressBar = document.getElementById('progress-bar');
        const progressIndicator = document.getElementById('progress-indicator');

        // Function to update progress bar
        function updateProgressBar() {
            const totalRequired = form.querySelectorAll('[required]').length;
            let filledCount = 0;

            form.querySelectorAll('[required]').forEach(field => {
                if (field.value.trim() !== '') {
                    filledCount++;
                }
            });

            const progressPercentage = totalRequired > 0 ? Math.round((filledCount / totalRequired) * 100) : 0;
            progressBar.style.width = `${progressPercentage}%`;
            progressIndicator.textContent = `${progressPercentage}% Complete`;

            // Change color based on progress
            if (progressPercentage < 30) {
                progressBar.classList.remove('bg-green-600', 'bg-yellow-500');
                progressBar.classList.add('bg-blue-600');
            } else if (progressPercentage < 70) {
                progressBar.classList.remove('bg-blue-600', 'bg-green-600');
                progressBar.classList.add('bg-yellow-500');
            } else {
                progressBar.classList.remove('bg-blue-600', 'bg-yellow-500');
                progressBar.classList.add('bg-green-600');
            }
        }

        // Add input event listeners to all required fields
        requiredFields.forEach(field => {
            field.addEventListener('input', updateProgressBar);
        });

        // Initial progress calculation
        updateProgressBar();

        // Client-side validation
        form.addEventListener('submit', function(e) {
            let hasErrors = false;
            const errorMessages = {
                first_name: 'First name is required',
                last_name: 'Last name is required',
                company_name: 'Company name is required',
                email: 'A valid email address is required'
            };

            // Clear previous errors
            document.querySelectorAll('.field-error').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            // Check individual fields if individual customer type is selected
            if (individualRadio.checked) {
                const firstName = document.getElementById('first_name');
                const lastName = document.getElementById('last_name');

                if (firstName.value.trim() === '') {
                    document.getElementById('first_name-error').textContent = errorMessages.first_name;
                    document.getElementById('first_name-error').classList.remove('hidden');
                    firstName.classList.add('border-red-300');
                    hasErrors = true;
                }

                if (lastName.value.trim() === '') {
                    document.getElementById('last_name-error').textContent = errorMessages.last_name;
                    document.getElementById('last_name-error').classList.remove('hidden');
                    lastName.classList.add('border-red-300');
                    hasErrors = true;
                }
            }
            // Check business fields if business customer type is selected
            else {
                const companyName = document.getElementById('company_name');

                if (companyName.value.trim() === '') {
                    document.getElementById('company_name-error').textContent = errorMessages.company_name;
                    document.getElementById('company_name-error').classList.remove('hidden');
                    companyName.classList.add('border-red-300');
                    hasErrors = true;
                }
            }

            // Check email (required for all customer types)
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email.value.trim() === '' || !emailRegex.test(email.value.trim())) {
                document.getElementById('email-error').textContent = errorMessages.email;
                document.getElementById('email-error').classList.remove('hidden');
                email.classList.add('border-red-300');
                hasErrors = true;
            }

            if (hasErrors) {
                e.preventDefault();

                // Scroll to the first error
                const firstError = document.querySelector('.field-error:not(.hidden)');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

                // Show error notification
                showNotification('Please fix the errors in the form', 'error');
            }
        });

        // Show notification function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-500 transform translate-x-full ${
                type === 'error' ? 'bg-red-500 text-white' :
                type === 'success' ? 'bg-green-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        ${type === 'error' ?
                            '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' :
                            type === 'success' ?
                            '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' :
                            '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                        }
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button" class="inline-flex rounded-md p-1.5 text-white focus:outline-none">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 100);

            // Add click event to close button
            notification.querySelector('button').addEventListener('click', () => {
                notification.classList.remove('translate-x-0');
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    notification.remove();
                }, 500);
            });

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    notification.classList.remove('translate-x-0');
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }
            }, 5000);
        }

        // Initialize form state
        toggleCustomerTypeFields();
    });
</script>

<style>
    /* Animated form transitions */
    .form-group {
        transition: all 0.3s ease;
    }

    /* Input focus effects */
    input:focus, select:focus, textarea:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        transition: all 0.2s ease;
    }

    /* Error state animations */
    .border-red-300 {
        animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }

    @keyframes shake {
        10%, 90% { transform: translate3d(-1px, 0, 0); }
        20%, 80% { transform: translate3d(2px, 0, 0); }
        30%, 50%, 70% { transform: translate3d(-3px, 0, 0); }
        40%, 60% { transform: translate3d(3px, 0, 0); }
    }

    /* Progress bar animation */
    #progress-bar {
        transition: width 0.5s ease, background-color 0.5s ease;
    }

    /* Card hover effects */
    .bg-white {
        transition: all 0.3s ease;
    }

    .bg-white:hover {
        transform: translateY(-2px);
    }

    /* Custom radio button styles */
    .peer:checked + label {
        border-color: rgb(59, 130, 246);
        background-color: rgba(59, 130, 246, 0.1);
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .grid-cols-2 {
            grid-template-columns: 1fr;
        }
    }

    /* Print styles */
    @media print {
        .bg-white {
            box-shadow: none !important;
            border: 1px solid #e5e7eb !important;
        }

        button, .shadow-lg, .shadow-xl {
            display: none !important;
        }
    }
</style>
@endsection
