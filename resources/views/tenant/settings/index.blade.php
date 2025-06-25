@extends('layouts.tenant')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-description', 'Manage your business settings and preferences')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Settings Navigation -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button class="settings-tab active border-transparent text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="general">
                    General
                </button>
                <button class="settings-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="business">
                    Business Info
                </button>
                <button class="settings-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="billing">
                    Billing
                </button>
                <button class="settings-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="users">
                    Users & Permissions
                </button>
                <button class="settings-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="integrations">
                    Integrations
                </button>
            </nav>
        </div>
    </div>

    <!-- General Settings -->
    <div id="general-tab" class="settings-content">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">General Settings</h3>
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                        <select id="timezone" name="timezone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="Africa/Lagos" selected>Africa/Lagos (WAT)</option>
                            <option value="UTC">UTC</option>
                            <option value="America/New_York">America/New_York (EST)</option>
                        </select>
                    </div>
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Default Currency</label>
                        <select id="currency" name="currency" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="NGN" selected>Nigerian Naira (₦)</option>
                            <option value="USD">US Dollar ($)</option>
                            <option value="EUR">Euro (€)</option>
                            <option value="GBP">British Pound (£)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_format" class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                        <select id="date_format" name="date_format" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="d/m/Y" selected>DD/MM/YYYY</option>
                            <option value="m/d/Y">MM/DD/YYYY</option>
                            <option value="Y-m-d">YYYY-MM-DD</option>
                            <option value="d-M-Y">DD-MMM-YYYY</option>
                        </select>
                    </div>
                    <div>
                        <label for="number_format" class="block text-sm font-medium text-gray-700 mb-2">Number Format</label>
                        <select id="number_format" name="number_format" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="1,234.56" selected>1,234.56</option>
                            <option value="1.234,56">1.234,56</option>
                            <option value="1 234.56">1 234.56</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">Notifications</label>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input id="email_notifications" name="email_notifications" type="checkbox" checked class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label for="email_notifications" class="ml-3 text-sm text-gray-700">Email notifications</label>
                        </div>
                        <div class="flex items-center">
                            <input id="sms_notifications" name="sms_notifications" type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label for="sms_notifications" class="ml-3 text-sm text-gray-700">SMS notifications</label>
                        </div>
                        <div class="flex items-center">
                            <input id="browser_notifications" name="browser_notifications" type="checkbox" checked class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label for="browser_notifications" class="ml-3 text-sm text-gray-700">Browser notifications</label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Business Info Settings -->
    <div id="business-tab" class="settings-content hidden">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Business Information</h3>
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                        <input type="text" id="business_name" name="business_name" value="Acme Corporation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="business_type" class="block text-sm font-medium text-gray-700 mb-2">Business Type</label>
                        <select id="business_type" name="business_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="retail" selected>Retail</option>
                            <option value="wholesale">Wholesale</option>
                            <option value="manufacturing">Manufacturing</option>
                            <option value="services">Services</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tax_id" class="block text-sm font-medium text-gray-700 mb-2">Tax ID (TIN)</label>
                        <input type="text" id="tax_id" name="tax_id" value="12345678-0001" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="vat_number" class="block text-sm font-medium text-gray-700 mb-2">VAT Number</label>
                        <input type="text" id="vat_number" name="vat_number" value="NG-VAT-123456" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label for="business_address" class="block text-sm font-medium text-gray-700 mb-2">Business Address</label>
                    <textarea id="business_address" name="business_address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">123 Business Street, Victoria Island, Lagos, Nigeria</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="business_phone" class="block text-sm font-medium text-gray-700 mb-2">Business Phone</label>
                        <input type="tel" id="business_phone" name="business_phone" value="+234 801 234 5678" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="business_email" class="block text-sm font-medium text-gray-700 mb-2">Business Email</label>
                        <input type="email" id="business_email" name="business_email" value="info@acmecorp.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label for="business_logo" class="block text-sm font-medium text-gray-700 mb-2">Business Logo</label>
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                Upload Logo
                            </button>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 2MB</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Billing Settings -->
    <div id="billing-tab" class="settings-content hidden">
        <div class="space-y-6">
            <!-- Current Plan -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Current Plan</h3>
                <div class="flex items-center justify-between p-4 bg-primary-50 rounded-lg">
                    <div>
                        <h4 class="text-lg font-semibold text-primary-900">Professional Plan</h4>
                        <p class="text-sm text-primary-700">₦15,000 per month</p>
                        <p class="text-xs text-primary-600 mt-1">Next billing date: February 15, 2024</p>
                    </div>
                    <div class="text-right">
                        <button class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                            Upgrade Plan
                        </button>
                        <p class="text-xs text-primary-600 mt-1">
                            <a href="#" class="hover:underline">View all plans</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Payment Method</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-6 bg-blue-600 rounded flex items-center justify-center mr-3">
                                <span class="text-xs font-bold text-white">VISA</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">**** **** **** 4242</p>
                                <p class="text-xs text-gray-500">Expires 12/25</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Default
                            </span>
                            <button class="text-primary-600 hover:text-primary-700 text-sm">Edit</button>
                        </div>
                    </div>
                    <button class="w-full p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 transition-colors">
                        <svg class="w-6 h-6 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="text-sm text-gray-600">Add Payment Method</span>
                    </button>
                </div>
            </div>

            <!-- Billing History -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Billing History</h3>
                    <button class="text-primary-600 hover:text-primary-700 text-sm">Download All</button>
                </div>
                <div class="space-y-3">
                    @php
                    $billingHistory = [
                        ['date' => '2024-01-15', 'amount' => 15000, 'status' => 'paid', 'invoice' => 'INV-2024-001'],
                        ['date' => '2023-12-15', 'amount' => 15000, 'status' => 'paid', 'invoice' => 'INV-2023-012'],
                        ['date' => '2023-11-15', 'amount' => 15000, 'status' => 'paid', 'invoice' => 'INV-2023-011'],
                        ['date' => '2023-10-15', 'amount' => 15000, 'status' => 'paid', 'invoice' => 'INV-2023-010'],
                    ];
                    @endphp

                    @foreach($billingHistory as $bill)
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $bill['invoice'] }}</p>
                                <p class="text-xs text-gray-500">{{ date('M j, Y', strtotime($bill['date'])) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-900">₦{{ number_format($bill['amount']) }}</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst($bill['status']) }}
                            </span>
                            <button class="text-primary-600 hover:text-primary-700 text-sm">Download</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Users & Permissions -->
    <div id="users-tab" class="settings-content hidden">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Team Members</h3>
                <button class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Invite User
                </button>
            </div>

            <div class="space-y-4">
                @php
                $teamMembers = [
                    ['name' => 'John Doe', 'email' => 'john@acmecorp.com', 'role' => 'Owner', 'status' => 'active', 'last_login' => '2024-01-15 10:30:00'],
                    ['name' => 'Sarah Wilson', 'email' => 'sarah@acmecorp.com', 'role' => 'Admin', 'status' => 'active', 'last_login' => '2024-01-14 16:45:00'],
                    ['name' => 'Mike Johnson', 'email' => 'mike@acmecorp.com', 'role' => 'Accountant', 'status' => 'active', 'last_login' => '2024-01-13 09:15:00'],
                    ['name' => 'Jane Smith', 'email' => 'jane@acmecorp.com', 'role' => 'Sales', 'status' => 'pending', 'last_login' => null],
                ];
                @endphp

                @foreach($teamMembers as $member)
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-4">
                            <span class="text-sm font-medium text-gray-700">{{ substr($member['name'], 0, 2) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $member['name'] }}</p>
                            <p class="text-xs text-gray-500">{{ $member['email'] }}</p>
                            @if($member['last_login'])
                                <p class="text-xs text-gray-400">Last login: {{ date('M j, Y g:i A', strtotime($member['last_login'])) }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $member['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($member['status']) }}
                        </span>
                        <span class="text-sm text-gray-600">{{ $member['role'] }}</span>
                        @if($member['role'] !== 'Owner')
                        <div class="flex items-center space-x-2">
                            <button class="text-primary-600 hover:text-primary-700 text-sm">Edit</button>
                            <button class="text-red-600 hover:text-red-700 text-sm">Remove</button>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Role Permissions -->
            <div class="mt-8">
                <h4 class="text-md font-semibold text-gray-900 mb-4">Role Permissions</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                    $roles = [
                        ['name' => 'Admin', 'permissions' => ['Full Access', 'User Management', 'Settings'], 'users' => 1],
                        ['name' => 'Accountant', 'permissions' => ['Financial Reports', 'Invoicing', 'Expenses'], 'users' => 1],
                        ['name' => 'Sales', 'permissions' => ['CRM', 'Quotes', 'Orders'], 'users' => 1],
                        ['name' => 'Employee', 'permissions' => ['Basic Access', 'Time Tracking'], 'users' => 0],
                    ];
                    @endphp

                    @foreach($roles as $role)
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="font-medium text-gray-900">{{ $role['name'] }}</h5>
                            <span class="text-xs text-gray-500">{{ $role['users'] }} users</span>
                        </div>
                        <div class="space-y-1">
                            @foreach($role['permissions'] as $permission)
                            <p class="text-xs text-gray-600">• {{ $permission }}</p>
                            @endforeach
                        </div>
                        <button class="mt-3 text-primary-600 hover:text-primary-700 text-sm">Edit Permissions</button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Integrations -->
    <div id="integrations-tab" class="settings-content hidden">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Integrations</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @php
                $integrations = [
                    ['name' => 'Paystack', 'description' => 'Accept online payments', 'status' => 'connected', 'icon' => 'payment'],
                    ['name' => 'Flutterwave', 'description' => 'Payment processing', 'status' => 'available', 'icon' => 'payment'],
                    ['name' => 'Nigerian Banks', 'description' => 'Bank account integration', 'status' => 'connected', 'icon' => 'bank'],
                    ['name' => 'SMS Services', 'description' => 'Send SMS notifications', 'status' => 'connected', 'icon' => 'sms'],
                    ['name' => 'Email Marketing', 'description' => 'Customer email campaigns', 'status' => 'available', 'icon' => 'email'],
                    ['name' => 'Accounting Software', 'description' => 'Sync with external accounting', 'status' => 'available', 'icon' => 'accounting'],
                ];
                @endphp

                @foreach($integrations as $integration)
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                @if($integration['icon'] === 'payment')
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                @elseif($integration['icon'] === 'bank')
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                @elseif($integration['icon'] === 'sms')
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                @elseif($integration['icon'] === 'email')
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                @else
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $integration['name'] }}</h4>
                                <p class="text-sm text-gray-500">{{ $integration['description'] }}</p>
                            </div>
                        </div>
                        @if($integration['status'] === 'connected')
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Connected
                            </span>
                            <button class="text-gray-600 hover:text-gray-700 text-sm">Configure</button>
                        </div>
                        @else
                        <button class="px-3 py-1 bg-primary-600 text-white rounded text-sm hover:bg-primary-700 transition-colors">
                            Connect
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- API Settings -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-medium text-gray-900 mb-3">API Access</h4>
                <p class="text-sm text-gray-600 mb-4">Use our API to integrate Ballie with your custom applications.</p>
                <div class="flex items-center space-x-4">
                    <button class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        Generate API Key
                    </button>
                    <a href="#" class="text-primary-600 hover:text-primary-700 text-sm">View Documentation</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.settings-tab');
    const contents = document.querySelectorAll('.settings-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all tabs
            tabs.forEach(t => {
                t.classList.remove('active', 'border-primary-500', 'text-primary-600');
                t.classList.add('border-transparent', 'text-gray-500');
            });

            // Add active class to clicked tab
            this.classList.add('active', 'border-primary-500', 'text-primary-600');
            this.classList.remove('border-transparent', 'text-gray-500');

            // Hide all content
            contents.forEach(content => {
                content.classList.add('hidden');
            });

            // Show target content
            document.getElementById(targetTab + '-tab').classList.remove('hidden');
        });
    });
});
</script>
@endpush