<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | {{ $tenant->name ?? 'Ballie' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --color-gold: #d1b05e;
            --color-blue: #2b6399;
            --color-dark-purple: #3c2c64;
            --color-teal: #69a2a4;
            --color-purple: #85729d;
            --color-light-blue: #7b87b8;
            --color-deep-purple: #4a3570;
            --color-lavender: #a48cb4;
            --color-violet: #614c80;
            --color-green: #249484;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .sidebar {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(180deg, var(--color-dark-purple) 0%, var(--color-deep-purple) 100%);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .sidebar-collapsed {
            width: 5rem;
        }

        .sidebar-expanded {
            width: 17rem;
        }

        .content-area {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: 17rem;
            width: calc(100% - 17rem);
        }

        .content-area.collapsed {
            margin-left: 5rem;
            width: calc(100% - 5rem);
        }

        .menu-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .menu-item:hover::before {
            left: 100%;
        }

        .menu-item:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.05) 100%);
            transform: translateX(4px);
        }

        .menu-item.active {
            background: linear-gradient(135deg, rgba(209, 176, 94, 0.2) 0%, rgba(209, 176, 94, 0.1) 100%);
            border-left: 4px solid var(--color-gold);
            box-shadow: 0 4px 12px rgba(209, 176, 94, 0.3);
        }

        .menu-title {
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .submenu-container {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .submenu-container.open {
            max-height: 1000px;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .gradient-bg {
            background: linear-gradient(135deg, var(--color-blue) 0%, var(--color-deep-purple) 100%);
        }

        .pulse-animation {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .8;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 50;
                height: 100vh;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .content-area {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Footer styles */
        .main-footer {
            background: linear-gradient(135deg, var(--color-dark-purple) 0%, var(--color-deep-purple) 100%);
            color: white;
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar sidebar-expanded text-white h-screen fixed shadow-2xl z-30 transform md:transform-none transition-all duration-300">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-20 px-6 border-b border-white border-opacity-10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-lg">B</span>
                    </div>
                    <div class="sidebar-title overflow-hidden whitespace-nowrap transition-opacity">
                        <span class="text-xl font-bold bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent">
                            {{ $tenant->name ?? 'Ballie' }}
                        </span>
                        <div class="text-xs text-gray-300 mt-1">Business Suite</div>
                    </div>
                </div>
                <button id="sidebarCollapseBtn" class="p-2 rounded-lg hover:bg-white hover:bg-opacity-10 md:block hidden transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
                <button id="mobileSidebarClose" class="p-2 rounded-lg hover:bg-white hover:bg-opacity-10 md:hidden block transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Sidebar Menu -->
            <nav class="py-6 overflow-y-auto h-[calc(100vh-10rem)] custom-scrollbar">
                <ul class="space-y-2 px-4">
                    <!-- Dashboard -->
                    <li>
                        <a href="#dashboard" class="menu-item flex items-center px-4 py-3 rounded-xl active group">
                            <div class="flex-shrink-0 w-6 h-6 mr-4 text-yellow-400 group-hover:scale-110 transition-transform duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <span class="menu-title whitespace-nowrap font-medium">Dashboard</span>
                        </a>
                    </li>

                    <!-- Masters -->
                    <li>
                        <button class="menu-item w-full flex items-center justify-between px-4 py-3 rounded-xl group" data-submenu="masters">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-6 h-6 mr-4 text-blue-400 group-hover:scale-110 transition-transform duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <span class="menu-title whitespace-nowrap font-medium">Masters</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="submenu-arrow h-4 w-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="submenu-container pl-12 pr-4 mt-2" id="masters-submenu">
                            <ul class="space-y-1">
                                <li>
                                    <a href="#account-info" class="block py-2 px-4 rounded-lg hover:bg-white hover:bg-opacity-10 text-sm text-gray-300 hover:text-white transition-all duration-200">
                                        Account Info
                                    </a>
                                </li>
                                <li>
                                    <a href="#payroll-info" class="block py-2 px-4 rounded-lg hover:bg-white hover:bg-opacity-10 text-sm text-gray-300 hover:text-white transition-all duration-200">
                                        Payroll Info
                                    </a>
                                </li>
                                <li>
                                    <a href="#inventory" class="block py-2 px-4 rounded-lg hover:bg-white hover:bg-opacity-10 text-sm text-gray-300 hover:text-white transition-all duration-200">
                                        Inventory
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Transactions -->
                    <li>
                        <button class="menu-item w-full flex items-center justify-between px-4 py-3 rounded-xl group" data-submenu="transactions">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-6 h-6 mr-4 text-green-400 group-hover:scale-110 transition-transform duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <span class="menu-title whitespace-nowrap font-medium">Transactions</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="submenu-arrow h-4 w-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="submenu-container pl-12 pr-4 mt-2" id="transactions-submenu">
                            <ul class="space-y-1">
                                <li>
                                    <a href="#invoices" class="block py-2 px-4 rounded-lg hover:bg-white hover:bg-opacity-10 text-sm text-gray-300 hover:text-white transition-all duration-200">
                                        Invoices
                                    </a>
                                </li>
                                <li>
                                    <a href="#receipts" class="block py-2 px-4 rounded-lg hover:bg-white hover:bg-opacity-10 text-sm text-gray-300 hover:text-white transition-all duration-200">
                                        Receipts
                                    </a>
                                </li>
                                <li>
                                    <a href="#payments" class="block py-2 px-4 rounded-lg hover:bg-white hover:bg-opacity-10 text-sm text-gray-300 hover:text-white transition-all duration-200">
                                        Payments
                                    </a>
                                </li>
                                <li>
                                    <a href="#expenses" class="block py-2 px-4 rounded-lg hover:bg-white hover:bg-opacity-10 text-sm text-gray-300 hover:text-white transition-all duration-200">
                                        Expenses
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Reports -->
                    <li>
                        <button class="menu-item w-full flex items-center justify-between px-4 py-3 rounded-xl group" data-submenu="reports">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-6 h-6 mr-4 text-purple-400 group-hover:scale-110 transition-transform duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <span class="menu-title whitespace-nowrap font-medium">Reports</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="submenu-arrow h-4 w-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="submenu-container pl-12 pr-4 mt-2" id="reports-submenu">
                            <ul class="space-y-1">
                                <li>
                                    <a href="#financial-reports" class="block py-2 px-4 rounded-lg hover:bg-white hover:bg-opacity-10 text-sm text-gray-300 hover:text-white transition-all duration-200">
                                        Financial Reports
                                    </a>
                                </li>
                                <li>
                                    <a href="#tax-reports" class="block py-2 px-4 rounded-lg hover:bg-white hover:bg-opacity-10 text-sm text-gray-300 hover:text-white transition-all duration-200">
                                        Tax Reports
                                    </a>
                                </li>
                                <li>
                                    <a href="#inventory-reports" class="block py-2 px-4 rounded-lg hover:bg-white hover:bg-opacity-10 text-sm text-gray-300 hover:text-white transition-all duration-200">
                                        Inventory Reports
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Settings -->
                    <li>
                        <a href="#settings" class="menu-item flex items-center px-4 py-3 rounded-xl group">
                            <div class="flex-shrink-0 w-6 h-6 mr-4 text-gray-400 group-hover:scale-110 transition-transform duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="menu-title whitespace-nowrap font-medium">Settings</span>
                        </a>
                    </li>
                </ul>

                <!-- User Profile Section -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white border-opacity-10">
                    <div class="flex items-center space-x-3 p-3 rounded-xl bg-white bg-opacity-10 backdrop-blur-sm">
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="sidebar-title overflow-hidden">
                            <div class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'User' }}</div>
                            <div class="text-xs text-gray-300 truncate">{{ auth()->user()->email ?? 'user@example.com' }}</div>
                        </div>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="content-area flex-1 flex flex-col overflow-hidden ml-0 md:ml-72 transition-all duration-300">
            <!-- Top Navigation -->
            <header class="glass-effect shadow-sm border-b border-gray-200 h-20 flex items-center justify-between px-6 sticky top-0 z-20">
                <div class="flex items-center space-x-4">
                    <button id="mobileSidebarToggle" class="p-2 rounded-lg hover:bg-gray-100 md:hidden transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                            @yield('page-title', 'Dashboard')
                        </h1>
                        <p class="text-sm text-gray-500 mt-1">@yield('page-description', 'Welcome back! Here\'s what\'s happening with your business today.')</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="relative hidden md:block">
                        <input type="text" placeholder="Search..." class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <button class="relative p-2 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 rounded-full flex items-center justify-center text-xs text-white pulse-animation">3</span>
                    </button>

                    <!-- User Menu -->
                    <div class="relative">
                        <button class="flex items-center space-x-3 p-2 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <div class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'User' }}</div>
                                <div class="text-xs text-gray-500">{{ auth()->user()->role ?? 'Admin' }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden hidden"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');
            const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
            const mobileSidebarClose = document.getElementById('mobileSidebarClose');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const contentArea = document.querySelector('.content-area');
            const sidebarTitles = document.querySelectorAll('.sidebar-title');
            const menuTitles = document.querySelectorAll('.menu-title');

            // Desktop sidebar collapse/expand
            if (sidebarCollapseBtn) {
                sidebarCollapseBtn.addEventListener('click', function() {
                    if (sidebar.classList.contains('sidebar-expanded')) {
                        sidebar.classList.remove('sidebar-expanded');
                        sidebar.classList.add('sidebar-collapsed');
                        contentArea.style.marginLeft = '5rem';

                        // Hide titles
                        sidebarTitles.forEach(title => title.style.opacity = '0');
                        menuTitles.forEach(title => title.style.opacity = '0');

                        // Rotate collapse button
                        sidebarCollapseBtn.style.transform = 'rotate(180deg)';
                    } else {
                        sidebar.classList.remove('sidebar-collapsed');
                        sidebar.classList.add('sidebar-expanded');
                        contentArea.style.marginLeft = '17rem';

                        // Show titles
                        setTimeout(() => {
                            sidebarTitles.forEach(title => title.style.opacity = '1');
                            menuTitles.forEach(title => title.style.opacity = '1');
                        }, 150);

                        // Reset collapse button rotation
                        sidebarCollapseBtn.style.transform = 'rotate(0deg)';
                    }
                });
            }

            // Mobile sidebar toggle
            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function() {
                    sidebar.classList.add('open');
                    sidebarOverlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }

            // Mobile sidebar close
            if (mobileSidebarClose) {
                mobileSidebarClose.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    sidebarOverlay.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                });
            }

            // Overlay click to close sidebar
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    sidebarOverlay.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                });
            }

            // Submenu functionality
            const submenuButtons = document.querySelectorAll('[data-submenu]');
            submenuButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const submenuId = this.getAttribute('data-submenu') + '-submenu';
                    const submenu = document.getElementById(submenuId);
                    const arrow = this.querySelector('.submenu-arrow');

                    if (submenu.classList.contains('open')) {
                        submenu.classList.remove('open');
                        arrow.style.transform = 'rotate(0deg)';
                    } else {
                        // Close all other submenus
                        document.querySelectorAll('.submenu-container').forEach(menu => {
                            menu.classList.remove('open');
                        });
                        document.querySelectorAll('.submenu-arrow').forEach(arr => {
                            arr.style.transform = 'rotate(0deg)';
                        });

                        // Open clicked submenu
                        submenu.classList.add('open');
                        arrow.style.transform = 'rotate(180deg)';
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('open');
                    sidebarOverlay.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });
        });
    </script>
</body>
</html>
