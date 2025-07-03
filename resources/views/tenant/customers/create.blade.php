@extends('layouts.tenant')

@section('title', 'Add Customer')
@section('page-title', 'Add New Customer')
@section('page-description', 'Create a new customer record in your database.')

@section('content')
<div class="space-y-6">
    <!-- Header with Back Button -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('tenant.customers.index', ['tenant' => tenant()->slug]) }}"
               class="inline-flex items-center p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Customers
            </a>
        </div>
        <div class="flex items-center space-x-3">
            <span class="text-sm text-gray-500">Creating new customer</span>
            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
        </div>
    </div>

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
                                value="{{ old('email') }}" placeholder="john@example.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="hidden text-sm text-red-600 mt-1 field-error" id="email-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Phone Number
                            </label>
                            <input type="tel" name="phone" id="phone"
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('phone') ? 'border-red-300' : 'border-gray-300' }}"
                                value="{{ old('phone') }}" placeholder="+234 800 123 4567">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">
                                Mobile Number
                            </label>
                            <input type="tel" name="mobile" id="mobile"
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('mobile') ? 'border-red-300' : 'border-gray-300' }}"
                                value="{{ old('mobile') }}" placeholder="+234 700 123 4567">
                            @error('mobile')
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
                    <span class="text-gray-500 text-sm ml-2">(Optional)</span>
                </h3>
                <button type="button" id="toggle-address" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <span id="address-toggle-text">Show Address</span>
                </button>
            </div>

            <div id="address-section" class="hidden transition-all duration-300">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-1">
                            Address Line 1
                        </label>
                        <input type="text" name="address_line1" id="address_line1"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('address_line1') ? 'border-red-300' : 'border-gray-300' }}"
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
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('address_line2') ? 'border-red-300' : 'border-gray-300' }}"
                            value="{{ old('address_line2') }}" placeholder="Suite 456">
                        @error('address_line2')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                            City
                        </label>
                        <input type="text" name="city" id="city"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('city') ? 'border-red-300' : 'border-gray-300' }}"
                            value="{{ old('city') }}" placeholder="Lagos">
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">
                            State
                        </label>
                        <input type="text" name="state" id="state"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('state') ? 'border-red-300' : 'border-gray-300' }}"
                            value="{{ old('state') }}" placeholder="Lagos State">
                        @error('state')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">
                            Postal Code
                        </label>
                        <input type="text" name="postal_code" id="postal_code"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('postal_code') ? 'border-red-300' : 'border-gray-300' }}"
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
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('country') ? 'border-red-300' : 'border-gray-300' }}">
                            <option value="">Select Country</option>
                            <option value="Nigeria" {{ old('country') == 'Nigeria' ? 'selected' : '' }}>Nigeria</option>
                            <option value="Ghana" {{ old('country') == 'Ghana' ? 'selected' : '' }}>Ghana</option>
                            <option value="Kenya" {{ old('country') == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                            <option value="South Africa" {{ old('country') == 'South Africa' ? 'selected' : '' }}>South Africa</option>
                            <option value="United Kingdom" {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                            <option value="United States" {{ old('country') == 'United States' ? 'selected' : '' }}>United States</option>
                            <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                            <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                            <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('country')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Settings -->
        <div class="bg-white rounded-2xl p-6 shadow-lg transition-all duration-300 hover:shadow-xl">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 mr-2 text-sm font-semibold">4</span>
                Financial Settings
                <span class="text-gray-500 text-sm ml-2">(Optional)</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">
                        Currency
                    </label>
                    <select name="currency" id="currency"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('currency') ? 'border-red-300' : 'border-gray-300' }}">
                        <option value="">Select Currency</option>
                        <option value="NGN" {{ old('currency', 'NGN') == 'NGN' ? 'selected' : '' }}>Nigerian Naira (NGN)</option>
                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                        <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                        <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>British Pound (GBP)</option>
                        <option value="CAD" {{ old('currency') == 'CAD' ? 'selected' : '' }}>Canadian Dollar (CAD)</option>
                        <option value="AUD" {{ old('currency') == 'AUD' ? 'selected' : '' }}>Australian Dollar (AUD)</option>
                        <option value="ZAR" {{ old('currency') == 'ZAR' ? 'selected' : '' }}>South African Rand (ZAR)</option>
                        <option value="GHS" {{ old('currency') == 'GHS' ? 'selected' : '' }}>Ghanaian Cedi (GHS)</option>
                        <option value="KES" {{ old('currency') == 'KES' ? 'selected' : '' }}>Kenyan Shilling (KES)</option>
                    </select>
                    @error('currency')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-1">
                        Payment Terms
                    </label>
                    <select name="payment_terms" id="payment_terms"
                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('payment_terms') ? 'border-red-300' : 'border-gray-300' }}">
                        <option value="">Select Payment Terms</option>
                        <option value="Net 15" {{ old('payment_terms') == 'Net 15' ? 'selected' : '' }}>Net 15 Days</option>
                        <option value="Net 30" {{ old('payment_terms', 'Net 30') == 'Net 30' ? 'selected' : '' }}>Net 30 Days</option>
                        <option value="Net 45" {{ old('payment_terms') == 'Net 45' ? 'selected' : '' }}>Net 45 Days</option>
                        <option value="Net 60" {{ old('payment_terms') == 'Net 60' ? 'selected' : '' }}>Net 60 Days</option>
                        <option value="Due on Receipt" {{ old('payment_terms') == 'Due on Receipt' ? 'selected' : '' }}>Due on Receipt</option>
                        <option value="Cash on Delivery" {{ old('payment_terms') == 'Cash on Delivery' ? 'selected' : '' }}>Cash on Delivery</option>
                    </select>
                    @error('payment_terms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Additional Notes -->
        <div class="bg-white rounded-2xl p-6 shadow-lg transition-all duration-300 hover:shadow-xl">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 mr-2 text-sm font-semibold">5</span>
                Additional Information
                <span class="text-gray-500 text-sm ml-2">(Optional)</span>
            </h3>

            <div class="form-group">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Notes
                </label>
                <textarea name="notes" id="notes" rows="4"
                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm rounded-md {{ $errors->has('notes') ? 'border-red-300' : 'border-gray-300' }}"
                placeholder="Any additional notes about this customer...">{{ old('notes') }}</textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Form Actions -->
    <div class="bg-white rounded-2xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button type="button" id="saveAndNew"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Save & Add New
                </button>
                <button type="button" id="previewBtn"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Preview
                </button>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('tenant.customers.index', ['tenant' => tenant()->slug]) }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" id="submitBtn"
                        class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2 hidden" id="submitSpinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Create Customer
                </button>
            </div>
        </div>
    </div>
</form>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
<div class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Customer Preview</h3>
                <button id="closePreview" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div id="previewContent" class="p-6">
            <!-- Preview content will be populated here -->
        </div>
    </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
// Form elements
const customerForm = document.getElementById('customerForm');
const individualRadio = document.getElementById('individual');
const businessRadio = document.getElementById('business');
const individualFields = document.getElementById('individual-fields');
const businessFields = document.getElementById('business-fields');
const toggleAddressBtn = document.getElementById('toggle-address');
const addressSection = document.getElementById('address-section');
const addressToggleText = document.getElementById('address-toggle-text');
const previewBtn = document.getElementById('previewBtn');
const previewModal = document.getElementById('previewModal');
const closePreviewBtn = document.getElementById('closePreview');
const submitBtn = document.getElementById('submitBtn');
const submitSpinner = document.getElementById('submitSpinner');
const saveAndNewBtn = document.getElementById('saveAndNew');
const progressBar = document.getElementById('progress-bar');
const progressIndicator = document.getElementById('progress-indicator');

// Customer type toggle functionality
function toggleCustomerType() {
    if (individualRadio.checked) {
        individualFields.classList.remove('hidden');
        businessFields.classList.add('hidden');
        // Clear business fields
        document.getElementById('company_name').value = '';
        document.getElementById('tax_id').value = '';
    } else if (businessRadio.checked) {
        businessFields.classList.remove('hidden');
        individualFields.classList.add('hidden');
        // Clear individual fields
        document.getElementById('first_name').value = '';
        document.getElementById('last_name').value = '';
    }
    updateProgress();
}

// Add event listeners for customer type radio buttons
individualRadio.addEventListener('change', toggleCustomerType);
businessRadio.addEventListener('change', toggleCustomerType);

// Initialize customer type on page load
toggleCustomerType();

// Address section toggle
toggleAddressBtn.addEventListener('click', function() {
    addressSection.classList.toggle('hidden');
    if (addressSection.classList.contains('hidden')) {
        addressToggleText.textContent = 'Show Address';
    } else {
        addressToggleText.textContent = 'Hide Address';
    }
    updateProgress();
});

// Progress tracking
function updateProgress() {
    const formFields = customerForm.querySelectorAll('input:not([type="radio"]):not([type="hidden"]), select, textarea');
    const radioGroups = ['customer_type'];

    let totalFields = formFields.length + radioGroups.length;
    let filledFields = 0;

    // Check regular fields
    formFields.forEach(field => {
        if (field.value.trim() !== '') {
            filledFields++;
        }
    });

    // Check radio groups
    radioGroups.forEach(groupName => {
        const checkedRadio = document.querySelector(`input[name="${groupName}"]:checked`);
        if (checkedRadio) {
            filledFields++;
        }
    });

    const progressPercentage = Math.round((filledFields / totalFields) * 100);
    progressBar.style.width = progressPercentage + '%';
    progressIndicator.textContent = progressPercentage + '% Complete';

    // Change color based on progress
    if (progressPercentage < 30) {
        progressBar.className = 'bg-red-500 h-2 rounded-full transition-all duration-300';
    } else if (progressPercentage < 70) {
        progressBar.className = 'bg-yellow-500 h-2 rounded-full transition-all duration-300';
    } else {
        progressBar.className = 'bg-green-500 h-2 rounded-full transition-all duration-300';
    }
}

// Add event listeners to all form fields for progress tracking
const formFields = customerForm.querySelectorAll('input, select, textarea');
formFields.forEach(field => {
    field.addEventListener('input', updateProgress);
    field.addEventListener('change', updateProgress);
});

// Initial progress calculation
updateProgress();

// Form validation
function validateForm() {
    const customerType = document.querySelector('input[name="customer_type"]:checked');
    const email = document.getElementById('email').value.trim();

    if (!customerType) {
        showError('Please select a customer type.');
        return false;
    }

    if (!email) {
        showError('Please enter an email address.');
        return false;
    }

    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        showError('Please enter a valid email address.');
        return false;
    }

    if (customerType.value === 'individual') {
        const firstName = document.getElementById('first_name').value.trim();
        const lastName = document.getElementById('last_name').value.trim();

        if (!firstName || !lastName) {
            showError('Please enter both first and last name for individual customers.');
            return false;
        }
    } else if (customerType.value === 'business') {
        const companyName = document.getElementById('company_name').value.trim();

        if (!companyName) {
            showError('Please enter a company name for business customers.');
            return false;
        }
    }

    return true;
}

// Show error message
function showError(message) {
    // Create or update error message
    let errorDiv = document.getElementById('form-error');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.id = 'form-error';
        errorDiv.className = 'bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md';
        customerForm.parentNode.insertBefore(errorDiv, customerForm);
    }

    errorDiv.innerHTML = `
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">${message}</p>
            </div>
        </div>
    `;

    // Scroll to error
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// Preview functionality
previewBtn.addEventListener('click', function() {
    if (!validateForm()) return;

    const customerType = document.querySelector('input[name="customer_type"]:checked').value;
    const previewContent = document.getElementById('previewContent');

    let customerName = '';
    if (customerType === 'individual') {
        customerName = document.getElementById('first_name').value + ' ' + document.getElementById('last_name').value;
    } else {
        customerName = document.getElementById('company_name').value;
    }

    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const mobile = document.getElementById('mobile').value;
    const address = [
        document.getElementById('address_line1').value,
        document.getElementById('address_line2').value,
        document.getElementById('city').value,
        document.getElementById('state').value,
        document.getElementById('postal_code').value,
        document.getElementById('country').value
    ].filter(item => item.trim() !== '').join(', ');

    const currency = document.getElementById('currency').value;
    const paymentTerms = document.getElementById('payment_terms').value;
    const notes = document.getElementById('notes').value;

    previewContent.innerHTML = `
        <div class="space-y-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                    ${customerName.charAt(0)}
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">${customerName}</h3>
                    <p class="text-gray-600">${customerType === 'individual' ? 'Individual Customer' : 'Business Customer'}</p>
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                        New Customer
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Contact Information</h4>
                    <div class="space-y-2 text-sm">
                        <p><strong>Email:</strong> ${email}</p>
                        ${phone ? `<p><strong>Phone:</strong> ${phone}</p>` : ''}
                        ${mobile ? `<p><strong>Mobile:</strong> ${mobile}</p>` : ''}
                        </div>
                    </div>

                    ${address ? `
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Address</h4>
                        <p class="text-sm text-gray-700">${address}</p>
                    </div>
                    ` : ''}
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    ${currency || paymentTerms ? `
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Financial Settings</h4>
                        <div class="space-y-2 text-sm">
                            ${currency ? `<p><strong>Currency:</strong> ${currency}</p>` : ''}
                            ${paymentTerms ? `<p><strong>Payment Terms:</strong> ${paymentTerms}</p>` : ''}
                        </div>
                    </div>
                    ` : ''}

                    ${notes ? `
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Notes</h4>
                        <p class="text-sm text-gray-700">${notes}</p>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;

        previewModal.classList.remove('hidden');
    });

    // Close preview modal
    closePreviewBtn.addEventListener('click', function() {
        previewModal.classList.add('hidden');
    });

    // Close modal when clicking outside
    previewModal.addEventListener('click', function(e) {
        if (e.target === previewModal) {
            previewModal.classList.add('hidden');
        }
    });

    // Save and New functionality
    saveAndNewBtn.addEventListener('click', function() {
        if (!validateForm()) return;

        // Add hidden input to indicate save and new
        const saveAndNewInput = document.createElement('input');
        saveAndNewInput.type = 'hidden';
        saveAndNewInput.name = 'save_and_new';
        saveAndNewInput.value = '1';
        customerForm.appendChild(saveAndNewInput);

        // Submit the form
        customerForm.submit();
    });

    // Form submission with loading state
    customerForm.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            return;
        }

        // Remove any existing error messages
        const errorDiv = document.getElementById('form-error');
        if (errorDiv) {
            errorDiv.remove();
        }

        // Show loading state
        submitBtn.disabled = true;
        submitSpinner.classList.remove('hidden');
        submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Creating...';

        // Disable save and new button
        saveAndNewBtn.disabled = true;
        saveAndNewBtn.classList.add('opacity-50', 'cursor-not-allowed');
    });

    // Real-time validation
    function addFieldValidation(fieldId, validationFn, errorMessage) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        field.addEventListener('blur', function() {
            const isValid = validationFn(this.value);
            const errorDiv = document.getElementById(`${fieldId}-error`);

            if (!isValid) {
                this.classList.add('border-red-300');
                this.classList.remove('border-gray-300');
                if (errorDiv) {
                    errorDiv.textContent = errorMessage;
                    errorDiv.classList.remove('hidden');
                }
            } else {
                this.classList.remove('border-red-300');
                this.classList.add('border-gray-300');
                if (errorDiv) {
                    errorDiv.classList.add('hidden');
                }
            }
        });
    }

    // Add validation for email field
    addFieldValidation('email', function(value) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return value === '' || emailPattern.test(value);
    }, 'Please enter a valid email address.');

    // Add validation for phone fields
    addFieldValidation('phone', function(value) {
        const phonePattern = /^[\+]?[1-9][\d]{0,15}$/;
        return value === '' || phonePattern.test(value.replace(/\s/g, ''));
    }, 'Please enter a valid phone number.');

    addFieldValidation('mobile', function(value) {
        const phonePattern = /^[\+]?[1-9][\d]{0,15}$/;
        return value === '' || phonePattern.test(value.replace(/\s/g, ''));
    }, 'Please enter a valid mobile number.');

    // Auto-save functionality (optional)
    let autoSaveTimeout;
    function autoSave() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Save form data to localStorage
            const formData = new FormData(customerForm);
            const formObject = {};
            formData.forEach((value, key) => {
                formObject[key] = value;
            });
            localStorage.setItem('customer-form-draft', JSON.stringify(formObject));
            console.log('Form data auto-saved');
        }, 2000);
    }

    // Load saved form data
    function loadSavedData() {
        const savedData = localStorage.getItem('customer-form-draft');
        if (savedData) {
            try {
                const formObject = JSON.parse(savedData);
                Object.keys(formObject).forEach(key => {
                    const field = document.querySelector(`[name="${key}"]`);
                    if (field) {
                        if (field.type === 'radio') {
                            if (field.value === formObject[key]) {
                                field.checked = true;
                            }
                        } else {
                            field.value = formObject[key];
                        }
                    }
                });

                // Trigger customer type toggle if needed
                toggleCustomerType();
                updateProgress();

                // Show notification
                showNotification('Draft data loaded', 'info');
            } catch (e) {
                console.error('Error loading saved data:', e);
            }
        }
    }

    // Clear saved data on successful submission
    customerForm.addEventListener('submit', function() {
        localStorage.removeItem('customer-form-draft');
    });

    // Show notification
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success' ?
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                    }
                </svg>
                ${message}
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Auto-save on input changes
    formFields.forEach(field => {
        field.addEventListener('input', autoSave);
        field.addEventListener('change', autoSave);
    });

    // Load saved data on page load
    loadSavedData();

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+S or Cmd+S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            if (validateForm()) {
                customerForm.submit();
            }
        }

        // Ctrl+N or Cmd+N for save and new
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            if (validateForm()) {
                saveAndNewBtn.click();
            }
        }

        // Escape to close modal
        if (e.key === 'Escape' && !previewModal.classList.contains('hidden')) {
            previewModal.classList.add('hidden');
        }
    });

    // Add smooth animations
    const sections = document.querySelectorAll('.bg-white.rounded-2xl');
    sections.forEach((section, index) => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        section.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        section.style.transitionDelay = (index * 0.1) + 's';

        setTimeout(() => {
            section.style.opacity = '1';
            section.style.transform = 'translateY(0)';
        }, 100);
    });

    // Form field focus enhancement
    const inputs = customerForm.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
        });
    });

    // Tooltip functionality
    const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
    tooltipTriggers.forEach(trigger => {
        trigger.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute z-50 px-2 py-1 text-xs text-white bg-gray-900 rounded shadow-lg';
            tooltip.textContent = this.getAttribute('data-tooltip');
            tooltip.style.bottom = '100%';
            tooltip.style.left = '50%';
            tooltip.style.transform = 'translateX(-50%)';
            tooltip.style.marginBottom = '5px';

            this.style.position = 'relative';
            this.appendChild(tooltip);
        });

        trigger.addEventListener('mouseleave', function() {
            const tooltip = this.querySelector('.absolute.z-50');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });
});
</script>

<style>
/* Custom styles for the create form */
.form-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.input-focus:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    border-color: #3b82f6;
}

/* Animated progress bar */
#progress-bar {
    transition: width 0.3s ease, background-color 0.3s ease;
}

/* Custom radio button styling */
input[type="radio"]:checked + label {
    border-color: #3b82f6;
    background-color: rgba(59, 130, 246, 0.05);
}

/* Hover effects */
.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

/* Loading animation */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Form validation styles */
.field-error {
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Notification animation */
.notification-enter {
    opacity: 0;
    transform: translateX(100%);
}

.notification-enter-active {
    opacity: 1;
    transform: translateX(0);
    transition: opacity 300ms, transform 300ms;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .grid-cols-1.sm\:grid-cols-2 {
        grid-template-columns: 1fr;
    }

    .flex.items-center.justify-between {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }

    .space-x-4 > * + * {
        margin-left: 0;
        margin-top: 0.5rem;
    }
}

/* Custom scrollbar for modal */
.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: rgba(243, 244, 246, 0.8);
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: rgba(107, 114, 128, 0.5);
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: rgba(75, 85, 99, 0.7);
}
</style>
@endsection
