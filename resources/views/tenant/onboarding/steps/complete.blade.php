@extends('layouts.tenant-onboarding')

@section('title', 'Setup Complete - Welcome to Ballie!')

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
            <div class="w-10 h-10 bg-brand-green text-white rounded-full flex items-center justify-center font-semibold">✓</div>
            <span class="ml-3 text-sm font-medium text-brand-green">Team Setup</span>
        </div>
        <div class="w-16 h-1 bg-brand-green rounded"></div>
        <div class="flex items-center">
            <div class="w-10 h-10 bg-brand-gold text-white rounded-full flex items-center justify-center font-semibold">✓</div>
            <span class="ml-3 text-sm font-medium text-brand-gold">Complete</span>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
    <!-- Success Animation -->
    <div class="mb-8">
        <div class="w-24 h-24 bg-gradient-to-br from-brand-green to-brand-teal rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-4">🎉 Welcome to Ballie!</h1>
        <p class="text-xl text-gray-600 mb-8">Your business management system is ready to go!</p>
    </div>

    <!-- Setup Summary -->
    <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 text-center">Setup Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-medium text-gray-900 mb-2 flex items-center">
                    <svg class="w-4 h-4 text-brand-blue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h2M7 7h10M7 11h10M7 15h10"></path>
                    </svg>
                    Company Information
                </h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>✓ Business details configured</li>
                    <li>✓ Contact information set</li>
                    <li>✓ Tax settings applied</li>
                </ul>
            </div>

            <div>
                <h3 class="font-medium text-gray-900 mb-2 flex items-center">
                    <svg class="w-4 h-4 text-brand-teal mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Business Preferences
                </h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>✓ Currency and localization set</li>
                    <li>✓ Invoice templates configured</li>
                    <li>✓ Features enabled</li>
                </ul>
            </div>

            <div>
                <h3 class="font-medium text-gray-900 mb-2 flex items-center">
                    <svg class="w-4 h-4 text-brand-purple mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Team Setup
                </h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>✓ Account owner configured</li>
                    <li>✓ Team invitations sent</li>
                    <li>✓ Roles and permissions set</li>
                </ul>
            </div>

            <div>
                <h3 class="font-medium text-gray-900 mb-2 flex items-center">
                    <svg class="w-4 h-4 text-brand-gold mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    System Ready
                </h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>✓ Database initialized</li>
                    <li>✓ Default data created</li>
                    <li>✓ Ready for business!</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Next Steps -->
    <div class="bg-gradient-to-br from-brand-blue to-brand-purple rounded-lg p-6 text-white mb-8">
        <h2 class="text-xl font-semibold mb-4">What's Next?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-left">
            <div class="bg-white bg-opacity-10 rounded-lg p-4">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <h3 class="font-medium mb-2">Add Products</h3>
                <p class="text-sm opacity-90">Start by adding your products and services to the inventory.</p>
            </div>

            <div class="bg-white bg-opacity-10 rounded-lg p-4">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="font-medium mb-2">Add Customers</h3>
                <p class="text-sm opacity-90">Import or add your customer database to start invoicing.</p>
            </div>

            <div class="bg-white bg-opacity-10 rounded-lg p-4">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="font-medium mb-2">Create Invoice</h3>
                <p class="text-sm opacity-90">Create your first invoice and start getting paid.</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
        <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug]) }}"
           class="px-8 py-4 bg-brand-gold text-white rounded-lg hover:bg-yellow-600 font-semibold text-lg transition-colors flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v1H8V5z"></path>
            </svg>
            Go to Dashboard
        </a>

        <a href="{{ route('tenant.help', ['tenant' => $tenant->slug]) }}"
           class="px-6 py-4 border-2 border-brand-blue text-brand-blue rounded-lg hover:bg-brand-blue hover:text-white font-semibold transition-colors flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Get Help
        </a>
    </div>

    <!-- Support Information -->
    <div class="bg-gray-50 rounded-lg p-6 text-left">
        <h3 class="font-semibold text-gray-900 mb-4 text-center">Need Help Getting Started?</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <div class="w-12 h-12 bg-brand-blue rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-900 mb-2">Documentation</h4>
                <p class="text-sm text-gray-600 mb-3">Comprehensive guides and tutorials</p>
                <a href="{{ route('tenant.docs', ['tenant' => $tenant->slug]) }}"
                   class="text-brand-blue hover:text-brand-purple text-sm font-medium">
                    View Docs →
                </a>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 bg-brand-teal rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-900 mb-2">Live Chat</h4>
                <p class="text-sm text-gray-600 mb-3">Chat with our support team</p>
                <button onclick="openLiveChat()"
                        class="text-brand-blue hover:text-brand-purple text-sm font-medium">
                    Start Chat →
                </button>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 bg-brand-purple rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-900 mb-2">Phone Support</h4>
                <p class="text-sm text-gray-600 mb-3">Call us for immediate assistance</p>
                <a href="tel:+2348000000000"
                   class="text-brand-blue hover:text-brand-purple text-sm font-medium">
                    +234 800 000 0000
                </a>
            </div>
        </div>
    </div>

    <!-- Celebration Message -->
    <div class="mt-8 p-4 bg-gradient-to-r from-brand-gold to-brand-green rounded-lg text-white">
        <p class="text-lg font-medium">🚀 You're all set! Welcome to the future of business management.</p>
    </div>
</div>

@push('scripts')
<script>
// Confetti animation on page load
document.addEventListener('DOMContentLoaded', function() {
    // Simple confetti effect
    createConfetti();
});

function createConfetti() {
    const colors = ['#d1b05e', '#2b6399', '#3c2c64', '#69a2a4', '#249484'];
    const confettiCount = 50;

    for (let i = 0; i < confettiCount; i++) {
        setTimeout(() => {
            const confetti = document.createElement('div');
            confetti.style.position = 'fixed';
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.top = '-10px';
            confetti.style.width = '10px';
            confetti.style.height = '10px';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.borderRadius = '50%';
            confetti.style.pointerEvents = 'none';
            confetti.style.zIndex = '9999';
            confetti.style.animation = 'fall 3s linear forwards';

            document.body.appendChild(confetti);

            setTimeout(() => {
                confetti.remove();
            }, 3000);
        }, i * 100);
    }
}

function openLiveChat() {
    // Integrate with your live chat system
    alert('Live chat feature will be integrated with your support system.');
}

// Add CSS for confetti animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fall {
        to {
            transform: translateY(100vh) rotate(360deg);
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection