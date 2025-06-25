# Ballie - Multi-Tenant Business Management System

## Overview
Ballie is a comprehensive business management software built specifically for Nigerian businesses. It features a multi-tenant architecture where each business operates in its own isolated environment.

## Key Features
- **Multi-tenant Architecture**: Each business has its own isolated data and customization
- **Nigerian Business Focus**: Built-in VAT calculations, Nigerian phone number validation, local currency support
- **Comprehensive Business Tools**: Invoicing, inventory, CRM, reporting, and more
- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile devices
- **Role-based Access Control**: Different permission levels for team members

## Installation & Setup

### 1. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database settings in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ballie
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 2. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed initial data
php artisan db:seed
```

### 3. Storage Setup
```bash
# Create storage link
php artisan storage:link

# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 4. Queue Configuration
```bash
# For development, you can use sync driver
QUEUE_CONNECTION=sync

# For production, use redis or database
QUEUE_CONNECTION=redis
```

## Multi-Tenant Structure

### Tenant Identification
- **Path-based**: `/tenant1/dashboard`, `/tenant2/invoices`
- **Domain-based**: `tenant1.ballie.com`, `tenant2.ballie.com` (optional)

### Tenant Isolation
- Each tenant has isolated database records
- File storage is separated by tenant ID
- Cache keys are prefixed with tenant ID
- User sessions are tenant-specific

## User Roles & Permissions

### Available Roles
1. **Owner**: Full access to all features and settings
2. **Admin**: Manage users, view reports, handle core business functions
3. **Manager**: Manage invoices, customers, products, inventory
4. **Accountant**: Focus on financial aspects - invoices, payments, reports
5. **Sales**: Handle customer relationships and sales processes
6. **Employee**: Basic access to view data and perform assigned tasks

### Permission System
```php
// Check if user has specific permission
TenantHelper::hasPermission('manage_invoices')

// Get user's role permissions
TenantHelper::getRolePermissions('manager')
```

## Nigerian Business Features

### Tax Calculations
- **VAT**: 7.5% (configurable per tenant)
- **Withholding Tax**: 5% (configurable)
- **Tax-inclusive/exclusive** pricing options

### Local Integrations
- Nigerian phone number validation and formatting
- Support for all 36 states + FCT
- Nigerian business registration types
- Local currency formatting (₦)

### Compliance Features
- VAT number validation
- TIN (Tax Identification Number) support
- FIRS-compliant reporting formats

## API Documentation

### Authentication
```bash
# Get access token
POST /api/auth/login
{
    "email": "user@example.com",
    "password": "password"
}
```

### Common Endpoints
```bash
# Dashboard stats
GET /api/dashboard/stats

# Invoices
GET /api/invoices
POST /api/invoices
PUT /api/invoices/{id}
DELETE /api/invoices/{id}

# Customers
GET /api/customers
POST /api/customers
PUT /api/customers/{id}
DELETE /api/customers/{id}
```

## Customization

### Tenant Settings
Each tenant can customize:
- Business information and branding
- Tax rates and preferences
- Invoice templates and numbering
- Payment terms and methods
- Notification preferences
- User roles and permissions

### Theming
- Light/dark mode support
- Custom color schemes (planned)
- Logo and branding customization
- Custom email templates

## Performance Optimization

### Caching Strategy
- Dashboard statistics cached for 5 minutes
- User permissions cached per session
- Tenant settings cached until modified
- Database query results cached where appropriate

### Database Optimization
- Proper indexing on tenant_id columns
- Eager loading to prevent N+1 queries
- Database query optimization
- Regular maintenance tasks

## Security Features

### Data Protection
- Tenant data isolation
- Encrypted sensitive data
- Secure file uploads
- SQL injection prevention
- XSS protection

### Access Control
- Role-based permissions
- Session management
- Rate limiting on API endpoints
- CSRF protection
- Secure password requirements

## Backup & Recovery

### Automated Backups
- Configurable backup frequency (hourly, daily, weekly, monthly)
- Database and file backups
- Retention policy management
- Backup integrity verification

### Manual Backups
```bash
# Create manual backup
php artisan tenant:backup {tenant_id}

# Restore from backup
php artisan tenant:restore {tenant_id} {backup_file}
```

## Monitoring & Maintenance

### Health Checks
- Database connectivity
- Storage accessibility
- Queue processing status
- Backup status monitoring
- Subscription status tracking

### Maintenance Tasks
```bash
# Clear expired sessions
php artisan session:gc

# Clean up old backups
php artisan backup:clean

# Update subscription statuses
php artisan subscription:update

# Generate reports
php artisan reports:generate
```

## Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper database credentials
- [ ] Set up Redis for caching and queues
- [ ] Configure email settings
- [ ] Set up SSL certificates
- [ ] Configure backup storage
- [ ] Set up monitoring and logging
- [ ] Configure cron jobs for scheduled tasks

### Server Requirements
- PHP 8.1 or higher
- MySQL 8.0 or PostgreSQL 13+
- Redis (recommended)
- Composer
- Node.js & NPM (for asset compilation)

## Support & Documentation

### Getting Help
- Check the documentation first
- Review error logs in `storage/logs/`
- Use the built-in help system
- Contact support for subscription-related issues

### Contributing
- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation
- Submit pull requests for review

---

**Ballie** - Empowering Nigerian Businesses with Smart Management Tools