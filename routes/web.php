<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\TenantController;
use App\Http\Controllers\SuperAdmin\AuthController as SuperAdminAuthController;

// Include authentication routes
require __DIR__.'/auth.php';

// Public routes (landing page, pricing, etc.)
Route::get('/', [HomeController::class, 'welcome'])->name('home');
Route::get('/features', [HomeController::class, 'features'])->name('features');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/demo', [HomeController::class, 'demo'])->name('demo');

// Social authentication routes
Route::get('/auth/google', [App\Http\Controllers\Auth\SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\SocialAuthController::class, 'handleGoogleCallback']);
Route::get('/auth/facebook', [App\Http\Controllers\Auth\SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [App\Http\Controllers\Auth\SocialAuthController::class, 'handleFacebookCallback']);

// General dashboard route that redirects to tenant dashboard
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();
    if ($user && $user->tenant) {
        return redirect()->route('tenant.dashboard', ['tenant' => $user->tenant->slug]);
    }
    return redirect()->route('home');
})->name('dashboard');

// Super Admin Routes
Route::prefix('super-admin')->name('super-admin.')->group(function () {
    // Guest routes
    Route::middleware(['guest:super_admin'])->group(function () {
        Route::get('/login', [SuperAdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [SuperAdminAuthController::class, 'login']);
        Route::get('/register', [SuperAdminAuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [SuperAdminAuthController::class, 'register']);
    });

    // Protected Super Admin Routes
    Route::middleware(['auth:super_admin'])->group(function () {
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [SuperAdminAuthController::class, 'logout'])->name('logout');

        // Tenant Management
        Route::resource('tenants', TenantController::class);
        Route::post('/tenants/{tenant}/suspend', [TenantController::class, 'suspend'])->name('tenants.suspend');
        Route::post('/tenants/{tenant}/activate', [TenantController::class, 'activate'])->name('tenants.activate');

        // Impersonation
        Route::post('/impersonate/{tenant}/{user}', [TenantController::class, 'impersonate'])->name('impersonate');
        Route::post('/stop-impersonation', [TenantController::class, 'stopImpersonation'])->name('stop-impersonation');
    });
});

// Tenant Routes (path-based: /tenant1/dashboard, /tenant2/invoices, etc.)
Route::prefix('{tenant}')->middleware(['tenant'])->group(function () {
    require __DIR__.'/tenant.php';
});
