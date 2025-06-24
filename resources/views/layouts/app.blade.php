<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Ballie - Nigerian Business Management Software')</title>
    <meta name="description" content="@yield('description', 'Comprehensive business management software built specifically for Nigerian businesses. Manage accounting, inventory, sales, and more in one platform.')">

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
                        },
                        brand: {
                            gold: '#d1b05e',
                            blue: '#2b6399',
                            'dark-purple': '#3c2c64',
                            teal: '#69a2a4',
                            purple: '#85729d',
                            'light-blue': '#7b87b8',
                            'deep-purple': '#4a3570',
                            lavender: '#a48cb4',
                            violet: '#614c80',
                            green: '#249484',
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
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center flex-shrink-0">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <div class="w-24 h-8 rounded-lg flex items-center justify-center mr-3" >
                               <img src="{{ asset('images/ballie_logo.png') }}" alt="Ballie Logo" class="w-36 h-12">
                            </div>
                            <span class="text-xl font-bold text-gray-900 text-violet-950">Ballie</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('features') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">Features</a>
                        <a href="{{ route('pricing') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">Pricing</a>
                        <a href="{{ route('about') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">About</a>
                        <a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">Contact</a>

                        @auth
                            <div class="relative">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            <div>{{ Auth::user()->name }}</div>
                                            <div class="ml-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('dashboard')">
                                            {{ __('Dashboard') }}
                                        </x-dropdown-link>

                                        <x-dropdown-link :href="route('profile.edit')">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('logout')"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">Login</a>
                            <a href="{{ route('register') }}" class="text-white px-4 py-2 rounded-lg hover:opacity-90 font-medium transition-all duration-200" style="background-color: #2b6399;">Get Started</a>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button class="text-gray-600 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition-colors duration-200" onclick="toggleMobileMenu()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <div id="mobileMenu" class="hidden md:hidden border-t border-gray-200 py-4">
                    <div class="space-y-3">
                        <a href="{{ route('features') }}" class="block text-gray-600 hover:text-gray-900 font-medium py-2 px-2 rounded transition-colors duration-200">Features</a>
                        <a href="{{ route('pricing') }}" class="block text-gray-600 hover:text-gray-900 font-medium py-2 px-2 rounded transition-colors duration-200">Pricing</a>
                        <a href="{{ route('about') }}" class="block text-gray-600 hover:text-gray-900 font-medium py-2 px-2 rounded transition-colors duration-200">About</a>
                        <a href="{{ route('contact') }}" class="block text-gray-600 hover:text-gray-900 font-medium py-2 px-2 rounded transition-colors duration-200">Contact</a>

                        @auth
                            <div class="border-t border-gray-200 pt-3 mt-3">
                                <a href="{{ route('dashboard') }}" class="block text-gray-600 hover:text-gray-900 font-medium py-2 px-2 rounded transition-colors duration-200">Dashboard</a>
                                <a href="{{ route('profile.edit') }}" class="block text-gray-600 hover:text-gray-900 font-medium py-2 px-2 rounded transition-colors duration-200">Profile</a>
                                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); this.closest('form').submit();"
                                       class="block text-gray-600 hover:text-gray-900 font-medium py-2 px-2 rounded transition-colors duration-200">Logout</a>
                                </form>
                            </div>
                        @else
                            <div class="border-t border-gray-200 pt-3 mt-3 space-y-2">
                                <a href="{{ route('login') }}" class="block text-gray-600 hover:text-gray-900 font-medium py-2 px-2 rounded transition-colors duration-200">Login</a>
                                <a href="{{ route('register') }}" class="block text-white px-4 py-2 rounded-lg hover:opacity-90 font-medium text-center transition-all duration-200" style="background-color: #2b6399;">Get Started</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3" style="background-color: #2b6399;">
                                <img src="{{ asset('images/ballie_logo.png') }}" alt="Ballie Logo" class="w-24 h-8">
                            </div>
                            <span class="text-xl font-bold">Ballie</span>
                        </div>
                        <p class="text-gray-300 mb-4 max-w-md">
                            Comprehensive business management software built specifically for Nigerian businesses.
                            Manage your entire business from one powerful platform with unmatched <strong style="color: #d1b05e;">Availability & Affordability</strong>.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.989 3.992-.281 1.189.597 2.165 1.771 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.221.083.343-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.747 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.624 0 11.99-5.367 11.99-11.99C24.007 5.367 18.641.001 12.017.001z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4" style="color: #d1b05e;">Product</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('features') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Features</a></li>
                            <li><a href="{{ route('pricing') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Pricing</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Integrations</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">API</a></li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4" style="color: #d1b05e;">Support</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Contact Us</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Help Center</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Documentation</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Status</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        © {{ date('Y') }} Ballie. All rights reserved. Made with ❤️ for Nigerian businesses.
                    </p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-200">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-200">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-200">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');
            const button = event.target.closest('button');

            if (!menu.contains(event.target) && !button) {
                menu.classList.add('hidden');
            }
        });

        // Close mobile menu on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                document.getElementById('mobileMenu').classList.add('hidden');
            }
        });
    </script>
</body>
</html>
