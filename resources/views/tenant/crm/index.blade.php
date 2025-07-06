@extends('layouts.tenant')

@section('title', 'CRM Dashboard')
@section('page-title', 'CRM')
@section('page-description', 'Manage your customers, vendors, and relationships to grow your business.')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="relative overflow-hidden rounded-2xl p-8 text-white gradient-bg-primary shadow-2xl">
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold mb-2">CRM Dashboard</h2>
                    <p class="text-blue-100 text-lg">Build stronger relationships and manage your customers and vendors effectively.</p>
                </div>
                <div class="hidden md:flex space-x-4">
                    <a href="{{ route('tenant.crm.customers.create', ['tenant' => $tenant->slug]) }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 backdrop-blur-sm border border-white border-opacity-20">
                        Add Customer
                    </a>
                    <a href="{{ route('tenant.crm.customers.index', ['tenant' => $tenant->slug]) }}" class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg">
                        View All Customers
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl p-6 shadow-lg">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('tenant.crm.customers.create', ['tenant' => $tenant->slug]) }}" class="quick-action-btn bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Add Customer</span>
            </a>

            <a href="{{ route('tenant.crm.customers.index', ['tenant' => $tenant->slug]) }}" class="quick-action-btn bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">View Customers</span>
            </a>

            <a href="#" class="quick-action-btn bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Add Vendor</span>
            </a>

            <a href="#" class="quick-action-btn bg-gradient-to-br from-red-500 to-red-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Create Quote</span>
            </a>

            <a href="#" class="quick-action-btn bg-gradient-to-br from-teal-500 to-teal-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Schedule Meeting</span>
            </a>

            <a href="#" class="quick-action-btn bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">CRM Reports</span>
            </a>
        </div>
    </div>

    <!-- CRM Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Total Customers</h3>
                    <p class="text-2xl font-bold text-blue-600">1,247</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Active Customers</h3>
                    <p class="text-2xl font-bold text-green-600">1,089</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Total Vendors</h3>
                    <p class="text-2xl font-bold text-purple-600">89</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Pending Quotes</h3>
                    <p class="text-2xl font-bold text-yellow-600">23</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Customers -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Recent Customers</h3>
                <a href="{{ route('tenant.crm.customers.index', ['tenant' => $tenant->slug]) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                    View All
                </a>
            </div>
            <div class="space-y-4">
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        JD
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">John Doe</p>
                                <p class="text-xs text-gray-500">john.doe@example.com</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Added today</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        AS
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Alice Smith</p>
                                <p class="text-xs text-gray-500">alice.smith@company.com</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">2 days ago</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        MB
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Mike Brown</p>
                                <p class="text-xs text-gray-500">mike.brown@business.com</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">3 days ago</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        SJ
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Sarah Johnson</p>
                                <p class="text-xs text-gray-500">sarah.j@startup.com</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">1 week ago</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        RW
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Robert Wilson</p>
                                <p class="text-xs text-gray-500">robert.w@enterprise.com</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">1 week ago</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Recent Activities</h3>
                <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                    View All
                </a>
            </div>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">
                            <span class="font-medium">John Doe</span> was added as a new customer
                        </p>
                        <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">
                            Quote #QT-001 was sent to <span class="font-medium">Alice Smith</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">4 hours ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">
                            Meeting scheduled with <span class="font-medium">Mike Brown</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">6 hours ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">
                            New vendor <span class="font-medium">Tech Solutions Inc.</span> was added
                        </p>
                        <p class="text-xs text-gray-500 mt-1">1 day ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">
                            Follow-up reminder for <span class="font-medium">Sarah Johnson</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">2 days ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">
                            Deal closed with <span class="font-medium">Robert Wilson</span> - $15,000
                        </p>
                        <p class="text-xs text-gray-500 mt-1">3 days ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.quick-action-btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.quick-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.quick-action-btn:active {
    transform: translateY(0);
}
</style>
@endsection