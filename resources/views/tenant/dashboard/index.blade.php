@extends('layouts.tenant')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600">Welcome to your Ballie dashboard</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Dashboard Content -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Quick Stats -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h2>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Invoices</span>
                        <span class="text-sm font-medium text-gray-700">0</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Customers</span>
                        <span class="text-sm font-medium text-gray-700">0</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Products</span>
                        <span class="text-sm font-medium text-gray-700">0</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h2>
            <div class="text-center py-8 text-gray-500">
                <p>No recent activity yet.</p>
                <p class="text-sm mt-2">Your activity will appear here as you use the system.</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('tenant.invoices.create', ['tenant' => request()->route('tenant')]) }}" class="block w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Create Invoice</span>
                    </div>
                </a>
                <a href="{{ route('tenant.customers.create', ['tenant' => request()->route('tenant')]) }}" class="block w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <span>Add Customer</span>
                    </div>
                </a>
                <a href="{{ route('tenant.products.create', ['tenant' => request()->route('tenant')]) }}" class="block w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span>Add Product</span>
                    </div>
                </a>
                <a href="{{ route('tenant.support', ['tenant' => request()->route('tenant')]) }}" class="block w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Get Help</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Getting Started Guide -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Getting Started Guide</h2>
            <button class="text-sm text-gray-500 hover:text-gray-700">Dismiss</button>
        </div>

        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <span class="text-blue-600 font-semibold">1</span>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Set up your business profile</h3>
                    <p class="text-sm text-gray-600 mt-1">Complete your business information to personalize your invoices and reports.</p>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 mt-1 inline-block">
                        Complete Profile →
                    </a>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <span class="text-blue-600 font-semibold">2</span>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Add your products or services</h3>
                    <p class="text-sm text-gray-600 mt-1">Add the products or services you offer to quickly add them to invoices.</p>
                    <a href="{{ route('tenant.products.create', ['tenant' => request()->route('tenant')]) }}" class="text-sm text-blue-600 hover:text-blue-800 mt-1 inline-block">
                        Add Products →
                    </a>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <span class="text-blue-600 font-semibold">3</span>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Add your customers</h3>
                    <p class="text-sm text-gray-600 mt-1">Add your customers to easily create invoices and track their information.</p>
                    <a href="{{ route('tenant.customers.create', ['tenant' => request()->route('tenant')]) }}" class="text-sm text-blue-600 hover:text-blue-800 mt-1 inline-block">
                        Add Customers →
                    </a>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <span class="text-blue-600 font-semibold">4</span>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Create your first invoice</h3>
                    <p class="text-sm text-gray-600 mt-1">Start generating professional invoices for your customers.</p>
                    <a href="{{ route('tenant.invoices.create', ['tenant' => request()->route('tenant')]) }}" class="text-sm text-blue-600 hover:text-blue-800 mt-1 inline-block">
                        Create Invoice →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Invoices and Customers -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Recent Invoices -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Invoices</h2>
                <a href="{{ route('tenant.invoices.index', ['tenant' => request()->route('tenant')]) }}" class="text-sm text-blue-600 hover:text-blue-800">
                    View All
                </a>
            </div>

            <div class="text-center py-8 text-gray-500">
                <p>No invoices yet.</p>
                <p class="text-sm mt-2">Your recent invoices will appear here.</p>
                <a href="{{ route('tenant.invoices.create', ['tenant' => request()->route('tenant')]) }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    Create Invoice
                </a>
            </div>
        </div>

        <!-- Recent Customers -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Customers</h2>
                <a href="{{ route('tenant.customers.index', ['tenant' => request()->route('tenant')]) }}" class="text-sm text-blue-600 hover:text-blue-800">
                    View All
                </a>
            </div>

            <div class="text-center py-8 text-gray-500">
                <p>No customers yet.</p>
                <p class="text-sm mt-2">Your recent customers will appear here.</p>
                <a href="{{ route('tenant.customers.create', ['tenant' => request()->route('tenant')]) }}" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                    Add Customer
                </a>
            </div>
        </div>
    </div>
</div>
@endsection