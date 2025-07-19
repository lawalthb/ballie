@extends('layouts.tenant')

@section('title', 'Accounting Dashboard')
@section('page-title', 'Accounting')
@section('page-description', 'Manage your financial records, invoices, and accounting operations.')

@section('content')
<div class="space-y-6" x-data="{ modalOpen: false }">
    <!-- Welcome Section -->
    <div class="relative overflow-hidden rounded-2xl p-8 text-white gradient-bg-primary shadow-2xl">
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Accounting Dashboard</h2>
                    <p class="text-blue-100 text-lg">Manage your financial operations and track your business performance.</p>
                </div>
                <div class="hidden md:flex space-x-4">
                    <a href="{{ route('tenant.accounting.invoices.create', ['tenant' => $tenant->slug]) }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 backdrop-blur-sm border border-white border-opacity-20">
                        Create Invoice
                    </a>
                    <a href="{{ route('tenant.reports.index', ['tenant' => $tenant->slug]) }}" class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg">
                        View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- More Actions Full Screen Modal -->
    <div x-show="modalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-gray-900 bg-opacity-95 overflow-y-auto"
         style="display: none;">

        <div class="min-h-screen p-4 md:p-8">
            <!-- Modal header -->
            <div class="sticky top-0 bg-white bg-opacity-10 backdrop-blur-lg border border-white border-opacity-20 px-6 py-4 rounded-2xl mb-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-white">More Actions</h3>
                    <button @click="modalOpen = false" class="text-white hover:text-gray-200 transition-colors duration-200">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal content -->
            <div class="max-w-7xl mx-auto">
                <!-- Voucher Management Section -->
                <div class="mb-8">
                    <h4 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        Voucher Management
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <!-- Voucher Types Card -->
                        <a href="{{ route('tenant.accounting.voucher-types.index', ['tenant' => $tenant->slug]) }}"
                           class="modal-action-card bg-gradient-to-br from-blue-600 to-blue-800 hover:from-blue-500 hover:to-blue-700 border border-blue-500 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:scale-105 group">
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-blue-500 bg-opacity-30 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-white group-hover:text-blue-200 transition-colors duration-300 text-lg">Voucher Types</h5>
                                    <p class="text-sm text-blue-200">Manage voucher categories</p>
                                </div>
                            </div>
                            <p class="text-sm text-blue-200">Configure and organize your voucher types for better accounting management.</p>
                        </a>

                        <!-- Create Voucher Card -->
                        <a href="{{ route('tenant.accounting.vouchers.create', ['tenant' => $tenant->slug]) }}"
                           class="modal-action-card bg-gradient-to-br from-green-600 to-green-800 hover:from-green-500 hover:to-green-700 border border-green-500 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:scale-105 group">
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-green-500 bg-opacity-30 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-white group-hover:text-green-200 transition-colors duration-300 text-lg">Create Voucher</h5>
                                    <p class="text-sm text-green-200">Add new accounting voucher</p>
                                </div>
                            </div>
                            <p class="text-sm text-green-200">Create new vouchers for recording financial transactions and entries.</p>
                        </a>

                        <!-- View Vouchers Card -->
                        <a href="{{ route('tenant.accounting.vouchers.index', ['tenant' => $tenant->slug]) }}"
                           class="modal-action-card bg-gradient-to-br from-purple-600 to-purple-800 hover:from-purple-500 hover:to-purple-700 border border-purple-500 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:scale-105 group">
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-purple-500 bg-opacity-30 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-white group-hover:text-purple-200 transition-colors duration-300 text-lg">View Vouchers</h5>
                                    <p class="text-sm text-purple-200">Browse all vouchers</p>
                                </div>
                            </div>
                            <p class="text-sm text-purple-200">View and manage all your existing vouchers and transaction records.</p>
                        </a>

                        <!-- Journal Entries Card -->
                        <a href="#"
                           class="modal-action-card bg-gradient-to-br from-pink-600 to-pink-800 hover:from-pink-500 hover:to-pink-700 border border-pink-500 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:scale-105 group">
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-pink-500 bg-opacity-30 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-white group-hover:text-pink-200 transition-colors duration-300 text-lg">Journal Entries</h5>
                                    <p class="text-sm text-pink-200">Manage accounting entries</p>
                                </div>
                            </div>
                            <p class="text-sm text-pink-200">Create and view journal entries for accurate financial record keeping.</p>
                        </a>
                    </div>
                </div>

                <!-- Financial Reports Section -->
                <div class="mb-8">
                    <h4 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        Financial Reports
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Profit & Loss Card -->
                        <a href="{{ route('tenant.reports.profit-loss', ['tenant' => $tenant->slug]) }}"
                           class="modal-action-card bg-gradient-to-br from-orange-600 to-orange-800 hover:from-orange-500 hover:to-orange-700 border border-orange-500 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:scale-105 group">
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-orange-500 bg-opacity-30 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-white group-hover:text-orange-200 transition-colors duration-300 text-lg">Profit & Loss</h5>
                                    <p class="text-sm text-orange-200">Income statement</p>
                                </div>
                            </div>
                            <p class="text-sm text-orange-200">View comprehensive income statement and profit analysis.</p>
                        </a>

                        <!-- Balance Sheet Card -->
                        <a href="{{ route('tenant.reports.balance-sheet', ['tenant' => $tenant->slug]) }}"
                           class="modal-action-card bg-gradient-to-br from-teal-600 to-teal-800 hover:from-teal-500 hover:to-teal-700 border border-teal-500 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:scale-105 group">
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-teal-500 bg-opacity-30 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-white group-hover:text-teal-200 transition-colors duration-300 text-lg">Balance Sheet</h5>
                                    <p class="text-sm text-teal-200">Financial position</p>
                                </div>
                            </div>
                            <p class="text-sm text-teal-200">Review your company's financial position and assets.</p>
                        </a>

                        <!-- Trial Balance Card -->
                        <a href="{{ route('tenant.reports.trial-balance', ['tenant' => $tenant->slug]) }}"
                           class="modal-action-card bg-gradient-to-br from-red-600 to-red-800 hover:from-red-500 hover:to-red-700 border border-red-500 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:scale-105 group">
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-red-500 bg-opacity-30 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-white group-hover:text-red-200 transition-colors duration-300 text-lg">Trial Balance</h5>
                                    <p class="text-sm text-red-200">Account balances</p>
                                </div>
                            </div>
                            <p class="text-sm text-red-200">Summary of all account balances and ledger entries.</p>
                        </a>

                        <!-- Cash Flow Card -->
                        <a href="{{ route('tenant.reports.cash-flow', ['tenant' => $tenant->slug]) }}"
                           class="modal-action-card bg-gradient-to-br from-indigo-600 to-indigo-800 hover:from-indigo-500 hover:to-indigo-700 border border-indigo-500 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:scale-105 group">
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-indigo-500 bg-opacity-30 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-white group-hover:text-indigo-200 transition-colors duration-300 text-lg">Cash Flow</h5>
                                    <p class="text-sm text-indigo-200">Cash movement</p>
                                </div>
                            </div>
                            <p class="text-sm text-indigo-200">Analyze cash inflows and outflows over time.</p>
                        </a>
                    </div>
                </div>

                <!-- Accounting Setup Section -->
                <div class="mb-8">
                    <h4 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <div class="w-10 h-10 bg-cyan-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        Accounting Setup
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Chart of Accounts Card -->
                        <a href="{{ route('tenant.accounting.chart-of-accounts.index', ['tenant' => $tenant->slug]) }}"
                           class="modal-action-card bg-gradient-to-br from-cyan-600 to-cyan-800 hover:from-cyan-500 hover:to-cyan-700 border border-cyan-500 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:scale-105 group">
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-cyan-500 bg-opacity-30 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-white group-hover:text-cyan-200 transition-colors duration-300 text-lg">Chart of Accounts</h5>
                                    <p class="text-sm text-cyan-200">Account structure</p>
                                </div>
                            </div>
                            <p class="text-sm text-cyan-200">Set up and manage your accounting system's chart of accounts.</p>
                        </a>

                        <!-- Fiscal Year Card -->
                        <a href="#"
                           class="modal-action-card bg-gradient-to-br from-yellow-600 to-yellow-800 hover:from-yellow-500 hover:to-yellow-700 border border-yellow-500 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:scale-105 group">
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-yellow-500 bg-opacity-30 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-white group-hover:text-yellow-200 transition-colors duration-300 text-lg">Fiscal Years</h5>
                                    <p class="text-sm text-yellow-200">Accounting periods</p>
                                </div>
                            </div>
                            <p class="text-sm text-yellow-200">Manage your company's fiscal years and accounting periods.</p>
                        </a>

                        <!-- Tax Settings Card -->
                        <a href="#"
                           class="modal-action-card bg-gradient-to-br from-lime-600 to-lime-800 hover:from-lime-500 hover:to-lime-700 border border-lime-500 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:scale-105 group">
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-lime-500 bg-opacity-30 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-white group-hover:text-lime-200 transition-colors duration-300 text-lg">Tax Settings</h5>
                                    <p class="text-sm text-lime-200">Configure tax rates</p>
                                </div>
                            </div>
                            <p class="text-sm text-lime-200">Set up tax rates and manage tax configurations for your business.</p>
                        </a>
                    </div>
                </div>

                <!-- Transactions Section -->
                <div class="mb-8">
                    <h4 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        Transactions & Payments
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <!-- Invoices Card -->
                        <a href="{{ route('tenant.accounting.invoices.index', ['tenant' => $tenant->slug]) }}"
                           class="modal-action-card bg-gradient-to-br from-emerald-600 to-emerald-800 hover:from-emerald-500 hover:to-emerald-700 border border-emerald-500 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:scale-105 group">
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-emerald-500 bg-opacity-30 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-white group-hover:text-emerald-200 transition-colors duration-300 text-lg">Invoices</h5>
                                    <p class="text-sm text-emerald-200">Manage all invoices</p>
                                </div>
                            </div>
                            <p class="text-sm text-emerald-200">Create, view, and manage all customer invoices in one place.</p>
                        </a>

                        <!-- Expenses Card -->
                        <a href="{{ route('tenant.accounting.expenses.index', ['tenant' => $tenant->slug]) }}"
                           class="modal-action-card bg-gradient-to-br from-red-600 to-red-800 hover:from-red-500 hover:to-red-700 border border-red-500 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:scale-105 group">
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-red-500 bg-opacity-30 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-white group-hover:text-red-200 transition-colors duration-300 text-lg">Expenses</h5>
                                    <p class="text-sm text-red-200">Track business expenses</p>
                                </div>
                            </div>
                            <p class="text-sm text-red-200">Record and categorize all your business expenses and costs.</p>
                        </a>

                        <!-- Payments Card -->
                        <a href="{{ route('tenant.accounting.payments.index', ['tenant' => $tenant->slug]) }}"
                           class="modal-action-card bg-gradient-to-br from-violet-600 to-violet-800 hover:from-violet-500 hover:to-violet-700 border border-violet-500 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:scale-105 group">
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-violet-500 bg-opacity-30 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-white group-hover:text-violet-200 transition-colors duration-300 text-lg">Payments</h5>
                                    <p class="text-sm text-violet-200">Manage receipts</p>
                                </div>
                            </div>
                            <p class="text-sm text-violet-200">Record and track all payments received from customers.</p>
                        </a>

                        <!-- Bills Card -->
                        <a href="#"
                           class="modal-action-card bg-gradient-to-br from-amber-600 to-amber-800 hover:from-amber-500 hover:to-amber-700 border border-amber-500 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:scale-105 group">
                            <div class="flex items-center mb-4">
                                <div class="w-14 h-14 bg-amber-500 bg-opacity-30 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-white group-hover:text-amber-200 transition-colors duration-300 text-lg">Bills</h5>
                                    <p class="text-sm text-amber-200">Track payables</p>
                                </div>
                            </div>
                            <p class="text-sm text-amber-200">Manage bills and monitor accounts payable for your business.</p>
                        </a>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="fixed bottom-0 left-0 right-0 bg-gray-900 bg-opacity-90 backdrop-blur-lg border-t border-white border-opacity-10 px-6 py-4">
                    <div class="flex justify-end max-w-7xl mx-auto">
                        <button @click="modalOpen = false" class="px-8 py-3 bg-white text-gray-900 rounded-xl font-medium transition-colors duration-200 hover:bg-gray-200 transform hover:scale-105 transition-transform">
                            Close Dashboard
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl p-6 shadow-lg">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('tenant.accounting.invoices.create', ['tenant' => $tenant->slug]) }}" class="quick-action-btn bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-xl text-center shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Create Invoice</span>
            </a>

            <a href="{{ route('tenant.accounting.invoices.index', ['tenant' => $tenant->slug]) }}" class="quick-action-btn bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-xl text-center shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">View Invoices</span>
            </a>

            <a href="{{ route('tenant.accounting.expenses.create', ['tenant' => $tenant->slug]) }}" class="quick-action-btn bg-gradient-to-br from-red-500 to-red-600 text-white p-4 rounded-xl text-center shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Add Expense</span>
            </a>

            <a href="{{ route('tenant.accounting.payments.create', ['tenant' => $tenant->slug]) }}" class="quick-action-btn bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-xl text-center shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Record Payment</span>
            </a>

            <a href="{{ route('tenant.accounting.chart-of-accounts.index', ['tenant' => $tenant->slug]) }}" class="quick-action-btn bg-gradient-to-br from-teal-500 to-teal-600 text-white p-4 rounded-xl text-center shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">Chart of Accounts</span>
            </a>

            <!-- More Actions Button -->
            <button @click="modalOpen = true" class="quick-action-btn bg-gradient-to-br from-indigo-500 to-indigo-600 text-white p-4 rounded-xl text-center shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 w-full">
                <div class="w-8 h-8 mx-auto mb-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium">More Actions</span>
                <svg class="w-4 h-4 mx-auto mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Financial Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">₦{{ number_format($totalRevenue ?? 0, 2) }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            +12.5% from last month
                        </span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Expenses</p>
                    <p class="text-2xl font-bold text-gray-900">₦{{ number_format($totalExpenses ?? 0, 2) }}</p>
                    <p class="text-sm text-red-600 mt-1">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                            +8.2% from last month
                        </span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Outstanding Invoices</p>
                    <p class="text-2xl font-bold text-gray-900">₦{{ number_format($outstandingInvoices ?? 0, 2) }}</p>
                    <p class="text-sm text-orange-600 mt-1">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $pendingInvoicesCount ?? 0 }} pending
                        </span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-teal-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Net Profit</p>
                    <p class="text-2xl font-bold text-gray-900">₦{{ number_format(($totalRevenue ?? 0) - ($totalExpenses ?? 0), 2) }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            +15.3% from last month
                        </span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Quick Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Transactions -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Recent Transactions</h3>
                <a href="{{ route('tenant.accounting.vouchers.index', ['tenant' => $tenant->slug]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-4">
                @forelse($recentTransactions ?? [] as $transaction)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-{{ $transaction->type === 'income' ? 'green' : 'red' }}-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-{{ $transaction->type === 'income' ? 'green' : 'red' }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($transaction->type === 'income')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    @endif
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $transaction->description }}</p>
                                <p class="text-sm text-gray-500">{{ $transaction->date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-{{ $transaction->type === 'income' ? 'green' : 'red' }}-600">
                                {{ $transaction->type === 'income' ? '+' : '-' }}₦{{ number_format($transaction->amount, 2) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500">No recent transactions</p>
                        <a href="{{ route('tenant.accounting.vouchers.create', ['tenant' => $tenant->slug]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 inline-block">Create your first voucher</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Voucher Summary -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Voucher Summary</h3>
                <a href="{{ route('tenant.accounting.voucher-types.index', ['tenant' => $tenant->slug]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Manage Types</a>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">voucher type</p>
                            <p class="text-sm text-gray-500">54 vouchers</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-900">₦5,422</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Chart -->
    <div class="bg-white rounded-2xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Monthly Financial Overview</h3>
            <div class="flex space-x-2">
                <button class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">6M</button>
                <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200">1Y</button>
            </div>
        </div>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-xl">
            <div class="text-center">
                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <p class="text-gray-500 mb-2">Financial Chart</p>
                <p class="text-sm text-gray-400">Chart integration coming soon</p>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<style>
.quick-action-btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.quick-action-btn:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.quick-action-btn:active {
    transform: translateY(0) scale(1.02);
}

/* Modal Action Cards Styles */
.modal-action-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.modal-action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.8s;
}

.modal-action-card:hover::before {
    left: 100%;
}

.modal-action-card:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

.modal-action-card:active {
    transform: translateY(-2px) scale(1.01);
}

/* Custom scrollbar for modal */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}

/* Animation for dropdown items */
.dropdown-item {
    transition: all 0.2s ease-in-out;
}

.dropdown-item:hover {
    transform: translateX(4px);
}

/* Modal backdrop blur effect */
.modal-backdrop {
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

/* Pulse animation for modal cards */
@keyframes pulse-subtle {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

.modal-action-card:hover .w-14 {
    animation: pulse-subtle 2s infinite;
}

/* Gradient animations */
.gradient-bg-primary {
    background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #f5576c);
    background-size: 400% 400%;
    animation: gradient-shift 15s ease infinite;
}

@keyframes gradient-shift {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

/* Hover effects for transaction items */
.transaction-item {
    transition: all 0.3s ease;
}

.transaction-item:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Loading skeleton animation */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* Modal entrance animation */
@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-content {
    animation: modalSlideIn 0.3s ease-out;
}

/* Card hover glow effect */
.modal-action-card:hover {
    box-shadow: 0 0 30px rgba(255, 255, 255, 0.2);
}

/* Responsive modal adjustments */
@media (max-width: 768px) {
    .modal-action-card {
        padding: 1rem;
    }

    .modal-action-card .w-14 {
        width: 2.5rem;
        height: 2.5rem;
    }

    .modal-action-card .w-7 {
        width: 1.25rem;
        height: 1.25rem;
    }
}

/* Ripple effect */
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth hover effects to cards
    const cards = document.querySelectorAll('.bg-white.rounded-2xl');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';
        });
    });

    // Add click animation to quick action buttons
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

    // Add click animation to modal action cards
    const modalActionCards = document.querySelectorAll('.modal-action-card');
    modalActionCards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Add click feedback
            this.style.transform = 'translateY(-2px) scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Alt + I for Create Invoice
        if (e.altKey && e.key === 'i') {
            e.preventDefault();
            window.location.href = "{{ route('tenant.accounting.invoices.create', ['tenant' => $tenant->slug]) }}";
        }

        // Alt + V for Create Voucher
        if (e.altKey && e.key === 'v') {
            e.preventDefault();
            window.location.href = "{{ route('tenant.accounting.vouchers.create', ['tenant' => $tenant->slug]) }}";
        }

        // Alt + R for Reports
        if (e.altKey && e.key === 'r') {
            e.preventDefault();
            window.location.href = "{{ route('tenant.reports.index', ['tenant' => $tenant->slug]) }}";
        }

        // Alt + M for More Actions Modal
        if (e.altKey && e.key === 'm') {
            e.preventDefault();
            // Trigger the Alpine.js modal
            const moreActionsBtn = document.querySelector('[x-data] button');
            if (moreActionsBtn) {
                moreActionsBtn.click();
            }
        }

        // Escape to close modal
        if (e.key === 'Escape') {
            // This will be handled by Alpine.js automatically
        }
    });

    // Auto-refresh data every 5 minutes
    setInterval(function() {
        // You can implement AJAX refresh here if needed
        console.log('Auto-refresh triggered');
    }, 300000); // 5 minutes
});
</script>
@endpush
@endsection
