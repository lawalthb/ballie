<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\AuthController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\OnboardingController;
use App\Http\Controllers\Tenant\SocialAuthController;
use App\Http\Controllers\Tenant\InvoiceController;
use App\Http\Controllers\Tenant\CustomerController;
use App\Http\Controllers\Tenant\ProductController;
use App\Http\Controllers\Tenant\InventoryController;
use App\Http\Controllers\Tenant\ReportController;
use App\Http\Controllers\Tenant\SettingsController;
use App\Http\Controllers\Tenant\UserController;
use App\Http\Controllers\Tenant\PayrollController;
use App\Http\Controllers\Tenant\AccountingController;
use App\Http\Controllers\Tenant\InvitationController;
use App\Models\Tenant;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the RouteServiceProvider and are
| prefixed with the tenant slug from the main web.php routes.
|
*/

// Route model binding for tenant
Route::bind('tenant', function ($value) {
    return Tenant::where('slug', $value)->firstOrFail();
});

// Tenant Authentication Routes (Guest only)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('tenant.register');
    Route::post('/register', [AuthController::class, 'register']);

    // Social Authentication
    Route::get('/auth/{provider}', [SocialAuthController::class, 'redirect'])->name('tenant.social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('tenant.social.callback');

    // Password Reset
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('tenant.password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('tenant.password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('tenant.password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('tenant.password.update');
});

// Protected Tenant Routes (Authenticated users only)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('tenant.logout');

    // Team invitation acceptance (available to all authenticated users)
    Route::get('/invitation/accept/{token}', [InvitationController::class, 'accept'])->name('tenant.invitation.accept');
    Route::post('/invitation/accept/{token}', [InvitationController::class, 'processAcceptance'])->name('tenant.invitation.process');

    // Onboarding routes (for users who haven't completed onboarding)
    Route::middleware(['check.onboarding'])->group(function () {
        Route::get('/onboarding', [OnboardingController::class, 'index'])->name('tenant.onboarding');
        Route::get('/onboarding/{step}', [OnboardingController::class, 'showStep'])->name('tenant.onboarding.step');
        Route::post('/onboarding/{step}', [OnboardingController::class, 'saveStep'])->name('tenant.onboarding.save-step');
        Route::post('/onboarding/{step}/skip', [OnboardingController::class, 'skip'])->name('tenant.onboarding.skip-step');
        Route::post('/onboarding/complete', [OnboardingController::class, 'complete'])->name('tenant.onboarding.complete');
    });

    // Main Application Routes (only accessible after onboarding)
    Route::middleware(['onboarding.completed'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');

        // Help and Documentation
        Route::get('/help', function () {
            return view('tenant.help');
        })->name('tenant.help');

        Route::get('/docs', function () {
            return view('tenant.docs');
        })->name('tenant.docs');

        // Customers
        Route::resource('customers', CustomerController::class)->names([
            'index' => 'tenant.customers.index',
            'create' => 'tenant.customers.create',
            'store' => 'tenant.customers.store',
            'show' => 'tenant.customers.show',
            'edit' => 'tenant.customers.edit',
            'update' => 'tenant.customers.update',
            'destroy' => 'tenant.customers.destroy',
        ]);

        // Products & Inventory
        Route::resource('products', ProductController::class)->names([
            'index' => 'tenant.products.index',
            'create' => 'tenant.products.create',
            'store' => 'tenant.products.store',
            'show' => 'tenant.products.show',
            'edit' => 'tenant.products.edit',
            'update' => 'tenant.products.update',
            'destroy' => 'tenant.products.destroy',
        ]);

        Route::prefix('inventory')->name('tenant.inventory.')->group(function () {
            Route::get('/', [InventoryController::class, 'index'])->name('index');
            Route::get('/stock-levels', [InventoryController::class, 'stockLevels'])->name('stock-levels');
            Route::get('/movements', [InventoryController::class, 'movements'])->name('movements');
            Route::post('/adjust', [InventoryController::class, 'adjust'])->name('adjust');
            Route::get('/reports', [InventoryController::class, 'reports'])->name('reports');
        });

        // Invoices & Sales
        Route::resource('invoices', InvoiceController::class)->names([
            'index' => 'tenant.invoices.index',
            'create' => 'tenant.invoices.create',
            'store' => 'tenant.invoices.store',
            'show' => 'tenant.invoices.show',
            'edit' => 'tenant.invoices.edit',
            'update' => 'tenant.invoices.update',
            'destroy' => 'tenant.invoices.destroy',
        ]);

        Route::prefix('invoices')->name('tenant.invoices.')->group(function () {
            Route::get('/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('pdf');
            Route::post('/{invoice}/send', [InvoiceController::class, 'sendEmail'])->name('send');
            Route::post('/{invoice}/mark-paid', [InvoiceController::class, 'markAsPaid'])->name('mark-paid');
            Route::get('/{invoice}/duplicate', [InvoiceController::class, 'duplicate'])->name('duplicate');
        });

        // Accounting
        Route::prefix('accounting')->name('tenant.accounting.')->group(function () {
            Route::get('/chart-of-accounts', [AccountingController::class, 'chartOfAccounts'])->name('chart-of-accounts');
            Route::get('/journal-entries', [AccountingController::class, 'journalEntries'])->name('journal-entries');
            Route::get('/trial-balance', [AccountingController::class, 'trialBalance'])->name('trial-balance');
            Route::get('/profit-loss', [AccountingController::class, 'profitLoss'])->name('profit-loss');
            Route::get('/balance-sheet', [AccountingController::class, 'balanceSheet'])->name('balance-sheet');
            Route::get('/cash-flow', [AccountingController::class, 'cashFlow'])->name('cash-flow');
        });

        // Payroll (Admin/Owner only)
        Route::middleware(['role:owner,admin'])->prefix('payroll')->name('tenant.payroll.')->group(function () {
            Route::get('/', [PayrollController::class, 'index'])->name('index');
            Route::get('/employees', [PayrollController::class, 'employees'])->name('employees');
            Route::get('/run-payroll', [PayrollController::class, 'runPayroll'])->name('run');
            Route::post('/process-payroll', [PayrollController::class, 'processPayroll'])->name('process');
            Route::get('/payslips', [PayrollController::class, 'payslips'])->name('payslips');
            Route::get('/tax-reports', [PayrollController::class, 'taxReports'])->name('tax-reports');
        });

        // Reports
        Route::prefix('reports')->name('tenant.reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
            Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
            Route::get('/products', [ReportController::class, 'products'])->name('products');
            Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
            Route::get('/tax', [ReportController::class, 'tax'])->name('tax');
        });

        // User Management (Admin/Owner only)
        Route::middleware(['role:owner,admin'])->prefix('users')->name('tenant.users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Settings (Admin/Owner only)
        Route::middleware(['role:owner,admin'])->prefix('settings')->name('tenant.settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::get('/company', [SettingsController::class, 'company'])->name('company');
            Route::put('/company', [SettingsController::class, 'updateCompany'])->name('company.update');
            Route::get('/billing', [SettingsController::class, 'billing'])->name('billing');
            Route::get('/integrations', [SettingsController::class, 'integrations'])->name('integrations');
            Route::get('/notifications', [SettingsController::class, 'notifications'])->name('notifications');
            Route::get('/security', [SettingsController::class, 'security'])->name('security');
        });

        // Profile Management
        Route::prefix('profile')->name('tenant.profile.')->group(function () {
            Route::get('/', [AuthController::class, 'profile'])->name('index');
            Route::put('/', [AuthController::class, 'updateProfile'])->name('update');
            Route::put('/password', [AuthController::class, 'updatePassword'])->name('password.update');
            Route::post('/avatar', [AuthController::class, 'updateAvatar'])->name('avatar.update');
        });
    });
});
