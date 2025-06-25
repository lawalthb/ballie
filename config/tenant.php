<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tenant Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for multi-tenant functionality
    |
    */

    'default_currency' => 'NGN',
    'default_timezone' => 'Africa/Lagos',
    'default_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Subscription Plans
    |--------------------------------------------------------------------------
    */
    'plans' => [
        'starter' => [
            'name' => 'Starter',
            'price' => 5000,
            'currency' => 'NGN',
            'billing_cycle' => 'monthly',
            'features' => [
                'invoicing',
                'customers',
                'products',
                'basic_reports'
            ],
            'limits' => [
                'invoices_per_month' => 50,
                'customers' => 100,
                'products' => 200,
                'users' => 3,
                'storage_mb' => 500
            ]
        ],
        'professional' => [
            'name' => 'Professional',
            'price' => 15000,
            'currency' => 'NGN',
            'billing_cycle' => 'monthly',
            'features' => [
                'invoicing',
                'customers',
                'products',
                'inventory',
                'advanced_reports',
                'multi_currency',
                'api_access'
            ],
            'limits' => [
                'invoices_per_month' => 200,
                'customers' => 500,
                'products' => 1000,
                'users' => 10,
                'storage_mb' => 2000
            ]
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'price' => 50000,
            'currency' => 'NGN',
            'billing_cycle' => 'monthly',
            'features' => [
                'invoicing',
                'customers',
                'products',
                'inventory',
                'advanced_reports',
                'multi_currency',
                'api_access',
                'custom_branding',
                'priority_support',
                'data_export'
            ],
            'limits' => [
                'invoices_per_month' => -1, // unlimited
                'customers' => -1,
                'products' => -1,
                'users' => -1,
                'storage_mb' => 10000
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Nigerian Business Settings
    |--------------------------------------------------------------------------
    */
    'nigeria' => [
        'vat_rate' => 7.5,
        'withholding_tax_rate' => 5.0,
        'currency_code' => 'NGN',
        'currency_symbol' => '₦',
        'phone_prefix' => '+234',
        'business_registration_types' => [
            'sole_proprietorship' => 'Sole Proprietorship',
            'partnership' => 'Partnership',
            'limited_liability' => 'Limited Liability Company',
            'public_limited' => 'Public Limited Company',
            'incorporated_trustees' => 'Incorporated Trustees'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Settings
    |--------------------------------------------------------------------------
    */
    'uploads' => [
        'max_file_size' => 5 * 1024 * 1024, // 5MB
        'allowed_types' => [
            'images' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'documents' => ['pdf', 'doc', 'docx', 'txt'],
            'spreadsheets' => ['xls', 'xlsx', 'csv']
        ],
        'storage_disk' => 'local',
        'path_prefix' => 'tenants'
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Settings
    |--------------------------------------------------------------------------
    */
    'backup' => [
        'enabled' => true,
        'frequency_options' => [
            'hourly' => 'Every Hour',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly'
        ],
        'default_frequency' => 'daily',
        'retention_days' => 30,
        'include_files' => true
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'channels' => ['mail', 'database'],
        'invoice_reminders' => [
            'before_due' => [7, 3, 1], // days before due date
            'after_due' => [1, 7, 14, 30] // days after due date
        ],
        'low_stock_threshold' => 10
    ],

    /*
    |--------------------------------------------------------------------------
    | API Settings
    |--------------------------------------------------------------------------
    */
    'api' => [
        'rate_limit' => 60, // requests per minute
        'version' => 'v1',
        'pagination' => [
            'default_per_page' => 15,
            'max_per_page' => 100
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'security' => [
        'session_timeout' => 3600, // 1 hour in seconds
        'max_login_attempts' => 5,
        'lockout_duration' => 900, // 15 minutes in seconds
        'password_expiry_days' => 90,
        'require_2fa' => false
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    */
    'features' => [
        'multi_currency' => true,
        'inventory_management' => true,
        'project_management' => false,
        'time_tracking' => false,
        'expense_management' => true,
        'payroll' => false,
        'pos_system' => true,
        'mobile_app' => true,
        'api_access' => true,
        'webhooks' => false,
        'custom_fields' => true,
        'advanced_reporting' => true,
        'data_export' => true,
        'email_marketing' => false
    ]
];