<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $tenant->name . ' - Ballie')</title>

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
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Impersonation Banner -->
        @if(isset($impersonating))
        <div class="bg-yellow-500 text-yellow-900 px-4 py-2 text-center text-sm">
            <strong>Impersonating:</strong> {{ $impersonating['user']->name }} ({{ $impersonating['user']->email }})
            <form method="POST" action="{{ route('super-admin.stop-impersonation') }}" class="inline ml-4">
                @csrf
                <button type="submit" class="underline hover:no-underline">Stop Impersonation</button>
            </form>
        </div>
        @endif

        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo & Tenant Name -->
                    <div class="flex items-center">
                        <a href="{{ route('tenant.dashboard', $tenant->slug) }}" class="flex items-center">
                            @if($tenant->logo)
                                <img src="{{ Storage::url($tenant->logo) }}" alt="{{ $tenant->name }}" class="w-8 h-8 rounded mr-3">
                            @else
                                <div class="w-8 h-8 bg-primary-600 rounded flex items-center justify-center mr-3">
                                    <span class="text-white font-bold text-sm">{{ substr($tenant->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <span class="text-lg font-semibold text-gray-900">{{ $tenant->name }}</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('tenant.dashboard', $tenant->slug) }}"
                           class="text-gray-600 hover:text-gray-900 font-medium transition-colors {{ request()->routeIs('tenant.dashboard') ? 'text-primary-600' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('tenant.customers.index', $tenant->slug) }}"
                           class="text-gray-600 hover:text-gray-900 font-medium transition-colors {{ request()->routeIs('tenant.customers.*') ? 'text-primary-600' : '' }}">
                            Customers
                        </a>
                        <a href="{{ route('tenant.products.index', $tenant->slug) }}"
                           class="text-gray-600 hover:text-gray-900 font-medium transition-colors {{ request()->routeIs('tenant.products.*') ? 'text-primary-600' : '' }}">
                            Products
                        </a>
                        <a href="{{ route('tenant.invoices.index', $tenant->slug) }}"
                           class="text-gray-600 hover:text-gray-900 font-medium transition-colors {{ request()->routeIs('tenant.invoices.*') ? 'text-primary-600' : '' }}">
                            Invoices
                        </a>
                        <a href="{{ route('tenant.reports.index', $tenant->slug) }}"
                           class="text-gray-600 hover:text-gray-900 font-medium transition-colors {{ request()->routeIs('tenant.reports.*') ? 'text-primary-600' : '' }}">
                            Reports
                        </a>

                        <!-- User Dropdown -->
                        <div class="relative">
                            <button class="flex items-center text-gray-600 hover:text-gray-900" onclick="toggleUserMenu()">
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-2">
                                    <span class="text-gray-600 font-medium text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span class="font-medium">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('tenant.profile.index', $tenant->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                @if(in_array(auth()->user()->role, ['owner', 'admin']))
                                    <a href="{{ route('tenant.settings.index', $tenant->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                    <a href="{{ route('tenant.users.index', $tenant->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Users</a>
                                @endif
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('tenant.logout', $tenant->slug) }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button class="text-gray-600 hover:text-gray-900" onclick="toggleMobileMenu()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <div id="mobileMenu" class="hidden md:hidden pb-4">
                    <div class="space-y-2">
                        <a href="{{ route('tenant.dashboard', $tenant->slug) }}" class="block text-gray-600 hover:text-gray-900 font-medium py-2">Dashboard</a>
                        <a href="{{ route('tenant.customers.index', $tenant->slug) }}" class="block text-gray-600 hover:text-gray-900 font-medium py-2">Customers</a>
                        <a href="{{ route('tenant.products.index', $tenant->slug) }}" class="block text-gray-600 hover:text-gray-900 font-medium py-2">Products</a>
                        <a href="{{ route('tenant.invoices.index', $tenant->slug) }}" class="block text-gray-600 hover:text-gray-900 font-medium py-2">Invoices</a>
                        <a href="{{ route('tenant.reports.index', $tenant->slug) }}" class="block text-gray-600 hover:text-gray-900 font-medium py-2">Reports</a>

                        <div class="border-t border-gray-200 pt-2">
                            <a href="{{ route('tenant.profile.index', $tenant->slug) }}" class="block text-gray-600 hover:text-gray-900 font-medium py-2">Profile</a>
                            @if(in_array(auth()->user()->role, ['owner', 'admin']))
                                <a href="{{ route('tenant.settings.index', $tenant->slug) }}" class="block text-gray-600 hover:text-gray-900 font-medium py-2">Settings</a>
                                <a href="{{ route('tenant.users.index', $tenant->slug) }}" class="block text-gray-600 hover:text-gray-900 font-medium py-2">Users</a>
                            @endif
                            <form method="POST" action="{{ route('tenant.logout', $tenant->slug) }}">
                                @csrf
                                <button type="submit" class="block w-full text-left text-gray-600 hover:text-gray-900 font-medium py-2">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Trial Banner -->
        @if($tenant->subscription_status === 'trial' && $tenantHelper::getTrialDaysRemaining() > 0)
        <div class="bg-blue-50 border-b border-blue-200 px-4 py-3">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-blue-800 text-sm">
                        <strong>Trial Period:</strong> {{ $tenantHelper::getTrialDaysRemaining() }} days remaining
                    </span>
                </div>
                <a href="{{ route('tenant.settings.billing', $tenant->slug) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors">
                    Upgrade Now
                </a>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-green-800">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-red-800">{{ session('error') }}</span>
                </div>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('userMenu');
            const userButton = event.target.closest('[onclick="toggleUserMenu()"]');

            if (!userButton && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
