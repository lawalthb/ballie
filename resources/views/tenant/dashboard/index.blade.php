@extends('layouts.tenant')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Welcome back! Here\'s what\'s happening with your business today.')

@section('content')
<style>
    .metric-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.6s;
    }

    .metric-card:hover::before {
        left: 100%;
    }

    .metric-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    .chart-container {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .insight-card {
        background: linear-gradient(135deg, var(--color-blue) 0%, var(--color-deep-purple) 100%);
        position: relative;
        overflow: hidden;
    }

    .insight-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .transaction-row {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .transaction-row:hover {
        background: linear-gradient(135deg, rgba(43, 99, 153, 0.05) 0%, rgba(74, 53, 112, 0.05) 100%);
        transform: translateX(4px);
    }

    .quick-action-btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .quick-action-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translate(-50%, -50%);
    }

    .quick-action-btn:hover::before {
        width: 200px;
        height: 200px;
    }

    .quick-action-btn:hover {
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.15);
    }

    .activity-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 12px;
        padding: 16px;
        margin: 8px 0;
    }

    .activity-item:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(248, 250, 252, 0.8) 100%);
        transform: translateX(8px);
        box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.1);
    }

    .status-badge {
        position: relative;
        overflow: hidden;
    }

    .status-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    .gradient-text {
        background: linear-gradient(135deg, var(--color-blue) 0%, var(--color-deep-purple) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .pulse-dot {
        animation: pulse-dot 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse-dot {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.8;
            transform: scale(1.1);
        }
    }
</style>

<div class="space-y-8">
    <!-- Welcome Section -->
    <div class="relative overflow-hidden rounded-2xl p-8 text-white insight-card shadow-2xl">
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Good {{ date('H') < 12 ? 'Morning' : (date('H') < 18 ? 'Afternoon' : 'Evening') }}, {{ auth()->user()->name ?? 'User' }}! 👋</h2>
                    <p class="text-blue-100 text-lg">Your business is performing well today. Here's your overview.</p>
                </div>
                <div class="hidden md:flex space-x-4">
                    <a href="#create-invoice" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 backdrop-blur-sm border border-white border-opacity-20">
                        Create Invoice
                    </a>
                    <a href="#view-reports" class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg">
                        View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Revenue -->
        <div class="metric-card rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold gradient-text">₦2,847,650</p>
                    <div class="flex items-center mt-2">
                        <span class="text-green-500 text-sm font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            +12.5%
                        </span>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Expenses -->
        <div class="metric-card rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Expenses</p>
                    <p class="text-3xl font-bold gradient-text">₦1,234,890</p>
                    <div class="flex items-center mt-2">
                        <span class="text-red-500 text-sm font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            +8.2%
                        </span>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-red-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="metric-card rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Net Profit</p>
                    <p class="text-3xl font-bold gradient-text">₦1,612,760</p>
                    <div class="flex items-center mt-2">
                        <span class="text-green-500 text-sm font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            +18.7%
                        </span>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Outstanding Invoices -->
        <div class="metric-card rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Outstanding</p>
                    <p class="text-3xl font-bold gradient-text">₦456,320</p>
                    <div class="flex items-center mt-2">
                        <span class="text-yellow-500 text-sm font-medium flex items-center">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2 pulse-dot"></div>
                            12 invoices
                        </span>
                        <span class="text-gray-500 text-sm ml-2">pending</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl p-6 shadow-lg">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="#create-invoice" class="quick-action-btn bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Create Invoice</span>
            </a>

            <a href="#add-expense" class="quick-action-btn bg-gradient-to-br from-red-500 to-red-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Add Expense</span>
            </a>

            <a href="#add-customer" class="quick-action-btn bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Add Customer</span>
            </a>

            <a href="#add-product" class="quick-action-btn bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Add Product</span>
            </a>

            <a href="#record-payment" class="quick-action-btn bg-gradient-to-br from-teal-500 to-teal-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Record Payment</span>
            </a>

            <a href="#view-reports" class="quick-action-btn bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-4 rounded-xl text-center shadow-lg">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">View Reports</span>
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Financial Overview Chart -->
        <div class="lg:col-span-2">
            <div class="chart-container rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Financial Overview</h3>
                    <div class="flex space-x-2">
                        <button class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            Monthly
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            Yearly
                        </button>
                    </div>
                </div>
                <div class="h-80 flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                    <canvas id="financialChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Business Insights -->
        <div class="space-y-6">
            <!-- Alerts & Notifications -->
            <div class="bg-white rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <div class="w-2 h-2 bg-red-500 rounded-full mr-3 pulse-dot"></div>
                    Alerts & Notifications
                </h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3 p-3 bg-red-50 rounded-xl border-l-4 border-red-500">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-red-800">Overdue Invoices</p>
                            <p class="text-xs text-red-600 mt-1">5 invoices are overdue totaling ₦234,500</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-xl border-l-4 border-yellow-500">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-yellow-800">Low Stock Alert</p>
                            <p class="text-xs text-yellow-600 mt-1">3 products are running low on inventory</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-xl border-l-4 border-blue-500">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-blue-800">Tax Reminder</p>
                            <p class="text-xs text-blue-600 mt-1">VAT filing due in 7 days</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">This Month's Sales</span>
                        <span class="text-sm font-semibold text-gray-900">₦847,230</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: 68%"></div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Customer Growth</span>
                        <span class="text-sm font-semibold text-green-600">+24%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full" style="width: 24%"></div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Expense Ratio</span>
                        <span class="text-sm font-semibold text-yellow-600">43%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-2 rounded-full" style="width: 43%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions & Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Transactions -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Recent Transactions</h3>
                <a href="#all-transactions" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                    View All
                </a>
            </div>
            <div class="space-y-1">
                <div class="transaction-row flex items-center justify-between p-4 rounded-xl">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Payment from Adebayo Ltd</p>
                            <p class="text-sm text-gray-500">Invoice #INV-2024-001</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-green-600">+₦125,000</p>
                        <p class="text-xs text-gray-500">2 hours ago</p>
                    </div>
                </div>

                <div class="transaction-row flex items-center justify-between p-4 rounded-xl">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Office Rent Payment</p>
                            <p class="text-sm text-gray-500">Monthly expense</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-red-600">-₦85,000</p>
                        <p class="text-xs text-gray-500">5 hours ago</p>
                    </div>
                </div>

                <div class="transaction-row flex items-center justify-between p-4 rounded-xl">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">New Invoice Created</p>
                            <p class="text-sm text-gray-500">Kemi Enterprises</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-blue-600">₦67,500</p>
                        <p class="text-xs text-gray-500">1 day ago</p>
                    </div>
                </div>

                <div class="transaction-row flex items-center justify-between p-4 rounded-xl">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Inventory Purchase</p>
                            <p class="text-sm text-gray-500">Office supplies</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-red-600">-₦23,450</p>
                        <p class="text-xs text-gray-500">2 days ago</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Recent Activity</h3>
                <a href="#activity-log" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                    View All
                </a>
            </div>
            <div class="space-y-1">
                <div class="activity-item">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">New customer added</p>
                            <p class="text-xs text-gray-500 mt-1">Tunde Bakare was added to your customer list</p>
                            <p class="text-xs text-gray-400 mt-1">30 minutes ago</p>
                        </div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Invoice payment received</p>
                            <p class="text-xs text-gray-500 mt-1">₦125,000 payment for Invoice #INV-2024-001</p>
                            <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                        </div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Low stock alert</p>
                            <p class="text-xs text-gray-500 mt-1">Office Paper is running low (5 units remaining)</p>
                            <p class="text-xs text-gray-400 mt-1">4 hours ago</p>
                        </div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Monthly report generated</p>
                            <p class="text-xs text-gray-500 mt-1">Financial report for November 2024 is ready</p>
                            <p class="text-xs text-gray-400 mt-1">1 day ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Due Dates -->
    <div class="bg-white rounded-2xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Upcoming Due Dates</h3>
            <a href="#calendar" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                View Calendar
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-red-50 to-red-100 p-4 rounded-xl border-l-4 border-red-500">
                <div class="flex items-center justify-between mb-2">
                    <span class="status-badge bg-red-500 text-white text-xs font-medium px-2 py-1 rounded-full">Overdue</span>
                    <span class="text-sm text-red-600 font-medium">5 days ago</span>
                </div>
<h4 class="font-semibold text-gray-900 mb-1">Invoice #INV-2024-045</h4>
                <p class="text-sm text-gray-600 mb-2">Emeka Trading Company</p>
                <p class="text-lg font-bold text-red-600">₦89,500</p>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-xl border-l-4 border-yellow-500">
                <div class="flex items-center justify-between mb-2">
                    <span class="status-badge bg-yellow-500 text-white text-xs font-medium px-2 py-1 rounded-full">Due Soon</span>
                    <span class="text-sm text-yellow-600 font-medium">3 days</span>
                </div>
                <h4 class="font-semibold text-gray-900 mb-1">VAT Filing</h4>
                <p class="text-sm text-gray-600 mb-2">Federal Inland Revenue Service</p>
                <p class="text-lg font-bold text-yellow-600">Tax Return</p>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-2">
                    <span class="status-badge bg-blue-500 text-white text-xs font-medium px-2 py-1 rounded-full">Upcoming</span>
                    <span class="text-sm text-blue-600 font-medium">7 days</span>
                </div>
                <h4 class="font-semibold text-gray-900 mb-1">Rent Payment</h4>
                <p class="text-sm text-gray-600 mb-2">Office Space Rental</p>
                <p class="text-lg font-bold text-blue-600">₦150,000</p>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Financial Overview Chart
    const ctx = document.getElementById('financialChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue',
                    data: [120000, 190000, 300000, 500000, 200000, 300000, 450000, 600000, 750000, 850000, 900000, 1000000],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                }, {
                    label: 'Expenses',
                    data: [80000, 120000, 180000, 250000, 150000, 200000, 280000, 350000, 400000, 450000, 500000, 550000],
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgb(239, 68, 68)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ₦' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#6B7280'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#6B7280',
                            callback: function(value) {
                                return '₦' + (value / 1000) + 'k';
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                elements: {
                    point: {
                        hoverBackgroundColor: '#fff'
                    }
                }
            }
        });
    }

    // Add some interactive animations
    const metricCards = document.querySelectorAll('.metric-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });

    metricCards.forEach((card) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Add click animations to quick action buttons
    const quickActionBtns = document.querySelectorAll('.quick-action-btn');
    quickActionBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            // Create ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});
</script>

<style>
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    pointer-events: none;
}

@keyframes ripple-animation {
    to {
        transform: scale(4);
        opacity: 0;
    }
}
</style>
@endsection
