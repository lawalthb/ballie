<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\AuthController;
use App\Http\Controllers\Tenant\OnboardingController;
use App\Http\Controllers\Tenant\InvoiceController;
use App\Http\Controllers\Tenant\AccountingController;
use App\Http\Controllers\Tenant\InventoryController;
use App\Http\Controllers\Tenant\CRMController;
use App\Http\Controllers\Tenant\POSController;
use App\Http\Controllers\Tenant\PayrollController;
use App\Http\Controllers\Tenant\ReportController;
use App\Http\Controllers\Tenant\SettingsController;
use App\Models\Tenant;

// Route model binding for tenant
Route::bind('tenant', function ($value) {
    return Tenant::where('slug', $value)->firstOrFail();
});

// Authentication routes for tenants
//require __DIR__.'/auth.php';

// All tenant routes require authentication
Route::middleware(['auth'])->group(function () {

    // Root route - check onboarding status and redirect accordingly
    Route::get('/', function () {
        $user = auth()->user();
        $tenant = app('currentTenant');

        if (!$tenant) {
            return redirect()->route('home');
        }

        // If onboarding is not completed, redirect to onboarding
        if (!$tenant->onboarding_completed) {
            return redirect()->route('onboarding.index');
        }

        // If onboarding is completed, redirect to dashboard
        return redirect()->route('tenant.dashboard');
    })->name('tenant.index');

    // Onboarding routes (accessible without onboarding completion)
    Route::prefix('onboarding')->name('onboarding.')->group(function () {
        Route::get('/', [OnboardingController::class, 'index'])->name('index');
        Route::post('/complete', [OnboardingController::class, 'complete'])->name('complete');
        Route::get('/{step}', [OnboardingController::class, 'showStep'])->name('step');
        Route::post('/{step}', [OnboardingController::class, 'saveStep'])->name('save-step');
    });

    // Protected tenant routes (require completed onboarding)
    Route::middleware(['onboarding.completed'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');

        // Accounting Routes
        Route::prefix('accounting')->name('accounting.')->group(function () {
            Route::get('/', [AccountingController::class, 'index'])->name('index');
            Route::get('/chart-of-accounts', [AccountingController::class, 'chartOfAccounts'])->name('chart-of-accounts');
            Route::get('/journal-entries', [AccountingController::class, 'journalEntries'])->name('journal-entries');
            Route::get('/trial-balance', [AccountingController::class, 'trialBalance'])->name('trial-balance');
            Route::get('/financial-statements', [AccountingController::class, 'financialStatements'])->name('financial-statements');
        });

        // Invoice Routes
        Route::prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::get('/create', [InvoiceController::class, 'create'])->name('create');
            Route::post('/', [InvoiceController::class, 'store'])->name('store');
            Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
            Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
            Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');
            Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
            Route::post('/{invoice}/send', [InvoiceController::class, 'send'])->name('send');
            Route::post('/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('mark-paid');
            Route::get('/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('pdf');
        });

        // Inventory Routes
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/', [InventoryController::class, 'index'])->name('index');
            Route::get('/products', [InventoryController::class, 'products'])->name('products');
            Route::get('/categories', [InventoryController::class, 'categories'])->name('categories');
            Route::get('/suppliers', [InventoryController::class, 'suppliers'])->name('suppliers');
            Route::get('/stock-movements', [InventoryController::class, 'stockMovements'])->name('stock-movements');
            Route::get('/low-stock', [InventoryController::class, 'lowStock'])->name('low-stock');
        });

        // CRM Routes
        Route::prefix('crm')->name('crm.')->group(function () {
            Route::get('/', [CRMController::class, 'index'])->name('index');
            Route::get('/customers', [CRMController::class, 'customers'])->name('customers');
            Route::get('/leads', [CRMController::class, 'leads'])->name('leads');
            Route::get('/sales-pipeline', [CRMController::class, 'salesPipeline'])->name('sales-pipeline');
            Route::get('/contacts', [CRMController::class, 'contacts'])->name('contacts');
            Route::get('/activities', [CRMController::class, 'activities'])->name('activities');
        });

        // POS Routes
        Route::prefix('pos')->name('pos.')->group(function () {
            Route::get('/', [POSController::class, 'index'])->name('index');
            Route::get('/sales', [POSController::class, 'sales'])->name('sales');
            Route::get('/receipts', [POSController::class, 'receipts'])->name('receipts');
            Route::post('/process-sale', [POSController::class, 'processSale'])->name('process-sale');
        });

        // Payroll Routes
        Route::prefix('payroll')->name('payroll.')->group(function () {
            Route::get('/', [PayrollController::class, 'index'])->name('index');
            Route::get('/employees', [PayrollController::class, 'employees'])->name('employees');
            Route::get('/payslips', [PayrollController::class, 'payslips'])->name('payslips');
            Route::get('/tax-reports', [PayrollController::class, 'taxReports'])->name('tax-reports');
            Route::get('/pension-reports', [PayrollController::class, 'pensionReports'])->name('pension-reports');
        });

        // Reports Routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
            Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
            Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
            Route::get('/customer', [ReportController::class, 'customer'])->name('customer');
            Route::get('/tax', [ReportController::class, 'tax'])->name('tax');
        });

        // Settings Routes
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::get('/company', [SettingsController::class, 'company'])->name('company');
            Route::get('/users', [SettingsController::class, 'users'])->name('users');
            Route::get('/roles', [SettingsController::class, 'roles'])->name('roles');
            Route::get('/integrations', [SettingsController::class, 'integrations'])->name('integrations');
            Route::get('/billing', [SettingsController::class, 'billing'])->name('billing');
            Route::get('/security', [SettingsController::class, 'security'])->name('security');
        });
    });
});
