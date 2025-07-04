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
// Route::middleware(['guest'])->group(function () {
//     Route::get('/login', [AuthController::class, 'showLoginForm'])->name('tenant.login');
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('tenant.register');
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('tenant.password.request');
//     Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('tenant.password.email');
//     Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('tenant.password.reset');
//     Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('tenant.password.update');
// });

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

        // Customers
        Route::prefix('customers')->name('tenant.customers.')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::get('/create', [CustomerController::class, 'create'])->name('create');
            Route::post('/', [CustomerController::class, 'store'])->name('store');
            Route::get('/export', [CustomerController::class, 'export'])->name('export');
            Route::get('/search', [CustomerController::class, 'search'])->name('search');
            Route::get('/stats', [CustomerController::class, 'getStats'])->name('stats');
            Route::get('/data', [CustomerController::class, 'getData'])->name('data');
            Route::post('/bulk-action', [CustomerController::class, 'bulkAction'])->name('bulk-action');
            Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
            Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
            Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
            Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
            Route::patch('/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('toggle-status');
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
            Route::get('/export', [InvoiceController::class, 'export'])->name('export');
            Route::get('/stats', [InvoiceController::class, 'getStats'])->name('stats');
            Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
            Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
            Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');
            Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
            Route::get('/{invoice}/pdf', [InvoiceController::class, 'generatePdf'])->name('pdf');
            Route::post('/{invoice}/send', [InvoiceController::class, 'sendToCustomer'])->name('send');
            Route::post('/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('mark-paid');
            Route::post('/{invoice}/duplicate', [InvoiceController::class, 'duplicate'])->name('duplicate');
        });

        // Payments & Receipts
        // Route::prefix('payments')->name('tenant.payments.')->group(function () {
        //     Route::get('/', [PaymentController::class, 'index'])->name('index');
        //     Route::get('/create', [PaymentController::class, 'create'])->name('create');
        //     Route::post('/', [PaymentController::class, 'store'])->name('store');
        //     Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        //     Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
        //     Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
        //     Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
        //     Route::get('/{payment}/receipt', [PaymentController::class, 'generateReceipt'])->name('receipt');
        // });

        // Expenses
        // Route::prefix('expenses')->name('tenant.expenses.')->group(function () {
        //     Route::get('/', [ExpenseController::class, 'index'])->name('index');
        //     Route::get('/create', [ExpenseController::class, 'create'])->name('create');
        //     Route::post('/', [ExpenseController::class, 'store'])->name('store');
        //     Route::get('/categories', [ExpenseController::class, 'categories'])->name('categories');
        //     Route::get('/{expense}', [ExpenseController::class, 'show'])->name('show');
        //     Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])->name('edit');
        //     Route::put('/{expense}', [ExpenseController::class, 'update'])->name('update');
        //     Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('destroy');
        // });

        // Inventory Management
        // Route::prefix('inventory')->name('tenant.inventory.')->group(function () {
        //     Route::get('/', [InventoryController::class, 'index'])->name('index');
        //     Route::get('/low-stock', [InventoryController::class, 'lowStock'])->name('low-stock');
        //     Route::get('/adjustments', [InventoryController::class, 'adjustments'])->name('adjustments');
        //     Route::post('/adjust', [InventoryController::class, 'adjust'])->name('adjust');
        //     Route::get('/movements', [InventoryController::class, 'movements'])->name('movements');
        // });

        // Reports
        // Route::prefix('reports')->name('tenant.reports.')->group(function () {
        //     Route::get('/', [ReportController::class, 'index'])->name('index');

        //     // Financial Reports
        //     Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');
        //     Route::get('/balance-sheet', [ReportController::class, 'balanceSheet'])->name('balance-sheet');
        //     Route::get('/cash-flow', [ReportController::class, 'cashFlow'])->name('cash-flow');
        //     Route::get('/trial-balance', [ReportController::class, 'trialBalance'])->name('trial-balance');

        //     // Sales Reports
        //     Route::get('/sales-summary', [ReportController::class, 'salesSummary'])->name('sales-summary');
        //     Route::get('/sales-by-customer', [ReportController::class, 'salesByCustomer'])->name('sales-by-customer');
        //     Route::get('/sales-by-product', [ReportController::class, 'salesByProduct'])->name('sales-by-product');

        //     // Tax Reports
        //     Route::get('/vat-report', [ReportController::class, 'vatReport'])->name('vat-report');
        //     Route::get('/tax-summary', [ReportController::class, 'taxSummary'])->name('tax-summary');

        //     // Custom Reports
        //     Route::get('/custom', [ReportController::class, 'custom'])->name('custom');
        //     Route::post('/generate', [ReportController::class, 'generate'])->name('generate');
        // });

        // // Settings
        // Route::prefix('settings')->name('tenant.settings.')->group(function () {
        //     Route::get('/', [SettingsController::class, 'index'])->name('index');

        //     // Business Settings
        //     Route::get('/business', [SettingsController::class, 'business'])->name('business');
        //     Route::put('/business', [SettingsController::class, 'updateBusiness'])->name('business.update');

        //     // Account Settings
        //     Route::get('/account', [SettingsController::class, 'account'])->name('account');
        //     Route::put('/account', [SettingsController::class, 'updateAccount'])->name('account.update');

        //     // Tax Settings
        //     Route::get('/taxes', [SettingsController::class, 'taxes'])->name('taxes');
        //     Route::put('/taxes', [SettingsController::class, 'updateTaxes'])->name('taxes.update');

        //     // Payment Settings
        //     Route::get('/payments', [SettingsController::class, 'payments'])->name('payments');
        //     Route::put('/payments', [SettingsController::class, 'updatePayments'])->name('payments.update');

        //     // Invoice Settings
        //     Route::get('/invoices', [SettingsController::class, 'invoices'])->name('invoices');
        //     Route::put('/invoices', [SettingsController::class, 'updateInvoices'])->name('invoices.update');

        //     // Email Settings
        //     Route::get('/email', [SettingsController::class, 'email'])->name('email');
        //     Route::put('/email', [SettingsController::class, 'updateEmail'])->name('email.update');
        //     Route::post('/email/test', [SettingsController::class, 'testEmail'])->name('email.test');

        //     // Backup & Export
        //     Route::get('/backup', [SettingsController::class, 'backup'])->name('backup');
        //     Route::post('/backup/create', [SettingsController::class, 'createBackup'])->name('backup.create');
        //     Route::get('/export', [SettingsController::class, 'export'])->name('export');
        //     Route::post('/export/data', [SettingsController::class, 'exportData'])->name('export.data');
        // });

        // // Chart of Accounts
        // Route::prefix('accounts')->name('tenant.accounts.')->group(function () {
        //     Route::get('/', [AccountController::class, 'index'])->name('index');
        //     Route::get('/create', [AccountController::class, 'create'])->name('create');
        //     Route::post('/', [AccountController::class, 'store'])->name('store');
        //     Route::get('/{account}', [AccountController::class, 'show'])->name('show');
        //     Route::get('/{account}/edit', [AccountController::class, 'edit'])->name('edit');
        //     Route::put('/{account}', [AccountController::class, 'update'])->name('update');
        //     Route::delete('/{account}', [AccountController::class, 'destroy'])->name('destroy');
        //     Route::get('/{account}/ledger', [AccountController::class, 'ledger'])->name('ledger');
        // });

        // // Journal Entries
        // Route::prefix('journal')->name('tenant.journal.')->group(function () {
        //     Route::get('/', [JournalController::class, 'index'])->name('index');
        //     Route::get('/create', [JournalController::class, 'create'])->name('create');
        //     Route::post('/', [JournalController::class, 'store'])->name('store');
        //     Route::get('/{entry}', [JournalController::class, 'show'])->name('show');
        //     Route::get('/{entry}/edit', [JournalController::class, 'edit'])->name('edit');
        //     Route::put('/{entry}', [JournalController::class, 'update'])->name('update');
        //     Route::delete('/{entry}', [JournalController::class, 'destroy'])->name('destroy');
        //     Route::post('/{entry}/post', [JournalController::class, 'post'])->name('post');
        // });

        // Help and Support
        Route::get('/help/videos', [HelpController::class, 'videos'])->name('tenant.help.videos');
        Route::get('/help/articles', [HelpController::class, 'articles'])->name('tenant.help.articles');
        Route::get('/help/search', [HelpController::class, 'search'])->name('tenant.help.search');
        Route::get('/support', [SupportController::class, 'index'])->name('tenant.support');
        Route::post('/support', [SupportController::class, 'store'])->name('tenant.support.store');
        Route::get('/support/{ticket}', [SupportController::class, 'show'])->name('tenant.support.show');
        Route::post('/support/{ticket}/reply', [SupportController::class, 'reply'])->name('tenant.support.reply');
        Route::get('/community', [CommunityController::class, 'index'])->name('tenant.community');

        // API Routes for AJAX calls
        Route::prefix('api')->name('tenant.api.')->group(function () {
            Route::get('/dashboard-stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
            Route::get('/recent-activities', [DashboardController::class, 'getRecentActivities'])->name('recent-activities');
        //     Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
        //     Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        //     Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    });

        // Webhooks (for external integrations)
        // Route::prefix('webhooks')->name('tenant.webhooks.')->group(function () {
        //     Route::post('/payment-received', [WebhookController::class, 'paymentReceived'])->name('payment-received');
        //     Route::post('/invoice-paid', [WebhookController::class, 'invoicePaid'])->name('invoice-paid');
        // });
    });
});

// Public routes (accessible without authentication)
Route::get('/', function () {
    return redirect()->route('tenant.login', ['tenant' => request()->route('tenant')]);
})->name('tenant.home');

// Public invoice view (for customers)
Route::get('/invoice/{invoice}/view', [InvoiceController::class, 'publicView'])
    ->name('tenant.invoice.public');

// Public payment page (for customers)
Route::get('/invoice/{invoice}/pay', [InvoiceController::class, 'paymentPage'])
    ->name('tenant.invoice.payment');
Route::post('/invoice/{invoice}/pay', [InvoiceController::class, 'processPayment'])
    ->name('tenant.invoice.payment.process');
