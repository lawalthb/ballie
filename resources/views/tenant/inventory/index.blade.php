@extends('layouts.tenant')

@section('title', 'Inventory Dashboard')
@section('page-title', 'Inventory')
@section('page-description', 'Manage your products, stock levels, and inventory operations.')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="relative overflow-hidden rounded-2xl p-8 text-white gradient-bg-primary shadow-2xl">
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Inventory Dashboard</h2>
                    <p class="text-blue-100 text-lg">Track your products, manage stock levels, and optimize your inventory.</p>
                </div>
                <div class="hidden md:flex space-x-4">
                    <a href="{{ route('tenant.inventory.products.create', ['tenant' => $tenant->slug]) }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 backdrop-blur-sm border border-white border-opacity-20">
                        Add Product
                    </a>
                    <a href="{{ route('tenant.inventory.products.index', ['tenant' => $tenant->slug]) }}" class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg">
                        View Products
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl p-6 shadow-lg">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('tenant.inventory.products.create', ['tenant' => $tenant->slug]) }}" class="quick-action-btn bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Add Product</span>
            </a>

            <a href="{{ route('tenant.inventory.products.index', ['tenant' => $tenant->slug]) }}" class="quick-action-btn bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">View Products</span>
            </a>

            <a href="#" class="quick-action-btn bg-gradient-to-br from-red-500 to-red-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Categories</span>
            </a>

            <a href="#" class="quick-action-btn bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Units</span>
            </a>

            <a href="#" class="quick-action-btn bg-gradient-to-br from-teal-500 to-teal-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Stock Movement</span>
            </a>

            <a href="{{ route('tenant.reports.index', ['tenant' => $tenant->slug]) }}" class="quick-action-btn bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Inventory Reports</span>
            </a>
        </div>
    </div>

    <!-- Inventory Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Total Products</h3>
                    <p class="text-2xl font-bold text-blue-600">245</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Total Stock Value</h3>
                    <p class="text-2xl font-bold text-green-600">₦4,567,890</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Low Stock Items</h3>
                    <p class="text-2xl font-bold text-red-600">12</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Out of Stock</h3>
                    <p class="text-2xl font-bold text-yellow-600">3</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="bg-white rounded-2xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Low Stock Alert</h3>
            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                View All
            </a>
        </div>
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No low stock items</h3>
            <p class="text-gray-500 mb-4">All your products are well stocked. Great job!</p>
            <a href="{{ route('tenant.inventory.products.index', ['tenant' => $tenant->slug]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                View All Products
            </a>
        </div>
    </div>
</div>
@endsection