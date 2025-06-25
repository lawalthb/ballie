// Configuration for tenant dashboard
window.TenantConfig = {
    // API endpoints
    api: {
        base: '/api',
        endpoints: {
            dashboard: '/dashboard/stats',
            invoices: '/invoices',
            customers: '/customers',
            products: '/products',
            reports: '/reports',
            settings: '/settings',
            users: '/users'
        }
    },

    // UI settings
    ui: {
        theme: 'light', // light, dark, auto
        sidebar: {
            collapsed: false,
            mobile: false
        },
        notifications: {
            position: 'top-right', // top-right, top-left, bottom-right, bottom-left
            duration: 5000,
            maxVisible: 5
        },
        tables: {
            defaultPageSize: 10,
            pageSizes: [10, 25, 50, 100]
        }
    },

    // Feature flags
    features: {
        darkMode: true,
        notifications: true,
        autoSave: true,
        keyboardShortcuts: true,
        analytics: true
    },

    // Formatting options
    formatting: {
        currency: {
            code: 'NGN',
            symbol: '₦',
            locale: 'en-NG'
        },
        date: {
            format: 'DD/MM/YYYY',
            locale: 'en-NG'
        },
        number: {
            locale: 'en-NG',
            thousandsSeparator: ',',
            decimalSeparator: '.'
        }
    },

    // File upload settings
    upload: {
        maxSize: 5 * 1024 * 1024, // 5MB
        allowedTypes: {
            images: ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
            documents: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            spreadsheets: ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv']
        }
    },

    // Validation rules
    validation: {
        email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        phone: /^(\+234|0)[789][01]\d{8}$/,
        tin: /^\d{8}-\d{4}$/,
        password: {
            minLength: 8,
            requireUppercase: true,
            requireLowercase: true,
            requireNumbers: true,
            requireSpecialChars: false
        }
    },

    // Chart default options
    charts: {
        colors: {
            primary: '#3b82f6',
            success: '#10b981',
            warning: '#f59e0b',
            danger: '#ef4444',
            info: '#06b6d4',
            secondary: '#6b7280'
        },
        defaults: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f3f4f6'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    },

    // Keyboard shortcuts
    shortcuts: {
        search: 'ctrl+k',
        newItem: 'ctrl+n',
        save: 'ctrl+s',
        help: '?',
        toggleSidebar: 'ctrl+b',
        toggleTheme: 'ctrl+shift+t'
    },

    // Auto-save settings
    autoSave: {
        interval: 30000, // 30 seconds
        forms: ['invoice-form', 'customer-form', 'product-form']
    },

    // Notification templates
    notifications: {
        templates: {
            success: {
                invoice_created: 'Invoice created successfully',
                customer_added: 'Customer added successfully',
                payment_recorded: 'Payment recorded successfully',
                settings_saved: 'Settings saved successfully'
            },
            error: {
                network_error: 'Network error. Please try again.',
                validation_failed: 'Please check your input and try again.',
                permission_denied: 'You do not have permission to perform this action.',
                server_error: 'Server error. Please contact support.'
            },
            warning: {
                unsaved_changes: 'You have unsaved changes. Are you sure you want to leave?',
                low_stock: 'Some products are running low on stock.',
                overdue_invoices: 'You have overdue invoices that need attention.'
            },
            info: {
                draft_saved: 'Draft saved automatically',
                backup_completed: 'Data backup completed',
                update_available: 'A new update is available'
            }
        }
    },

    // Status mappings
    status: {
        invoice: {
            draft: { label: 'Draft', color: 'gray' },
            sent: { label: 'Sent', color: 'blue' },
            paid: { label: 'Paid', color: 'green' },
            overdue: { label: 'Overdue', color: 'red' },
            cancelled: { label: 'Cancelled', color: 'gray' }
        },
        customer: {
            active: { label: 'Active', color: 'green' },
            inactive: { label: 'Inactive', color: 'gray' },
            blocked: { label: 'Blocked', color: 'red' }
        },
        product: {
            active: { label: 'Active', color: 'green' },
            inactive: { label: 'Inactive', color: 'gray' },
            out_of_stock: { label: 'Out of Stock', color: 'red' },
            low_stock: { label: 'Low Stock', color: 'yellow' }
        },
        user: {
            active: { label: 'Active', color: 'green' },
            inactive: { label: 'Inactive', color: 'gray' },
            pending: { label: 'Pending', color: 'yellow' },
            suspended: { label: 'Suspended', color: 'red' }
        }
    },

    // Default pagination settings
    pagination: {
        defaultPage: 1,
        defaultPerPage: 10,
        maxPerPage: 100,
        showSizeSelector: true,
        showPageInfo: true
    },

    // Search settings
    search: {
        minLength: 2,
        debounceDelay: 300,
        highlightMatches: true
    },

    // Export settings
    export: {
        formats: ['pdf', 'excel', 'csv'],
        defaultFormat: 'pdf',
        includeHeaders: true,
        dateRange: {
            default: 'last_30_days',
            options: [
                { value: 'today', label: 'Today' },
                { value: 'yesterday', label: 'Yesterday' },
                { value: 'last_7_days', label: 'Last 7 days' },
                { value: 'last_30_days', label: 'Last 30 days' },
                { value: 'last_90_days', label: 'Last 90 days' },
                { value: 'this_month', label: 'This month' },
                { value: 'last_month', label: 'Last month' },
                { value: 'this_year', label: 'This year' },
                { value: 'custom', label: 'Custom range' }
            ]
        }
    },

    // Dashboard widgets configuration
    dashboard: {
        widgets: {
            revenue: {
                title: 'Total Revenue',
                icon: 'currency',
                color: 'primary',
                format: 'currency'
            },
            invoices: {
                title: 'Total Invoices',
                icon: 'document',
                color: 'success',
                format: 'number'
            },
            customers: {
                title: 'Total Customers',
                icon: 'users',
                color: 'info',
                format: 'number'
            },
            products: {
                title: 'Total Products',
                icon: 'package',
                color: 'warning',
                format: 'number'
            }
        },
        refreshInterval: 300000, // 5 minutes
        autoRefresh: true
    },

    // Form validation messages
    validation_messages: {
        required: 'This field is required',
        email: 'Please enter a valid email address',
        phone: 'Please enter a valid Nigerian phone number',
        min_length: 'Must be at least {min} characters',
        max_length: 'Must not exceed {max} characters',
        numeric: 'Please enter a valid number',
        positive: 'Must be a positive number',
        date: 'Please enter a valid date',
        url: 'Please enter a valid URL',
        password_mismatch: 'Passwords do not match'
    },

    // Regional settings for Nigeria
    regional: {
        timezone: 'Africa/Lagos',
        workingDays: [1, 2, 3, 4, 5], // Monday to Friday
        workingHours: {
            start: '09:00',
            end: '17:00'
        },
        holidays: [
            // Nigerian public holidays
            '2024-01-01', // New Year's Day
            '2024-04-10', // Eid al-Fitr (estimated)
            '2024-05-01', // Workers' Day
            '2024-06-12', // Democracy Day
            '2024-06-17', // Eid al-Adha (estimated)
            '2024-10-01', // Independence Day
            '2024-12-25', // Christmas Day
            '2024-12-26'  // Boxing Day
        ]
    },

    // Integration settings
    integrations: {
        paystack: {
            enabled: false,
            publicKey: null
        },
        flutterwave: {
            enabled: false,
            publicKey: null
        },
        sms: {
            enabled: false,
            provider: null
        },
        email: {
            enabled: true,
            provider: 'smtp'
        }
    },

    // Security settings
    security: {
        sessionTimeout: 3600000, // 1 hour
        maxLoginAttempts: 5,
        lockoutDuration: 900000, // 15 minutes
        passwordExpiry: 7776000000, // 90 days
        twoFactorAuth: false
    },

    // Backup settings
    backup: {
        autoBackup: true,
        frequency: 'daily',
        retention: 30, // days
        includeFiles: true
    },

    // Performance settings
    performance: {
        lazyLoading: true,
        cacheTimeout: 300000, // 5 minutes
        maxCacheSize: 50, // MB
        compressionEnabled: true
    },

    // Accessibility settings
    accessibility: {
        highContrast: false,
        largeText: false,
        reducedMotion: false,
        screenReader: false
    },

    // Debug settings (only in development)
    debug: {
        enabled: false,
        logLevel: 'info', // error, warn, info, debug
        showPerformanceMetrics: false,
        mockData: false
    }
}

// Utility function to get nested config values
window.getConfig = function(path, defaultValue = null) {
    const keys = path.split('.')
    let value = window.TenantConfig

    for (const key of keys) {
        if (value && typeof value === 'object' && key in value) {
            value = value[key]
        } else {
            return defaultValue
        }
    }

    return value
}

// Utility function to set nested config values
window.setConfig = function(path, value) {
    const keys = path.split('.')
    const lastKey = keys.pop()
    let obj = window.TenantConfig

    for (const key of keys) {
        if (!(key in obj) || typeof obj[key] !== 'object') {
            obj[key] = {}
        }
        obj = obj[key]
    }

    obj[lastKey] = value
}

// Initialize configuration based on user preferences
document.addEventListener('DOMContentLoaded', function() {
    // Load user preferences from localStorage
    const userPrefs = JSON.parse(localStorage.getItem('tenant_preferences') || '{}')

    // Apply user preferences to config
    Object.keys(userPrefs).forEach(key => {
        if (key in window.TenantConfig.ui) {
            window.TenantConfig.ui[key] = userPrefs[key]
        }
    })

    // Apply theme
    if (window.TenantConfig.ui.theme === 'dark' ||
        (window.TenantConfig.ui.theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark')
    }

    // Apply accessibility settings
    if (window.TenantConfig.accessibility.reducedMotion ||
        window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.documentElement.classList.add('motion-reduce')
    }

    if (window.TenantConfig.accessibility.highContrast ||
        window.matchMedia('(prefers-contrast: high)').matches) {
        document.documentElement.classList.add('high-contrast')
    }
})

// Save user preferences
window.saveUserPreferences = function() {
    const preferences = {
        theme: window.TenantConfig.ui.theme,
        sidebarCollapsed: window.TenantConfig.ui.sidebar.collapsed,
        notificationsEnabled: window.TenantConfig.features.notifications,
        autoSaveEnabled: window.TenantConfig.features.autoSave
    }

    localStorage.setItem('tenant_preferences', JSON.stringify(preferences))
}

// Export configuration for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = window.TenantConfig
}