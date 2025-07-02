<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\AuthController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\OnboardingController;
use App\Http\Controllers\Tenant\ProductController;
use App\Http\Controllers\Tenant\CustomerController;
use App\Http\Controllers\Tenant\InvoiceController;
use App\Http\Controllers\Tenant\HelpController;
use App\Http\Controllers\Tenant\SupportController;
use App\Http\Controllers\Tenant\CommunityController;
use App\Http\Controllers\Tenant\VendorController;
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

// Guest routes (login, register, etc.)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('tenant.register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('tenant.password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('tenant.password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('tenant.password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('tenant.password.update');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('tenant.logout');

    // Onboarding routes
    Route::prefix('onboarding')->name('tenant.onboarding.')->group(function () {
        Route::get('/', [OnboardingController::class, 'index'])->name('index');
        Route::post('/complete', [OnboardingController::class, 'complete'])->name('complete');
        Route::get('/{step}', [OnboardingController::class, 'showStep'])->name('step');
        Route::post('/{step}', [OnboardingController::class, 'saveStep'])->name('save-step');

        Route::get('/show-step', [OnboardingController::class, 'showStep'])->name('show-step');
    });

    // Routes that require completed onboarding
    Route::middleware(['onboarding.completed'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');

        // Accounting Routes
        Route::prefix('accounting')->name('tenant.accounting.')->group(function () {
            Route::get('/chart-of-accounts', [AccountingController::class, 'chartOfAccounts'])->name('chart-of-accounts');
            Route::get('/ledger/create', [AccountingController::class, 'createLedgerAccount'])->name('ledger.create');
            Route::post('/ledger', [AccountingController::class, 'storeLedgerAccount'])->name('ledger.store');
            Route::get('/ledger/{id}/edit', [AccountingController::class, 'editLedgerAccount'])->name('ledger.edit');
            Route::put('/ledger/{id}', [AccountingController::class, 'updateLedgerAccount'])->name('ledger.update');
        });

        // Voucher Routes
        Route::prefix('vouchers')->name('tenant.vouchers.')->group(function () {
            Route::get('/', [VoucherController::class, 'index'])->name('index');
            Route::get('/create/{type?}', [VoucherController::class, 'create'])->name('create');
            Route::post('/', [VoucherController::class, 'store'])->name('store');
            Route::get('/{voucher}', [VoucherController::class, 'show'])->name('show');
            Route::get('/{voucher}/edit', [VoucherController::class, 'edit'])->name('edit');
            Route::put('/{voucher}', [VoucherController::class, 'update'])->name('update');
            Route::post('/{voucher}/post', [VoucherController::class, 'post'])->name('post');
            Route::post('/{voucher}/unpost', [VoucherController::class, 'unpost'])->name('unpost');
            Route::delete('/{voucher}', [VoucherController::class, 'destroy'])->name('destroy');
        });

        // Ledger Routes
        Route::prefix('ledger')->name('tenant.ledger.')->group(function () {
            Route::get('/', [LedgerController::class, 'index'])->name('index');
            Route::get('/{ledger}', [LedgerController::class, 'show'])->name('show');
        });

        // Reports Routes
        Route::prefix('reports')->name('tenant.reports.')->group(function () {
            Route::get('/trial-balance', [ReportsController::class, 'trialBalance'])->name('trial-balance');
            Route::get('/day-book', [ReportsController::class, 'dayBook'])->name('day-book');
            Route::get('/profit-loss', [ReportsController::class, 'profitLoss'])->name('profit-loss');
            Route::get('/balance-sheet', [ReportsController::class, 'balanceSheet'])->name('balance-sheet');
        });

        // Products
        Route::prefix('products')->name('tenant.products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{product}', [ProductController::class, 'show'])->name('show');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        });

        // Customers
        Route::prefix('customers')->name('tenant.customers.')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::get('/create', [CustomerController::class, 'create'])->name('create');
            Route::post('/', [CustomerController::class, 'store'])->name('store');
            Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
            Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
            Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
            Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
        });

          // Vendors
    Route::prefix('vendors')->name('tenant.vendors.')->group(function () {
        Route::get('/', [VendorController::class, 'index'])->name('index');
        Route::get('/create', [VendorController::class, 'create'])->name('create');
        Route::post('/', [VendorController::class, 'store'])->name('store');
        Route::get('/{vendor}', [VendorController::class, 'show'])->name('show');
        Route::get('/{vendor}/edit', [VendorController::class, 'edit'])->name('edit');
        Route::put('/{vendor}', [VendorController::class, 'update'])->name('update');
        Route::delete('/{vendor}', [VendorController::class, 'destroy'])->name('destroy');
    });



        // Invoices
        Route::prefix('invoices')->name('tenant.invoices.')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::get('/create', [InvoiceController::class, 'create'])->name('create');
            Route::post('/', [InvoiceController::class, 'store'])->name('store');
            Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
            Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
            Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');
            Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
            Route::get('/{invoice}/pdf', [InvoiceController::class, 'generatePdf'])->name('pdf');
            Route::post('/{invoice}/send', [InvoiceController::class, 'sendToCustomer'])->name('send');
        });

        // Help and Support
        Route::get('/help/videos', [HelpController::class, 'videos'])->name('tenant.help.videos');
        Route::get('/help/articles', [HelpController::class, 'articles'])->name('tenant.help.articles');
        Route::get('/support', [SupportController::class, 'index'])->name('tenant.support');
        Route::post('/support', [SupportController::class, 'store'])->name('tenant.support.store');
        Route::get('/community', [CommunityController::class, 'index'])->name('tenant.community');
    });
});

// Public routes (accessible without authentication)
Route::get('/', function () {
    return redirect()->route('tenant.login', ['tenant' => request()->route('tenant')]);
})->name('tenant.home');
