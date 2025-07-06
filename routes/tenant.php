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
use App\Http\Controllers\Tenant\AccountingController;
use App\Http\Controllers\Tenant\InventoryController;
use App\Http\Controllers\Tenant\CrmController;
use App\Http\Controllers\Tenant\PosController;
use App\Http\Controllers\Tenant\PayrollController;
use App\Http\Controllers\Tenant\ReportsController;
use App\Http\Controllers\Tenant\DocumentsController;
use App\Http\Controllers\Tenant\ActivityController;
use App\Http\Controllers\Tenant\ProductCategoryController;
use App\Http\Controllers\Tenant\SettingsController;
use App\Http\Controllers\Tenant\VendorController;
use App\Http\Controllers\Tenant\UnitController;

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

        // Accounting Module
        Route::prefix('accounting')->name('tenant.accounting.')->group(function () {
            Route::get('/', [AccountingController::class, 'index'])->name('index');

            // Invoices (moved from root level)
            Route::prefix('invoices')->name('invoices.')->group(function () {
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

            // Voucher Types
            Route::prefix('voucher-types')->name('voucher-types.')->group(function () {
                Route::get('/', [VoucherTypeController::class, 'index'])->name('index');
                Route::get('/create', [VoucherTypeController::class, 'create'])->name('create');
                Route::post('/', [VoucherTypeController::class, 'store'])->name('store');
                Route::get('/{voucherType}', [VoucherTypeController::class, 'show'])->name('show');
                Route::get('/{voucherType}/edit', [VoucherTypeController::class, 'edit'])->name('edit');
                Route::put('/{voucherType}', [VoucherTypeController::class, 'update'])->name('update');
                Route::delete('/{voucherType}', [VoucherTypeController::class, 'destroy'])->name('destroy');
                Route::post('/{voucherType}/reset-numbering', [VoucherTypeController::class, 'resetNumbering'])->name('reset-numbering');
            });

            // Vouchers
            Route::prefix('vouchers')->name('vouchers.')->group(function () {
                Route::get('/', [VoucherController::class, 'index'])->name('index');
                Route::get('/create', [VoucherController::class, 'create'])->name('create');
                Route::post('/', [VoucherController::class, 'store'])->name('store');
                Route::get('/{voucher}', [VoucherController::class, 'show'])->name('show');
                Route::get('/{voucher}/edit', [VoucherController::class, 'edit'])->name('edit');
                Route::put('/{voucher}', [VoucherController::class, 'update'])->name('update');
                Route::delete('/{voucher}', [VoucherController::class, 'destroy'])->name('destroy');
                Route::post('/{voucher}/approve', [VoucherController::class, 'approve'])->name('approve');
                Route::post('/{voucher}/reject', [VoucherController::class, 'reject'])->name('reject');
                Route::post('/{voucher}/cancel', [VoucherController::class, 'cancel'])->name('cancel');
                Route::get('/{voucher}/pdf', [VoucherController::class, 'generatePdf'])->name('pdf');
                Route::post('/{voucher}/duplicate', [VoucherController::class, 'duplicate'])->name('duplicate');
            });

            // Expenses (add if not exists)
            Route::prefix('expenses')->name('expenses.')->group(function () {
                Route::get('/', [ExpenseController::class, 'index'])->name('index');
                Route::get('/create', [ExpenseController::class, 'create'])->name('create');
                Route::post('/', [ExpenseController::class, 'store'])->name('store');
                Route::get('/{expense}', [ExpenseController::class, 'show'])->name('show');
                Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])->name('edit');
                Route::put('/{expense}', [ExpenseController::class, 'update'])->name('update');
                Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('destroy');
            });

            // Payments (add if not exists)
            Route::prefix('payments')->name('payments.')->group(function () {
                Route::get('/', [PaymentController::class, 'index'])->name('index');
                Route::get('/create', [PaymentController::class, 'create'])->name('create');
                Route::post('/', [PaymentController::class, 'store'])->name('store');
                Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
                Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
                Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
                Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
            });

            // Chart of Accounts (add if not exists)
            Route::prefix('chart-of-accounts')->name('chart-of-accounts.')->group(function () {
                Route::get('/', [ChartOfAccountsController::class, 'index'])->name('index');
                Route::get('/create', [ChartOfAccountsController::class, 'create'])->name('create');
                Route::post('/', [ChartOfAccountsController::class, 'store'])->name('store');
                Route::get('/{account}', [ChartOfAccountsController::class, 'show'])->name('show');
                Route::get('/{account}/edit', [ChartOfAccountsController::class, 'edit'])->name('edit');
                Route::put('/{account}', [ChartOfAccountsController::class, 'update'])->name('update');
                Route::delete('/{account}', [ChartOfAccountsController::class, 'destroy'])->name('destroy');
            });
        });

        // Inventory Module
        Route::prefix('inventory')->name('tenant.inventory.')->group(function () {
            Route::get('/', [InventoryController::class, 'index'])->name('index');

            // Products
            Route::prefix('products')->name('products.')->group(function () {
                Route::get('/', [ProductController::class, 'index'])->name('index');
                Route::get('/create', [ProductController::class, 'create'])->name('create');
                Route::post('/', [ProductController::class, 'store'])->name('store');
                Route::get('/{product}', [ProductController::class, 'show'])->name('show');
                Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
                Route::put('/{product}', [ProductController::class, 'update'])->name('update');
                Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
            });

                // Units
                Route::prefix('units')->name('units.')->group(function () {
                    Route::get('/', [UnitController::class, 'index'])->name('index');
                    Route::get('/create', [UnitController::class, 'create'])->name('create');
                    Route::post('/', [UnitController::class, 'store'])->name('store');
                    Route::get('/{unit}', [UnitController::class, 'show'])->name('show');
                    Route::get('/{unit}/edit', [UnitController::class, 'edit'])->name('edit');
                    Route::put('/{unit}', [UnitController::class, 'update'])->name('update');
                    Route::delete('/{unit}', [UnitController::class, 'destroy'])->name('destroy');
                    Route::patch('/{unit}/toggle-status', [UnitController::class, 'toggleStatus'])->name('toggle-status');
                });

                // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [ProductCategoryController::class, 'index'])->name('index');
        Route::get('/create', [ProductCategoryController::class, 'create'])->name('create');
        Route::post('/', [ProductCategoryController::class, 'store'])->name('store');
        Route::get('/{category}', [ProductCategoryController::class, 'show'])->name('show');
        Route::get('/{category}/edit', [ProductCategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [ProductCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [ProductCategoryController::class, 'destroy'])->name('destroy');
        Route::patch('/{category}/toggle-status', [ProductCategoryController::class, 'toggleStatus'])->name('toggle-status');
    });



        });

        // CRM Module
        Route::prefix('crm')->name('tenant.crm.')->group(function () {
            Route::get('/', [CrmController::class, 'index'])->name('index');

            // Customers
            Route::prefix('customers')->name('customers.')->group(function () {
                Route::get('/', [CustomerController::class, 'index'])->name('index');
                Route::get('/create', [CustomerController::class, 'create'])->name('create');
                Route::post('/', [CustomerController::class, 'store'])->name('store');
                Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
                Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
                Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
                Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
            });


               // Vendor
               Route::prefix('vendors')->name('vendors.')->group(function () {
                Route::get('/', [VendorController::class, 'index'])->name('index');
                Route::get('/create', [VendorController::class, 'create'])->name('create');
                Route::post('/', [VendorController::class, 'store'])->name('store');
                Route::get('/{vendor}', [VendorController::class, 'show'])->name('show');
                Route::get('/{vendor}/edit', [VendorController::class, 'edit'])->name('edit');
                Route::put('/{vendor}', [VendorController::class, 'update'])->name('update');
                Route::delete('/{vendor}', [VendorController::class, 'destroy'])->name('destroy');
            });


        });

        // POS Module
        Route::prefix('pos')->name('tenant.pos.')->group(function () {
            Route::get('/', [PosController::class, 'index'])->name('index');
        });

        // Payroll Module
        Route::prefix('payroll')->name('tenant.payroll.')->group(function () {
            Route::get('/', [PayrollController::class, 'index'])->name('index');
        });

        // Reports Module
        Route::prefix('reports')->name('tenant.reports.')->group(function () {
            Route::get('/', [ReportsController::class, 'index'])->name('index');
            Route::get('/profit-loss', [ReportsController::class, 'profitLoss'])->name('profit-loss');
            Route::get('/balance-sheet', [ReportsController::class, 'balanceSheet'])->name('balance-sheet');
            Route::get('/trial-balance', [ReportsController::class, 'trialBalance'])->name('trial-balance');
            Route::get('/cash-flow', [ReportsController::class, 'cashFlow'])->name('cash-flow');
            Route::get('/voucher-register', [ReportsController::class, 'voucherRegister'])->name('voucher-register');
            Route::get('/account-ledger', [ReportsController::class, 'accountLedger'])->name('account-ledger');
        });

        // Documents Module
        Route::prefix('documents')->name('tenant.documents.')->group(function () {
            Route::get('/', [DocumentsController::class, 'index'])->name('index');
        });

        // Activity Log Module
        Route::prefix('activity')->name('tenant.activity.')->group(function () {
            Route::get('/', [ActivityController::class, 'index'])->name('index');
        });

        // Settings Module
        Route::prefix('settings')->name('tenant.settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
        });

        // Help and Support (keeping these at root level for easy access)
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

// Public invoice view (for customers)
Route::get('/invoice/{invoice}/view', [InvoiceController::class, 'publicView'])
    ->name('tenant.invoice.public');

// Public payment page (for customers)
Route::get('/invoice/{invoice}/pay', [InvoiceController::class, 'paymentPage'])
    ->name('tenant.invoice.payment');
Route::post('/invoice/{invoice}/pay', [InvoiceController::class, 'processPayment'])
    ->name('tenant.invoice.payment.process');
