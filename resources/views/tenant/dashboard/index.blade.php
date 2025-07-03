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

    /* Dark theme styles */
    .dark-theme {
        background-color: #1a202c;
        color: #e2e8f0;
    }

    .dark-theme .bg-white {
        background-color: #2d3748;
    }

    .dark-theme .text-gray-900 {
        color: #e2e8f0;
    }

    .dark-theme .text-gray-600 {
        color: #a0aec0;
    }

    .dark-theme .text-gray-500 {
        color: #718096;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .grid-cols-4 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .lg\:col-span-2 {
            grid-column: span 1;
        }
    }

    @media (max-width: 640px) {
        .grid-cols-4 {
            grid-template-columns: minmax(0, 1fr);
        }

        .grid-cols-6 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    /* Print styles */
    @media print {
        .quick-action-btn,
        .no-print {
            display: none !important;
        }

        .bg-gradient-to-br {
            background: #f8f9fa !important;
            color: #000 !important;
        }

        .shadow-lg {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
    }

    /* Accessibility improvements */
    @media (prefers-reduced-motion: reduce) {
        .metric-card,
        .quick-action-btn,
        .transition-all,
        .transition-colors,
        .transition-transform {
            transition: none !important;
        }

        .pulse-dot {
            animation: none !important;
        }
    }

    /* High contrast mode */
    @media (prefers-contrast: high) {
        .bg-gradient-to-br {
            background: #fff !important;
            border: 2px solid #000 !important;
        }

        .text-blue-600 {
            color: #0000ff !important;
        }

        .text-green-600 {
            color: #008000 !important;
        }

        .text-red-600 {
            color: #ff0000 !important;
        }
    }

    /* Loading states */
    .loading {
        position: relative;
        pointer-events: none;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Custom scrollbar for webkit browsers */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
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

            <a href="{{ route('tenant.customers.index', ['tenant' => tenant()->slug]) }}" class="quick-action-btn bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-xl text-center shadow-lg">
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
                        <button class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors" data-period="monthly">
                            Monthly
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" data-period="yearly">
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
                                <p class="text-sm text-gray-500">Invoice #SV-2024-001</p>
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
                                <p class="text-xs text-gray-500 mt-1">₦125,000 payment for Invoice #SV-2024-001</p>
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
                    <h4 class="font-semibold text-gray-900 mb-1">Invoice #SV-2024-045</h4>
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
        // Chart data
        const chartData = @json($chartData);
        // Financial Overview Chart
        const ctx = document.getElementById('financialChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Revenue',
                        data: chartData.revenue,
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
                        data: chartData.expenses,
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

        // Auto-refresh dashboard data every 5 minutes
        setInterval(function() {
            // Only refresh if the page is visible
            if (!document.hidden) {
                console.log('Refreshing dashboard data...');
                // TODO: Implement AJAX refresh of key metrics
            }
        }, 300000); // 5 minutes

        // Handle period filter changes
        const periodButtons = document.querySelectorAll('[data-period]');
        periodButtons.forEach(button => {
            button.addEventListener('click', function() {
                const period = this.dataset.period;

                // Update active state
                periodButtons.forEach(btn => btn.classList.remove('bg-blue-50', 'text-blue-600'));
                this.classList.add('bg-blue-50', 'text-blue-600');

                // TODO: Fetch and update chart data for selected period
                console.log('Period changed to:', period);
            });
        });

        // Initialize tooltips
        const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
        tooltipTriggers.forEach(trigger => {
            trigger.addEventListener('mouseenter', function() {
                const tooltip = document.createElement('div');
                tooltip.className = 'absolute bg-gray-900 text-white text-xs rounded py-1 px-2 pointer-events-none z-50';
                tooltip.textContent = this.dataset.tooltip;

                document.body.appendChild(tooltip);

                const rect = this.getBoundingClientRect();
                tooltip.style.left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + 'px';
                tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';

                this._tooltip = tooltip;
            });

            trigger.addEventListener('mouseleave', function() {
                if (this._tooltip) {
                    this._tooltip.remove();
                    this._tooltip = null;
                }
            });
        });

        // Handle quick action shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + key combinations for quick actions
            if ((e.ctrlKey || e.metaKey) && e.shiftKey) {
                switch(e.key) {
                    case 'I':
                        e.preventDefault();
                        window.location.href = '#create-invoice';
                        break;
                    case 'E':
                        e.preventDefault();
                        window.location.href = '#add-expense';
                        break;
                    case 'C':
                        e.preventDefault();
                        window.location.href = '{{ route("tenant.customers.index", ["tenant" => tenant()->slug]) }}';
                        break;
                    case 'P':
                        e.preventDefault();
                        window.location.href = '#add-product';
                        break;
                }
            }
        });

        // Dashboard theme toggle
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                document.body.classList.toggle('dark-theme');
                // TODO: Save theme preference
            });
        }

        // Real-time notifications (placeholder)
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 bg-${type === 'success' ? 'green' : type === 'error' ? 'red' : 'blue'}-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 5000);
        }

        // Simulate real-time updates (for demonstration)
        setTimeout(() => {
            showNotification('New payment received: ₦45,000', 'success');
        }, 10000);

        // Add loading states for async operations
        function showLoading(element) {
            element.classList.add('loading');
        }

        function hideLoading(element) {
            element.classList.remove('loading');
        }

        // Handle responsive chart resizing
        window.addEventListener('resize', function() {
            if (window.Chart && window.Chart.instances) {
                window.Chart.instances.forEach(chart => {
                    chart.resize();
                });
            }
        });

        // Initialize progressive web app features
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('SW registered: ', registration);
                })
                .catch(registrationError => {
                console.log('SW registration failed: ', registrationError);
            });
    }

    // Handle offline/online status
    function updateOnlineStatus() {
        const statusIndicator = document.getElementById('online-status');
        if (statusIndicator) {
            if (navigator.onLine) {
                statusIndicator.className = 'w-2 h-2 bg-green-500 rounded-full';
                statusIndicator.title = 'Online';
            } else {
                statusIndicator.className = 'w-2 h-2 bg-red-500 rounded-full';
                statusIndicator.title = 'Offline';
            }
        }
    }

    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
    updateOnlineStatus();

    // Performance monitoring
    if ('performance' in window) {
        window.addEventListener('load', function() {
            setTimeout(function() {
                const perfData = performance.getEntriesByType('navigation')[0];
                console.log('Page load time:', perfData.loadEventEnd - perfData.loadEventStart, 'ms');
            }, 0);
        });
    }

    // Error handling for chart initialization
    window.addEventListener('error', function(e) {
        console.error('Dashboard error:', e.error);
        // TODO: Send error to logging service
    });

    // Initialize dashboard features
    initializeDashboard();

    function initializeDashboard() {
        // Set up auto-save for any form inputs
        const formInputs = document.querySelectorAll('input, textarea, select');
        formInputs.forEach(input => {
            input.addEventListener('change', function() {
                // TODO: Implement auto-save functionality
                console.log('Auto-saving:', this.name, this.value);
            });
        });

        // Initialize drag and drop for dashboard widgets
        const widgets = document.querySelectorAll('.metric-card, .chart-container');
        widgets.forEach(widget => {
            widget.draggable = true;
            widget.addEventListener('dragstart', function(e) {
                e.dataTransfer.setData('text/plain', this.id);
            });
        });

        // Set up keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });

        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });

        // Initialize search functionality
        const searchInput = document.querySelector('#dashboard-search');
        if (searchInput) {
            searchInput.addEventListener('input', debounce(function() {
                const query = this.value.toLowerCase();
                // TODO: Implement dashboard search
                console.log('Searching for:', query);
            }, 300));
        }

        // Set up print functionality
        const printButton = document.querySelector('#print-dashboard');
        if (printButton) {
            printButton.addEventListener('click', function() {
                window.print();
            });
        }

        // Initialize export functionality
        const exportButton = document.querySelector('#export-dashboard');
        if (exportButton) {
            exportButton.addEventListener('click', function() {
                // TODO: Implement dashboard export
                console.log('Exporting dashboard data...');
            });
        }
    }

    // Utility function for debouncing
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Handle browser back/forward navigation
    window.addEventListener('popstate', function(e) {
        if (e.state && e.state.dashboardView) {
            // TODO: Restore dashboard state
            console.log('Restoring dashboard state:', e.state);
        }
    });

    // Save dashboard state
    function saveDashboardState() {
        const state = {
            dashboardView: true,
            timestamp: Date.now()
        };
        history.replaceState(state, '', window.location.href);
    }

    saveDashboardState();

    // Initialize accessibility features
    function initializeAccessibility() {
        // Add ARIA labels to interactive elements
        const interactiveElements = document.querySelectorAll('button, a, input, select, textarea');
        interactiveElements.forEach(element => {
            if (!element.getAttribute('aria-label') && !element.getAttribute('aria-labelledby')) {
                const text = element.textContent || element.value || element.placeholder;
                if (text) {
                    element.setAttribute('aria-label', text.trim());
                }
            }
        });

        // Add focus indicators
        const focusableElements = document.querySelectorAll('button, a, input, select, textarea, [tabindex]');
        focusableElements.forEach(element => {
            element.addEventListener('focus', function() {
                this.classList.add('focus-visible');
            });
            element.addEventListener('blur', function() {
                this.classList.remove('focus-visible');
            });
        });
    }

    initializeAccessibility();

    // Handle dashboard refresh
    const refreshButton = document.querySelector('#refresh-dashboard');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            showLoading(this);
            // TODO: Implement dashboard refresh
            setTimeout(() => {
                hideLoading(this);
                showNotification('Dashboard refreshed successfully', 'success');
            }, 2000);
        });
    }

    // Initialize dashboard customization
    function initializeDashboardCustomization() {
        const customizeButton = document.querySelector('#customize-dashboard');
        if (customizeButton) {
            customizeButton.addEventListener('click', function() {
                // TODO: Show dashboard customization modal
                console.log('Opening dashboard customization...');
            });
        }
    }

    initializeDashboardCustomization();

    // Handle dashboard sharing
    const shareButton = document.querySelector('#share-dashboard');
    if (shareButton) {
        shareButton.addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                    title: 'Business Dashboard',
                    text: 'Check out my business dashboard',
                    url: window.location.href
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showNotification('Dashboard link copied to clipboard', 'success');
                });
            }
        });
    }

    // Initialize dashboard analytics
    function trackDashboardUsage() {
        // TODO: Implement analytics tracking
        console.log('Dashboard viewed at:', new Date().toISOString());
    }

    trackDashboardUsage();

    // Handle dashboard fullscreen
    const fullscreenButton = document.querySelector('#fullscreen-dashboard');
    if (fullscreenButton) {
        fullscreenButton.addEventListener('click', function() {
            if (document.fullscreenElement) {
                document.exitFullscreen();
            } else {
                document.documentElement.requestFullscreen();
            }
        });
    }

    // Monitor fullscreen changes
    document.addEventListener('fullscreenchange', function() {
        const fullscreenButton = document.querySelector('#fullscreen-dashboard');
        if (fullscreenButton) {
            if (document.fullscreenElement) {
                fullscreenButton.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            } else {
                fullscreenButton.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>';
            }
        }
    });

    // Initialize dashboard help system
    function initializeHelpSystem() {
        const helpButton = document.querySelector('#dashboard-help');
        if (helpButton) {
            helpButton.addEventListener('click', function() {
                // TODO: Show help modal or tour
                console.log('Opening dashboard help...');
            });
        }

        // Add help tooltips to complex elements
        const complexElements = document.querySelectorAll('.metric-card, .chart-container');
        complexElements.forEach((element, index) => {
            const helpTexts = [
                'This card shows your total revenue for the current period',
                'This card displays your total expenses',
                'This shows your net profit calculation',
                'Outstanding invoices that need attention',
                'Interactive chart showing financial trends'
            ];

            if (helpTexts[index]) {
                element.setAttribute('data-tooltip', helpTexts[index]);
            }
        });
    }

    initializeHelpSystem();

    // Final initialization complete
    console.log('Dashboard initialization complete');

    // Dispatch custom event for other scripts
    const dashboardReadyEvent = new CustomEvent('dashboardReady', {
        detail: {
            timestamp: Date.now(),
            version: '1.0.0'
        }
    });
    document.dispatchEvent(dashboardReadyEvent);
});

// Global dashboard utilities
window.DashboardUtils = {
    formatCurrency: function(amount) {
        return new Intl.NumberFormat('en-NG', {
            style: 'currency',
            currency: 'NGN'
        }).format(amount);
    },

    formatDate: function(date) {
        return new Intl.DateTimeFormat('en-NG', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        }).format(new Date(date));
    },

    showNotification: function(message, type = 'info') {
        // Reuse the notification function
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 bg-${type === 'success' ? 'green' : type === 'error' ? 'red' : 'blue'}-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);
    }
};
</script>

<!-- Additional CSS for enhanced features -->
<style>
    /* Focus indicators for accessibility */
    .focus-visible {
        outline: 2px solid #3B82F6;
        outline-offset: 2px;
    }

    /* Keyboard navigation styles */
    .keyboard-navigation *:focus {
        outline: 2px solid #3B82F6;
        outline-offset: 2px;
    }

    /* Loading spinner overlay */
    .loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        z-index: 1000;
    }

    /* Drag and drop styles */
    .dragging {
        opacity: 0.5;
        transform: rotate(5deg);
    }

    /* Print styles */
    @media print {
        .no-print,
        .quick-action-btn,
        button,
        .shadow-lg {
            display: none !important;
        }

        .bg-gradient-to-br {
            background: white !important;
            border: 1px solid #ccc !important;
        }

        .text-white {
            color: black !important;
        }
    }

    /* High contrast mode support */
    @media (prefers-contrast: high) {
        .bg-gradient-to-br {
            background: white !important;
            border: 2px solid black !important;
        }
    }

    /* Reduced motion support */
    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }

    /* Dark mode styles */
    .dark-theme {
        background-color: #1a202c;
        color: #e2e8f0;
    }

    .dark-theme .bg-white {
        background-color: #2d3748 !important;
        color: #e2e8f0;
    }

    .dark-theme .text-gray-900 {
        color: #e2e8f0 !important;
    }

    .dark-theme .text-gray-600 {
        color: #a0aec0 !important;
    }

    .dark-theme .text-gray-500 {
        color: #718096 !important;
    }

    .dark-theme .border-gray-200 {
        border-color: #4a5568 !important;
    }

    /* Responsive enhancements */
    @media (max-width: 640px) {
        .grid-cols-6 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .text-3xl {
            font-size: 1.5rem;
        }

        .p-8 {
            padding: 1rem;
        }
    }

    /* Animation for metric cards */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-in-up {
        animation: slideInUp 0.6s ease-out;
    }

    /* Tooltip styles */
    .tooltip {
        position: absolute;
        background: rgba(0, 0, 0, 0.9);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 1000;
        pointer-events: none;
    }

    .tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: rgba(0, 0, 0, 0.9) transparent transparent transparent;
    }
</style>
@endsection
