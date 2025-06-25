<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Ballie') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg fixed h-full z-30" id="sidebar">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 px-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-white font-bold text-lg">B</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Ballie</span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 overflow-y-auto">
                    <div class="space-y-2">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                            </svg>
                            Dashboard
                        </a>

                        <!-- Accounting -->
                        <div class="nav-group">
                            <button class="nav-group-toggle {{ request()->routeIs('accounting*') ? 'active' : '' }}" onclick="toggleNavGroup(this)">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    Accounting
                                </div>
                                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="nav-group-content {{ request()->routeIs('accounting*') ? 'show' : '' }}">
                                <a href="{{ route('accounting.index', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('accounting.index') ? 'active' : '' }}">Overview</a>
                                <a href="{{ route('accounting.chart-of-accounts', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('accounting.chart-of-accounts') ? 'active' : '' }}">Chart of Accounts</a>
                                <a href="{{ route('accounting.journal-entries', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('accounting.journal-entries') ? 'active' : '' }}">Journal Entries</a>
                                <a href="{{ route('accounting.financial-statements', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('accounting.financial-statements') ? 'active' : '' }}">Financial Statements</a>
                            </div>
                        </div>

                        <!-- Invoicing -->
                        <div class="nav-group">
                            <button class="nav-group-toggle {{ request()->routeIs('invoices*') ? 'active' : '' }}" onclick="toggleNavGroup(this)">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Invoicing
                                </div>
                                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="nav-group-content {{ request()->routeIs('invoices*') ? 'show' : '' }}">
                                <a href="{{ route('invoices.index', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('invoices.index') ? 'active' : '' }}">All Invoices</a>
                                <a href="{{ route('invoices.create', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('invoices.create') ? 'active' : '' }}">Create Invoice</a>
                            </div>
                        </div>

                        <!-- Inventory -->
                        <div class="nav-group">
                            <button class="nav-group-toggle {{ request()->routeIs('inventory*') ? 'active' : '' }}" onclick="toggleNavGroup(this)">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Inventory
                                </div>
                                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="nav-group-content {{ request()->routeIs('inventory*') ? 'show' : '' }}">
                                <a href="{{ route('inventory.index', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('inventory.index') ? 'active' : '' }}">Overview</a>
                                <a href="{{ route('inventory.products', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('inventory.products') ? 'active' : '' }}">Products</a>
                                <a href="{{ route('inventory.categories', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('inventory.categories') ? 'active' : '' }}">Categories</a>
                                <a href="{{ route('inventory.suppliers', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('inventory.suppliers') ? 'active' : '' }}">Suppliers</a>
                            </div>
                        </div>

                        <!-- CRM -->
                        <div class="nav-group">
                            <button class="nav-group-toggle {{ request()->routeIs('crm*') ? 'active' : '' }}" onclick="toggleNavGroup(this)">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    CRM
                                </div>
                                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="nav-group-content {{ request()->routeIs('crm*') ? 'show' : '' }}">
                                <a href="{{ route('crm.index', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('crm.index') ? 'active' : '' }}">Overview</a>
                                <a href="{{ route('crm.customers', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('crm.customers') ? 'active' : '' }}">Customers</a>
                                <a href="{{ route('crm.leads', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('crm.leads') ? 'active' : '' }}">Leads</a>
                                <a href="{{ route('crm.sales-pipeline', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('crm.sales-pipeline') ? 'active' : '' }}">Sales Pipeline</a>
                            </div>
                        </div>

                        <!-- POS -->
                        <div class="nav-group">
                            <button class="nav-group-toggle {{ request()->routeIs('pos*') ? 'active' : '' }}" onclick="toggleNavGroup(this)">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    Point of Sale
                                </div>
                                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="nav-group-content {{ request()->routeIs('pos*') ? 'show' : '' }}">
                                <a href="{{ route('pos.index', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('pos.index') ? 'active' : '' }}">POS Terminal</a>
                                <a href="{{ route('pos.sales', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('pos.sales') ? 'active' : '' }}">Sales History</a>
                                <a href="{{ route('pos.receipts', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('pos.receipts') ? 'active' : '' }}">Receipts</a>
                            </div>
                        </div>

                        <!-- Payroll -->
                        <div class="nav-group">
                            <button class="nav-group-toggle {{ request()->routeIs('payroll*') ? 'active' : '' }}" onclick="toggleNavGroup(this)">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                    </svg>
                                    Payroll
                                </div>
                                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="nav-group-content {{ request()->routeIs('payroll*') ? 'show' : '' }}">
                                <a href="{{ route('payroll.index', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('payroll.index') ? 'active' : '' }}">Overview</a>
                                <a href="{{ route('payroll.employees', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('payroll.employees') ? 'active' : '' }}">Employees</a>
                                <a href="{{ route('payroll.payslips', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('payroll.payslips') ? 'active' : '' }}">Payslips</a>
                                <a href="{{ route('payroll.tax-reports', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('payroll.tax-reports') ? 'active' : '' }}">Tax Reports</a>
                            </div>
                        </div>

                        <!-- Reports -->
                        <div class="nav-group">
                            <button class="nav-group-toggle {{ request()->routeIs('reports*') ? 'active' : '' }}" onclick="toggleNavGroup(this)">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Reports
                                </div>
                                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="nav-group-content {{ request()->routeIs('reports*') ? 'show' : '' }}">
                                <a href="{{ route('reports.index', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('reports.index') ? 'active' : '' }}">Overview</a>
                                <a href="{{ route('reports.financial', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('reports.financial') ? 'active' : '' }}">Financial</a>
                                <a href="{{ route('reports.sales', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('reports.sales') ? 'active' : '' }}">Sales</a>
                                <a href="{{ route('reports.inventory', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('reports.inventory') ? 'active' : '' }}">Inventory</a>
                            </div>
                        </div>

                        <!-- Settings -->
                        <div class="nav-group">
                            <button class="nav-group-toggle {{ request()->routeIs('settings*') ? 'active' : '' }}" onclick="toggleNavGroup(this)">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Settings
                                </div>
                                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="nav-group-content {{ request()->routeIs('settings*') ? 'show' : '' }}">
                                <a href="{{ route('settings.index', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('settings.index') ? 'active' : '' }}">General</a>
                                <a href="{{ route('settings.company', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('settings.company') ? 'active' : '' }}">Company</a>
                                <a href="{{ route('settings.users', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('settings.users') ? 'active' : '' }}">Users</a>
                                <a href="{{ route('settings.integrations', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('settings.integrations') ? 'active' : '' }}">Integrations</a>
                                <a href="{{ route('settings.billing', ['tenant' => $tenant->slug]) }}" class="nav-sublink {{ request()->routeIs('settings.billing') ? 'active' : '' }}">Billing</a>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- User Profile -->
                <div class="px-4 py-4 border-t border-gray-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-700">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->role ?? 'Owner' }}</p>
                        </div>
                        <div class="relative">
                            <button class="text-gray-400 hover:text-gray-600" onclick="toggleUserMenu()">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                            <div id="userMenu" class="hidden absolute bottom-full right-0 mb-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Account Settings</a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button class="lg:hidden text-gray-500 hover:text-gray-700 mr-4" onclick="toggleSidebar()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-sm text-gray-500">@yield('page-description', 'Welcome back! Here\'s what\'s happening with your business today.')</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" placeholder="Search..." class="block w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                        </div>

                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-2 text-gray-400 hover:text-gray-600 relative" onclick="toggleNotifications()">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a6 6 0 10-12 0v3l-5 5h5m7 0v1a3 3 0 01-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
                            </button>
                            <div id="notificationsMenu" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3"></div>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-900">New order received</p>
                                                <p class="text-xs text-gray-500 mt-1">Order #INV-2024-001 for ₦85,500</p>
                                                <p class="text-xs text-gray-400 mt-1">2 minutes ago</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-900">Payment received</p>
                                                <p class="text-xs text-gray-500 mt-1">₦125,000 from Adebayo Enterprises</p>
                                                <p class="text-xs text-gray-400 mt-1">15 minutes ago</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="w-2 h-2 bg-orange-500 rounded-full mt-2 mr-3"></div>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-900">Low stock alert</p>
                                                <p class="text-xs text-gray-500 mt-1">5 products are running low</p>
                                                <p class="text-xs text-gray-400 mt-1">1 hour ago</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="px-4 py-2 border-t border-gray-100">
                                    <a href="#" class="text-sm text-primary-600 hover:text-primary-700">View all notifications</a>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="relative">
                            <button class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors" onclick="toggleQuickActions()">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                            <div id="quickActionsMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('invoices.create', ['tenant' => $tenant->slug]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Create Invoice</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Add Product</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">New Customer</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Record Payment</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden" onclick="toggleSidebar()"></div>

    <!-- Custom Styles -->
    <style>
        .nav-link {
            @apply flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200;
        }

        .nav-link.active {
            @apply bg-primary-50 text-primary-700 border-r-2 border-primary-600;
        }

        .nav-link svg {
            @apply mr-3;
        }

        .nav-group-toggle {
            @apply w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200;
        }

        .nav-group-toggle.active {
            @apply bg-primary-50 text-primary-700;
        }

        .nav-group-toggle svg:last-child {
            @apply transform transition-transform duration-200;
        }

        .nav-group-toggle.active svg:last-child {
            @apply rotate-180;
        }

        .nav-group-content {
            @apply ml-6 mt-1 space-y-1 max-h-0 overflow-hidden transition-all duration-200;
        }

        .nav-group-content.show {
            @apply max-h-96;
        }

        .nav-sublink {
            @apply block px-3 py-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 hover:text-gray-700 transition-colors duration-200;
        }

        .nav-sublink.active {
            @apply bg-primary-50 text-primary-600;
        }

        /* Mobile responsive */
        @media (max-width: 1024px) {
            #sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            #sidebar.show {
                transform: translateX(0);
            }

            .ml-64 {
                margin-left: 0;
            }
        }
    </style>

    <!-- JavaScript -->
    <script>
        function toggleNavGroup(button) {
            const content = button.nextElementSibling;
            const isActive = button.classList.contains('active');

            // Close all other nav groups
            document.querySelectorAll('.nav-group-toggle').forEach(toggle => {
                if (toggle !== button) {
                    toggle.classList.remove('active');
                    toggle.nextElementSibling.classList.remove('show');
                }
            });

            // Toggle current nav group
            if (isActive) {
                button.classList.remove('active');
                content.classList.remove('show');
            } else {
                button.classList.add('active');
                content.classList.add('show');
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('show');
            overlay.classList.toggle('hidden');
        }

        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
        }

        function toggleNotifications() {
            const menu = document.getElementById('notificationsMenu');
            menu.classList.toggle('hidden');
        }

        function toggleQuickActions() {
            const menu = document.getElementById('quickActionsMenu');
            menu.classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('userMenu');
            const notificationsMenu = document.getElementById('notificationsMenu');
            const quickActionsMenu = document.getElementById('quickActionsMenu');

            if (!event.target.closest('.relative')) {
                userMenu.classList.add('hidden');
                notificationsMenu.classList.add('hidden');
                quickActionsMenu.classList.add('hidden');
            }
        });

        // Initialize navigation state on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-expand active nav groups
            document.querySelectorAll('.nav-group-toggle.active').forEach(toggle => {
                toggle.nextElementSibling.classList.add('show');
            });
        });
    </script>
</body>
</html>