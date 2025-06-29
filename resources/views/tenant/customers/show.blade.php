@extends('layouts.tenant')

@section('title', 'Customer Details')
@section('page-title', 'Customer Details')
@section('page-description', 'View detailed information about this customer.')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-xl">
                {{ substr($customer->customer_type == 'individual' ? $customer->first_name : $customer->company_name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    @if($customer->customer_type == 'individual')
                        {{ $customer->first_name }} {{ $customer->last_name }}
                    @else
                        {{ $customer->company_name }}
                    @endif
                </h1>
                <p class="text-sm text-gray-500">
                    {{ $customer->customer_type == 'individual' ? 'Individual Customer' : 'Business Customer' }}
                    @if($customer->status == 'active')
                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="mt-4 md:mt-0 flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
            <a href="{{ route('tenant.invoices.create', ['customer_id' => $customer->id]) }}" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl text-white hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 002-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Create Invoice
            </a>

            <a href="{{ route('tenant.customers.edit', $customer->id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm">
                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Customer
            </a>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                    </svg>
                    More Actions
                </button>

                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-10" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95">
                    <div class="py-1">
                        <a href="#" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Send Email
                        </a>
                        <a href="#" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Record Payment
                        </a>
                    </div>
                    <div class="py-1">
                        <a href="#" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print Details
                        </a>
                    </div>
                    <div class="py-1">
                        <form action="{{ route('tenant.customers.destroy', $customer->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="group flex w-full items-center px-4 py-2 text-sm text-red-700 hover:bg-red-100" onclick="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
                                <svg class="mr-3 h-5 w-5 text-red-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Customer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="md:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($customer->customer_type == 'individual')
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Full Name</h3>
                            <p class="mt-1 text-base text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</p>
                        </div>
                    @else
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Company Name</h3>
                            <p class="mt-1 text-base text-gray-900">{{ $customer->company_name }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Tax ID / VAT Number</h3>
                            <p class="mt-1 text-base text-gray-900">{{ $customer->tax_id ?? 'Not provided' }}</p>
                        </div>
                    @endif

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Email Address</h3>
                        <p class="mt-1 text-base text-gray-900">
                            <a href="mailto:{{ $customer->email }}" class="text-blue-600 hover:text-blue-800">
                                {{ $customer->email }}
                            </a>
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Phone Number</h3>
                        <p class="mt-1 text-base text-gray-900">
                            <a href="tel:{{ $customer->phone }}" class="text-blue-600 hover:text-blue-800">
                                {{ $customer->phone ?? 'Not provided' }}
                            </a>
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Mobile Number</h3>
                        <p class="mt-1 text-base text-gray-900">
                            <a href="tel:{{ $customer->mobile }}" class="text-blue-600 hover:text-blue-800">
                                {{ $customer->mobile ?? 'Not provided' }}
                            </a>
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Customer Since</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $customer->created_at->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Address Information Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Address Information</h2>

                @if($customer->address_line1)
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Billing Address</h3>
                            <p class="mt-1 text-base text-gray-900">
                                {{ $customer->address_line1 }}<br>
                                @if($customer->address_line2){{ $customer->address_line2 }}<br>@endif
                                {{ $customer->city }}@if($customer->state), {{ $customer->state }}@endif @if($customer->postal_code) {{ $customer->postal_code }}@endif<br>
                                {{ $customer->country ?? '' }}
                            </p>
                        </div>

                        <div class="mt-2">
                            <a href="https://maps.google.com/?q={{ urlencode($customer->full_address) }}" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                View on Map
                            </a>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 italic">No address information provided.</p>
                @endif
            </div>

            <!-- Additional Information Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Preferred Currency</h3>
                        <p class="mt-1 text-base text-gray-900">
                            @switch($customer->currency)
                                @case('NGN')
                                    Nigerian Naira (₦)
                                    @break
                                @case('USD')
                                    US Dollar ($)
                                    @break
                                @case('EUR')
                                    Euro (€)
                                    @break
                                @case('GBP')
                                    British Pound (£)
                                    @break
                                @case('GHS')
                                    Ghanaian Cedi (₵)
                                    @break
                                @case('KES')
                                    Kenyan Shilling (KSh)
                                    @break
                                @case('ZAR')
                                    South African Rand (R)
                                    @break
                                @default
                                    {{ $customer->currency }}
                            @endswitch
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Payment Terms</h3>
                        <p class="mt-1 text-base text-gray-900">
                            @switch($customer->payment_terms)
                                @case('due_on_receipt')
                                    Due on Receipt
                                    @break
                                @case('net_7')
                                    Net 7 Days
                                    @break
                                @case('net_15')
                                    Net 15 Days
                                    @break
                                @case('net_30')
                                    Net 30 Days
                                    @break
                                @case('net_60')
                                    Net 60 Days
                                    @break
                                @case('custom')
                                    Custom
                                    @break
                                @default
                                    {{ $customer->payment_terms ?? 'Not specified' }}
                            @endswitch
                        </p>
                    </div>

                    @if($customer->notes)
                        <div class="col-span-1 md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500">Notes</h3>
                            <p class="mt-1 text-base text-gray-900 whitespace-pre-line">{{ $customer->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Financial Summary Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Financial Summary</h2>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Total Spent</h3>
                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ $customer->currency }} {{ number_format($customer->total_spent, 2) }}</p>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-500">Outstanding Balance</h3>
                        <p class="mt-1 text-xl font-semibold text-red-600">{{ $customer->currency }} {{ number_format($outstandingBalance ?? 0, 2) }}</p>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-500">Last Invoice</h3>
                        @if($customer->last_invoice_date)
                            <p class="mt-1 text-base text-gray-900">
                                <span class="font-medium">{{ $customer->last_invoice_number }}</span><br>
                                <span class="text-sm text-gray-500">{{ $customer->last_invoice_date->format('F d, Y') }}</span>
                            </p>
                        @else
                            <p class="mt-1 text-base text-gray-500 italic">No invoices yet</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>

                <div class="space-y-3">
                    <a href="{{ route('tenant.invoices.create', ['customer_id' => $customer->id]) }}" class="flex items-center p-3 bg-blue-50 rounded-xl text-blue-700 hover:bg-blue-100 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 002-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Create New Invoice
                    </a>

                    <a href="#" class="flex items-center p-3 bg-green-50 rounded-xl text-green-700 hover:bg-green-100 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Record Payment
                    </a>

                    <a href="#" class="flex items-center p-3 bg-purple-50 rounded-xl text-purple-700 hover:bg-purple-100 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Send Statement
                    </a>

                    <a href="{{ route('tenant.customers.edit', $customer->id) }}" class="flex items-center p-3 bg-gray-50 rounded-xl text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Customer
                    </a>
                </div>
            </div>

            <!-- Activity Timeline Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h2>

                @if(isset($activities) && count($activities) > 0)
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($activities as $activity)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex items-start space-x-3">
                                            <div class="relative">
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center ring-8 ring-white">
                                                    <!-- Icon based on activity type -->
                                                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $activity->description }}
                                                    </div>
                                                    <p class="mt-0.5 text-sm text-gray-500">
                                                        {{ $activity->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-gray-500 italic">No recent activity found.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Invoices and Transactions -->
    <div class="bg-white rounded-2xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-medium text-gray-900">Invoices & Transactions</h2>

            <a href="{{ route('tenant.invoices.index', ['customer_id' => $customer->id]) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                View All
            </a>
        </div>

        @if(isset($invoices) && count($invoices) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Invoice #
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Due Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($invoices as $invoice)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <a href="{{ route('tenant.invoices.show', $invoice->id) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $invoice->invoice_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $invoice->due_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $customer->currency }} {{ number_format($invoice->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($invoice->status)
                                        @case('draft')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Draft
                                            </span>
                                            @break
                                        @case('sent')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Sent
                                            </span>
                                            @break
                                        @case('paid')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Paid
                                            </span>
                                            @break
                                        @case('overdue')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Overdue
                                            </span>
                                            @break
                                        @case('partial')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Partial
                                            </span>
                                            @break
                                        @default
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('tenant.invoices.show', $invoice->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        View
                                    </a>
                                    <a href="{{ route('tenant.invoices.edit', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No invoices yet</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by
