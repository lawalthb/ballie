<?php

namespace App\Helpers;

use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TenantHelper
{
    /**
     * Get the current tenant
     */
    public static function current(): ?Tenant
    {
        return app('currentTenant');
    }

    /**
     * Get tenant-specific cache key
     */
    public static function cacheKey(string $key): string
    {
        $tenant = self::current();
        return $tenant ? "tenant_{$tenant->id}_{$key}" : $key;
    }

    /**
     * Format currency for the tenant
     */
    public static function formatCurrency(float $amount, string $currency = 'NGN'): string
    {
        $tenant = self::current();
        $currency = $tenant->currency ?? $currency;

        $formatter = new \NumberFormatter('en_NG', \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, $currency);
    }

    /**
     * Format number with Nigerian locale
     */
    public static function formatNumber(float $number): string
    {
        return number_format($number, 2, '.', ',');
    }

    /**
     * Get tenant's business hours
     */
    public static function getBusinessHours(): array
    {
        $tenant = self::current();

        return [
            'start' => $tenant->business_hours_start ?? '09:00',
            'end' => $tenant->business_hours_end ?? '17:00',
            'timezone' => $tenant->timezone ?? 'Africa/Lagos',
            'working_days' => $tenant->working_days ?? [1, 2, 3, 4, 5] // Mon-Fri
        ];
    }

    /**
     * Check if current time is within business hours
     */
    public static function isBusinessHours(): bool
    {
        $hours = self::getBusinessHours();
        $now = now($hours['timezone']);

        // Check if today is a working day
        if (!in_array($now->dayOfWeek, $hours['working_days'])) {
            return false;
        }

        $start = $now->copy()->setTimeFromTimeString($hours['start']);
        $end = $now->copy()->setTimeFromTimeString($hours['end']);

        return $now->between($start, $end);
    }

    /**
     * Generate invoice number
     */
    public static function generateInvoiceNumber(): string
    {
        $tenant = self::current();
        $prefix = $tenant->invoice_prefix ?? 'INV';
        $year = date('Y');
        $month = date('m');

        // Get the last invoice number for this month
        $lastNumber = Cache::remember(
            self::cacheKey("last_invoice_number_{$year}_{$month}"),
            3600,
            function () use ($tenant, $year, $month) {
                return $tenant->invoices()
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->count();
            }
        );

        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$year}{$month}-{$nextNumber}";
    }

    /**
     * Generate quote number
     */
    public static function generateQuoteNumber(): string
    {
        $tenant = self::current();
        $prefix = $tenant->quote_prefix ?? 'QUO';
        $year = date('Y');
        $month = date('m');

        $lastNumber = Cache::remember(
            self::cacheKey("last_quote_number_{$year}_{$month}"),
            3600,
            function () use ($tenant, $year, $month) {
                return $tenant->quotes()
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->count();
            }
        );

        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$year}{$month}-{$nextNumber}";
    }

    /**
     * Get tenant's tax settings
     */
    public static function getTaxSettings(): array
    {
        $tenant = self::current();

        return [
            'vat_rate' => $tenant->vat_rate ?? 7.5, // Nigeria VAT rate
            'vat_number' => $tenant->vat_number,
            'tin' => $tenant->tin,
            'withholding_tax_rate' => $tenant->withholding_tax_rate ?? 5.0,
            'apply_vat' => $tenant->apply_vat ?? true,
            'apply_withholding_tax' => $tenant->apply_withholding_tax ?? false
        ];
    }

    /**
     * Calculate VAT amount
     */
    public static function calculateVAT(float $amount, bool $inclusive = false): array
    {
        $taxSettings = self::getTaxSettings();
        $vatRate = $taxSettings['vat_rate'] / 100;

        if ($inclusive) {
            $vatAmount = $amount - ($amount / (1 + $vatRate));
            $netAmount = $amount - $vatAmount;
        } else {
            $vatAmount = $amount * $vatRate;
            $netAmount = $amount;
        }

        return [
            'net_amount' => round($netAmount, 2),
            'vat_amount' => round($vatAmount, 2),
            'gross_amount' => round($netAmount + $vatAmount, 2),
            'vat_rate' => $taxSettings['vat_rate']
        ];
    }

    /**
     * Get tenant's notification preferences
     */
    public static function getNotificationPreferences(): array
    {
        $tenant = self::current();

        return [
            'email_notifications' => $tenant->email_notifications ?? true,
            'sms_notifications' => $tenant->sms_notifications ?? false,
            'invoice_reminders' => $tenant->invoice_reminders ?? true,
            'payment_confirmations' => $tenant->payment_confirmations ?? true,
            'low_stock_alerts' => $tenant->low_stock_alerts ?? true,
            'backup_notifications' => $tenant->backup_notifications ?? true
        ];
    }

    /**
     * Check if user has permission
     */
    public static function hasPermission(string $permission): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // Super admin has all permissions
        if ($user->role === 'owner') {
            return true;
        }

        // Check user permissions
        $permissions = $user->permissions ?? [];
        return in_array($permission, $permissions);
    }

    /**
     * Get user's role permissions
     */
    public static function getRolePermissions(string $role): array
    {
        $permissions = [
            'owner' => [
                'view_dashboard',
                'manage_users',
                'manage_settings',
                'manage_billing',
                'view_reports',
                'manage_invoices',
                'manage_customers',
                'manage_products',
                'manage_inventory',
                'manage_payments',
                'export_data',
                'backup_data'
            ],
            'admin' => [
                'view_dashboard',
                'manage_users',
                'view_reports',
                'manage_invoices',
                'manage_customers',
                'manage_products',
                'manage_inventory',
                'manage_payments',
                'export_data'
            ],
            'manager' => [
                'view_dashboard',
                'view_reports',
                'manage_invoices',
                'manage_customers',
                'manage_products',
                'manage_inventory',
                'manage_payments'
            ],
            'accountant' => [
                'view_dashboard',
                'view_reports',
                'manage_invoices',
                'manage_payments',
                'export_data'
            ],
            'sales' => [
                'view_dashboard',
                'manage_invoices',
                'manage_customers',
                'view_products'
            ],
            'employee' => [
                'view_dashboard',
                'view_invoices',
                'view_customers',
                'view_products'
            ]
        ];

        return $permissions[$role] ?? [];
    }

    /**
     * Generate secure random string
     */
    public static function generateSecureString(int $length = 32): string
    {
        return Str::random($length);
    }

    /**
     * Sanitize filename for storage
     */
    public static function sanitizeFilename(string $filename): string
    {
        // Remove special characters and spaces
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);

        // Remove multiple underscores
        $filename = preg_replace('/_+/', '_', $filename);

        // Trim underscores from start and end
        return trim($filename, '_');
    }

    /**
     * Get file storage path for tenant
     */
    public static function getStoragePath(string $type = 'general'): string
    {
        $tenant = self::current();
        $tenantId = $tenant ? $tenant->id : 'default';

        return "tenants/{$tenantId}/{$type}";
    }

    /**
     * Format phone number to Nigerian format
     */
    public static function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Handle different formats
        if (strlen($phone) === 11 && substr($phone, 0, 1) === '0') {
            // 0XXXXXXXXXX format
            return '+234' . substr($phone, 1);
        } elseif (strlen($phone) === 10) {
            // XXXXXXXXXX format
            return '+234' . $phone;
        } elseif (strlen($phone) === 13 && substr($phone, 0, 3) === '234') {
            // 234XXXXXXXXXX format
            return '+' . $phone;
        } elseif (strlen($phone) === 14 && substr($phone, 0, 4) === '+234') {
            // Already in correct format
            return $phone;
        }

        // Return original if can't format
        return $phone;
    }

    /**
     * Validate Nigerian phone number
     */
    public static function isValidPhoneNumber(string $phone): bool
    {
        $formatted = self::formatPhoneNumber($phone);
        return preg_match('/^\+234[789][01]\d{8}$/', $formatted);
    }

    /**
     * Get Nigerian states
     */
    public static function getNigerianStates(): array
    {
        return [
            'AB' => 'Abia',
            'AD' => 'Adamawa',
            'AK' => 'Akwa Ibom',
            'AN' => 'Anambra',
            'BA' => 'Bauchi',
            'BY' => 'Bayelsa',
            'BE' => 'Benue',
            'BO' => 'Borno',
            'CR' => 'Cross River',
            'DE' => 'Delta',
            'EB' => 'Ebonyi',
            'ED' => 'Edo',
            'EK' => 'Ekiti',
            'EN' => 'Enugu',
            'FC' => 'FCT - Abuja',
            'GO' => 'Gombe',
            'IM' => 'Imo',
            'JI' => 'Jigawa',
            'KD' => 'Kaduna',
            'KN' => 'Kano',
            'KT' => 'Katsina',
            'KE' => 'Kebbi',
            'KO' => 'Kogi',
            'KW' => 'Kwara',
            'LA' => 'Lagos',
            'NA' => 'Nasarawa',
            'NI' => 'Niger',
            'OG' => 'Ogun',
            'ON' => 'Ondo',
            'OS' => 'Osun',
            'OY' => 'Oyo',
            'PL' => 'Plateau',
            'RI' => 'Rivers',
            'SO' => 'Sokoto',
            'TA' => 'Taraba',
            'YO' => 'Yobe',
            'ZA' => 'Zamfara'
        ];
    }

    /**
     * Get business categories
     */
    public static function getBusinessCategories(): array
    {
        return [
            'retail' => 'Retail & E-commerce',
            'wholesale' => 'Wholesale & Distribution',
            'manufacturing' => 'Manufacturing',
            'services' => 'Professional Services',
            'consulting' => 'Consulting',
            'technology' => 'Technology & Software',
            'healthcare' => 'Healthcare',
            'education' => 'Education & Training',
            'hospitality' => 'Hospitality & Tourism',
            'construction' => 'Construction & Real Estate',
            'agriculture' => 'Agriculture & Food',
            'transportation' => 'Transportation & Logistics',
            'finance' => 'Financial Services',
            'media' => 'Media & Entertainment',
            'nonprofit' => 'Non-profit Organization',
            'other' => 'Other'
        ];
    }

    /**
     * Get payment terms options
     */
    public static function getPaymentTerms(): array
    {
        return [
            'immediate' => 'Due Immediately',
            'net_7' => 'Net 7 days',
            'net_15' => 'Net 15 days',
            'net_30' => 'Net 30 days',
            'net_45' => 'Net 45 days',
            'net_60' => 'Net 60 days',
            'net_90' => 'Net 90 days',
            'eom' => 'End of Month',
            'custom' => 'Custom Terms'
        ];
    }

    /**
     * Calculate due date based on payment terms
     */
    public static function calculateDueDate(string $terms, \DateTime $invoiceDate = null): \DateTime
    {
        $invoiceDate = $invoiceDate ?? new \DateTime();
        $dueDate = clone $invoiceDate;

        switch ($terms) {
            case 'immediate':
                break;
            case 'net_7':
                $dueDate->add(new \DateInterval('P7D'));
                break;
            case 'net_15':
                $dueDate->add(new \DateInterval('P15D'));
                break;
            case 'net_30':
                $dueDate->add(new \DateInterval('P30D'));
                break;
            case 'net_45':
                $dueDate->add(new \DateInterval('P45D'));
                break;
            case 'net_60':
                $dueDate->add(new \DateInterval('P60D'));
                break;
            case 'net_90':
                $dueDate->add(new \DateInterval('P90D'));
                break;
            case 'eom':
                $dueDate->modify('last day of this month');
                break;
            default:
                $dueDate->add(new \DateInterval('P30D'));
        }

        return $dueDate;
    }

    /**
     * Get invoice status color
     */
    public static function getInvoiceStatusColor(string $status): string
    {
        $colors = [
            'draft' => 'gray',
            'sent' => 'blue',
            'viewed' => 'indigo',
            'partial' => 'yellow',
            'paid' => 'green',
            'overdue' => 'red',
            'cancelled' => 'gray'
        ];

        return $colors[$status] ?? 'gray';
    }

    /**
     * Get product stock status
     */
    public static function getStockStatus(int $quantity, int $lowStockThreshold = 10): array
    {
        if ($quantity <= 0) {
            return ['status' => 'out_of_stock', 'color' => 'red', 'label' => 'Out of Stock'];
        } elseif ($quantity <= $lowStockThreshold) {
            return ['status' => 'low_stock', 'color' => 'yellow', 'label' => 'Low Stock'];
        } else {
            return ['status' => 'in_stock', 'color' => 'green', 'label' => 'In Stock'];
        }
    }

    /**
     * Generate barcode for product
     */
    public static function generateBarcode(): string
    {
        // Generate EAN-13 compatible barcode
        $prefix = '978'; // Book prefix, can be customized
        $tenant = self::current();
        $tenantCode = str_pad(substr($tenant->id, -4), 4, '0', STR_PAD_LEFT);
        $random = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);

        $code = $prefix . $tenantCode . $random;

        // Calculate check digit
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += $code[$i] * (($i % 2 === 0) ? 1 : 3);
        }
        $checkDigit = (10 - ($sum % 10)) % 10;

        return $code . $checkDigit;
    }

    /**
     * Log tenant activity
     */
    public static function logActivity(string $action, string $description, array $properties = []): void
    {
        $tenant = self::current();
        $user = Auth::user();

        if ($tenant && $user) {
            activity()
                ->performedOn($tenant)
                ->causedBy($user)
                ->withProperties($properties)
                ->log("{$action}: {$description}");
        }
    }

    /**
     * Get tenant subscription status
     */
    public static function getSubscriptionStatus(): array
    {
        $tenant = self::current();

        if (!$tenant) {
            return ['status' => 'unknown', 'active' => false];
        }

        $subscription = $tenant->subscription;

        if (!$subscription) {
            return ['status' => 'no_subscription', 'active' => false];
        }

        $now = now();
        $expiresAt = $subscription->expires_at;

        if ($subscription->status === 'cancelled') {
            return ['status' => 'cancelled', 'active' => false];
        }

        if ($subscription->status === 'suspended') {
            return ['status' => 'suspended', 'active' => false];
        }

        if ($expiresAt && $now->gt($expiresAt)) {
            return ['status' => 'expired', 'active' => false];
        }

        if ($expiresAt && $now->diffInDays($expiresAt) <= 7) {
            return ['status' => 'expiring_soon', 'active' => true];
        }

        return ['status' => 'active', 'active' => true];
    }

    /**
     * Check if feature is available for tenant
     */
    public static function hasFeature(string $feature): bool
    {
        $tenant = self::current();

        if (!$tenant) {
            return false;
        }

        $subscription = $tenant->subscription;

        if (!$subscription || !$subscription->isActive()) {
            // Basic features for free/trial accounts
            $basicFeatures = [
                'invoicing',
                'customers',
                'products',
                'basic_reports'
            ];

            return in_array($feature, $basicFeatures);
        }

        $plan = $subscription->plan;
        $planFeatures = $plan->features ?? [];

        return in_array($feature, $planFeatures);
    }

    /**
     * Get feature limits for tenant
     */
    public static function getFeatureLimits(): array
    {
        $tenant = self::current();

        if (!$tenant) {
            return [];
        }

        $subscription = $tenant->subscription;

        if (!$subscription || !$subscription->isActive()) {
            // Trial/free limits
            return [
                'invoices_per_month' => 10,
                'customers' => 50,
                'products' => 100,
                'users' => 2,
                'storage_mb' => 100
            ];
        }

        return $subscription->plan->limits ?? [];
    }

    /**
     * Check if limit is exceeded
     */
    public static function isLimitExceeded(string $feature): bool
    {
        $limits = self::getFeatureLimits();

        if (!isset($limits[$feature])) {
            return false;
        }

        $tenant = self::current();
        $limit = $limits[$feature];
        $current = 0;

        switch ($feature) {
            case 'invoices_per_month':
                $current = $tenant->invoices()
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
                break;

            case 'customers':
                $current = $tenant->customers()->count();
                break;

            case 'products':
                $current = $tenant->products()->count();
                break;

            case 'users':
                $current = $tenant->users()->count();
                break;

            case 'storage_mb':
                // Calculate storage usage
                $current = 0; // Implement storage calculation
                break;
        }

        return $current >= $limit;
    }

    /**
     * Get tenant's dashboard statistics
     */
    public static function getDashboardStats(): array
    {
        $tenant = self::current();

        if (!$tenant) {
            return [];
        }

        $cacheKey = self::cacheKey('dashboard_stats');

        return Cache::remember($cacheKey, 300, function () use ($tenant) {
            $currentMonth = now()->startOfMonth();
            $lastMonth = now()->subMonth()->startOfMonth();

            // Revenue stats
            $currentRevenue = $tenant->invoices()
                ->where('status', 'paid')
                ->where('created_at', '>=', $currentMonth)
                ->sum('total');

            $lastMonthRevenue = $tenant->invoices()
                ->where('status', 'paid')
                ->whereBetween('created_at', [$lastMonth, $currentMonth])
                ->sum('total');

            // Invoice stats
            $totalInvoices = $tenant->invoices()->count();
            $paidInvoices = $tenant->invoices()->where('status', 'paid')->count();
            $overdueInvoices = $tenant->invoices()
                ->where('status', 'sent')
                ->where('due_date', '<', now())
                ->count();

            // Customer stats
            $totalCustomers = $tenant->customers()->count();
            $activeCustomers = $tenant->customers()
                ->where('status', 'active')
                ->count();

            // Product stats
            $totalProducts = $tenant->products()->count();
            $lowStockProducts = $tenant->products()
                ->whereColumn('quantity', '<=', 'low_stock_threshold')
                ->count();

            return [
                'revenue' => [
                    'current' => $currentRevenue,
                    'last_month' => $lastMonthRevenue,
                    'change_percent' => $lastMonthRevenue > 0
                        ? (($currentRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
                        : 0
                ],
                'invoices' => [
                    'total' => $totalInvoices,
                    'paid' => $paidInvoices,
                    'overdue' => $overdueInvoices,
                    'payment_rate' => $totalInvoices > 0
                        ? ($paidInvoices / $totalInvoices) * 100
                        : 0
                ],
                'customers' => [
                    'total' => $totalCustomers,
                    'active' => $activeCustomers,
                    'active_rate' => $totalCustomers > 0
                        ? ($activeCustomers / $totalCustomers) * 100
                        : 0
                ],
                'products' => [
                    'total' => $totalProducts,
                    'low_stock' => $lowStockProducts,
                    'stock_health' => $totalProducts > 0
                        ? (($totalProducts - $lowStockProducts) / $totalProducts) * 100
                        : 100
                ]
            ];
        });
    }

    /**
     * Clear tenant cache
     */
    public static function clearCache(string $pattern = null): void
    {
        $tenant = self::current();

        if (!$tenant) {
            return;
        }

        if ($pattern) {
            Cache::forget(self::cacheKey($pattern));
        } else {
            // Clear all tenant-related cache
            $keys = [
                'dashboard_stats',
                'user_permissions',
                'settings',
                'subscription_status'
            ];

            foreach ($keys as $key) {
                Cache::forget(self::cacheKey($key));
            }
        }
    }

    /**
     * Get tenant's email signature
     */
    public static function getEmailSignature(): string
    {
        $tenant = self::current();

        if (!$tenant) {
            return '';
        }

        $signature = $tenant->email_signature;

        if (!$signature) {
            // Generate default signature
            $signature = "Best regards,\n\n";
            $signature .= $tenant->name . "\n";

            if ($tenant->phone) {
                $signature .= "Phone: " . $tenant->phone . "\n";
            }

            if ($tenant->email) {
                $signature .= "Email: " . $tenant->email . "\n";
            }

            if ($tenant->website) {
                $signature .= "Website: " . $tenant->website . "\n";
            }
        }

        return $signature;
    }

    /**
     * Generate PDF filename
     */
    public static function generatePdfFilename(string $type, string $number): string
    {
        $tenant = self::current();
        $tenantName = $tenant ? Str::slug($tenant->name) : 'document';
        $date = date('Y-m-d');

        return "{$tenantName}-{$type}-{$number}-{$date}.pdf";
    }

    /**
     * Get supported currencies
     */
    public static function getSupportedCurrencies(): array
    {
        return [
            'NGN' => ['name' => 'Nigerian Naira', 'symbol' => '₦'],
            'USD' => ['name' => 'US Dollar', 'symbol' => '$'],
            'EUR' => ['name' => 'Euro', 'symbol' => '€'],
            'GBP' => ['name' => 'British Pound', 'symbol' => '£'],
            'GHS' => ['name' => 'Ghanaian Cedi', 'symbol' => '₵'],
            'KES' => ['name' => 'Kenyan Shilling', 'symbol' => 'KSh'],
            'ZAR' => ['name' => 'South African Rand', 'symbol' => 'R']
        ];
    }

    /**
     * Convert currency
     */
    public static function convertCurrency(float $amount, string $from, string $to): float
    {
        if ($from === $to) {
            return $amount;
        }

        // In a real application, you would use a currency conversion API
        // For now, return the original amount
        return $amount;
    }

    /**
     * Get tenant's backup settings
     */
    public static function getBackupSettings(): array
    {
        $tenant = self::current();

        return [
            'auto_backup' => $tenant->auto_backup ?? true,
            'backup_frequency' => $tenant->backup_frequency ?? 'daily',
            'backup_retention' => $tenant->backup_retention ?? 30,
            'include_files' => $tenant->backup_include_files ?? true,
            'last_backup' => $tenant->last_backup_at,
            'next_backup' => $tenant->next_backup_at
        ];
    }

    /**
     * Check if backup is due
     */
    public static function isBackupDue(): bool
    {
        $settings = self::getBackupSettings();

        if (!$settings['auto_backup']) {
            return false;
        }

        $lastBackup = $settings['last_backup'];

        if (!$lastBackup) {
            return true;
        }

        $frequency = $settings['backup_frequency'];
        $now = now();

        switch ($frequency) {
            case 'hourly':
                return $now->diffInHours($lastBackup) >= 1;
            case 'daily':
                return $now->diffInDays($lastBackup) >= 1;
            case 'weekly':
                return $now->diffInWeeks($lastBackup) >= 1;
            case 'monthly':
                return $now->diffInMonths($lastBackup) >= 1;
            default:
                return false;
        }
    }

    /**
     * Get system health status
     */
    public static function getSystemHealth(): array
    {
        $tenant = self::current();

        if (!$tenant) {
            return ['status' => 'unknown', 'checks' => []];
        }

        $checks = [];
        $overallStatus = 'healthy';

        // Database connectivity
        try {
            \DB::connection()->getPdo();
            $checks['database'] = ['status' => 'healthy', 'message' => 'Database connection OK'];
        } catch (\Exception $e) {
            $checks['database'] = ['status' => 'error', 'message' => 'Database connection failed'];
            $overallStatus = 'error';
        }

        // Storage accessibility
        try {
            \Storage::disk('local')->exists('test');
            $checks['storage'] = ['status' => 'healthy', 'message' => 'Storage accessible'];
        } catch (\Exception $e) {
            $checks['storage'] = ['status' => 'error', 'message' => 'Storage not accessible'];
            $overallStatus = 'error';
        }

        // Subscription status
        $subscription = self::getSubscriptionStatus();
        if ($subscription['active']) {
            $checks['subscription'] = ['status' => 'healthy', 'message' => 'Subscription active'];
        } else {
            $checks['subscription'] = ['status' => 'warning', 'message' => 'Subscription inactive'];
            if ($overallStatus === 'healthy') {
                $overallStatus = 'warning';
            }
        }

        // Backup status
        if (self::isBackupDue()) {
            $checks['backup'] = ['status' => 'warning', 'message' => 'Backup overdue'];
            if ($overallStatus === 'healthy') {
                $overallStatus = 'warning';
            }
        } else {
            $checks['backup'] = ['status' => 'healthy', 'message' => 'Backup up to date'];
        }

        return [
            'status' => $overallStatus,
            'checks' => $checks,
            'last_checked' => now()->toISOString()
        ];
    }
}

if (!function_exists('current_tenant')) {
    /**
     * Get the current tenant
     */
    function current_tenant(): ?\App\Models\Tenant
    {
        return app('current_tenant');
    }
}

if (!function_exists('tenant_route')) {
    /**
     * Generate a tenant-aware route
     */
    function tenant_route(string $name, array $parameters = [], bool $absolute = true): string
    {
        $tenant = current_tenant();
        if ($tenant && !isset($parameters['tenant'])) {
            $parameters['tenant'] = $tenant->slug;
        }

        return route($name, $parameters, $absolute);
    }
}
