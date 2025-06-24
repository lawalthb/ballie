@extends('layouts.tenant-onboarding')

@section('title', 'Team Setup - Ballie Setup')

@section('content')
<!-- Progress Steps -->
<div class="mb-8">
    <div class="flex items-center justify-center space-x-8">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-brand-green text-white rounded-full flex items-center justify-center font-semibold">✓</div>
            <span class="ml-3 text-sm font-medium text-brand-green">Company Info</span>
        </div>
        <div class="w-16 h-1 bg-brand-green rounded"></div>
        <div class="flex items-center">
            <div class="w-10 h-10 bg-brand-green text-white rounded-full flex items-center justify-center font-semibold">✓</div>
            <span class="ml-3 text-sm font-medium text-brand-green">Preferences</span>
        </div>
        <div class="w-16 h-1 bg-brand-green rounded"></div>
        <div class="flex items-center">
            <div class="w-10 h-10 bg-brand-blue text-white rounded-full flex items-center justify-center font-semibold">3</div>
            <span class="ml-3 text-sm font-medium text-brand-blue">Team Setup</span>
        </div>
        <div class="w-16 h-1 bg-brand-blue rounded"></div>
        <div class="flex items-center">
            <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">4</div>
            <span class="ml-3 text-sm font-medium text-gray-500">Complete</span>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-gradient-to-br from-brand-purple to-brand-violet rounded-lg flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Invite your team</h2>
        <p class="text-gray-600">Add team members and assign roles to collaborate effectively on Ballie.</p>
    </div>

    <form method="POST" action="{{ route('tenant.onboarding.save-step', ['tenant' => $tenant->slug, 'step' => 'team']) }}">
        @csrf

        <!-- Current User Info -->
        <div class="bg-brand-blue bg-opacity-5 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-brand-blue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Account Owner
            </h3>
            <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-brand-blue text-white rounded-full flex items-center justify-center font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="ml-4">
                        <div class="font-medium text-gray-900">{{ auth()->user()->name }}</div>
                        <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <div class="px-3 py-1 bg-brand-gold text-white text-sm rounded-full font-medium">
                    Owner
                </div>
            </div>
        </div>

        <!-- Team Members -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-brand-teal mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    Team Members
                </h3>
                <button type="button" onclick="addTeamMember()"
                        class="px-4 py-2 bg-brand-teal text-white rounded-lg hover:bg-brand-green font-medium text-sm">
                    + Add Member
                </button>
            </div>

            <div id="team-members-container">
                <!-- Team member template will be added here -->
            </div>

            <div class="text-center py-8" id="no-members-message">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-gray-500 mb-4">No team members added yet</p>
                <p class="text-sm text-gray-400">Click "Add Member" to invite your team</p>
            </div>
        </div>

        <!-- Role Descriptions -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Role Permissions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center mb-2">
                        <div class="w-3 h-3 bg-brand-gold rounded-full mr-2"></div>
                        <span class="font-medium text-gray-900">Admin</span>
                    </div>
                    <p class="text-sm text-gray-600">Full access to all features and settings</p>
                </div>

                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center mb-2">
                        <div class="w-3 h-3 bg-brand-blue rounded-full mr-2"></div>
                        <span class="font-medium text-gray-900">Manager</span>
                    </div>
                    <p class="text-sm text-gray-600">Manage operations, view reports, limited settings</p>
                </div>

                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center mb-2">
                        <div class="w-3 h-3 bg-brand-purple rounded-full mr-2"></div>
                        <span class="font-medium text-gray-900">Accountant</span>
                    </div>
                    <p class="text-sm text-gray-600">Financial data, invoicing, reports</p>
                </div>

                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center mb-2">
                        <div class="w-3 h-3 bg-brand-teal rounded-full mr-2"></div>
                        <span class="font-medium text-gray-900">Sales</span>
                    </div>
                    <p class="text-sm text-gray-600">Customer management, quotes, orders</p>
                </div>

                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center mb-2">
                        <div class="w-3 h-3 bg-brand-green rounded-full mr-2"></div>
                        <span class="font-medium text-gray-900">Employee</span>
                    </div>
                    <p class="text-sm text-gray-600">Basic access, own tasks and data</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center pt-6 border-t border-gray-200">
            <a href="{{ route('tenant.onboarding.step', ['tenant' => $tenant->slug, 'step' => 'preferences']) }}"
               class="text-gray-600 hover:text-brand-blue font-medium">
                ← Back to Preferences
            </a>

            <div class="flex space-x-4">
                <button type="button" onclick="skipStep()"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                    Skip This Step
                </button>
                <button type="submit"
                        class="px-8 py-3 bg-brand-blue text-white rounded-lg hover:bg-brand-dark-purple font-medium">
                    Continue →
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Team Member Template (Hidden) -->
<template id="team-member-template">
    <div class="team-member-item bg-white border border-gray-200 rounded-lg p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="team_members[INDEX][name]"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent"
                           placeholder="Full name" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="team_members[INDEX][email]"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent"
                           placeholder="email@example.com" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="team_members[INDEX][role]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent">
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="accountant">Accountant</option>
                        <option value="sales">Sales</option>
                        <option value="employee" selected>Employee</option>
                    </select>
                </div>
            </div>
            <button type="button" onclick="removeTeamMember(this)"
                    class="ml-4 p-2 text-red-600 hover:bg-red-50 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    </div>
</template>

@push('scripts')
<script>
let memberIndex = 0;

function addTeamMember() {
    const container = document.getElementById('team-members-container');
    const template = document.getElementById('team-member-template');
    const noMembersMessage = document.getElementById('no-members-message');

    // Clone template
    const clone = template.content.cloneNode(true);

    // Replace INDEX with actual index
    const inputs = clone.querySelectorAll('input, select');
    inputs.forEach(input => {
        if (input.name) {
            input.name = input.name.replace('INDEX', memberIndex);
        }
    });

    container.appendChild(clone);
    memberIndex++;

    // Hide no members message
    noMembersMessage.style.display = 'none';
}

function removeTeamMember(button) {
    const memberItem = button.closest('.team-member-item');
    memberItem.remove();

    // Show no members message if no members left
    const container = document.getElementById('team-members-container');
    const noMembersMessage = document.getElementById('no-members-message');

    if (container.children.length === 0) {
        noMembersMessage.style.display = 'block';
    }
}

function skipStep() {
    if (confirm('Are you sure you want to skip team setup? You can always invite team members later.')) {
        window.location.href = "{{ route('tenant.onboarding.step', ['tenant' => $tenant->slug, 'step' => 'complete']) }}";
    }
}
</script>
@endsection