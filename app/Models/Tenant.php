<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug', // For path-based routing (tenant1, tenant2)
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'business_type',
        'business_registration_number',
        'tax_identification_number',
        'logo',
        'website',
        'subscription_plan',
        'subscription_status',
        'subscription_starts_at',
        'subscription_ends_at',
        'trial_ends_at',
        'billing_cycle', // monthly, yearly
        'created_by', // Super admin who created this tenant
        'is_active',
        'settings',
        'onboarding_completed',
    ];

    protected $casts = [
        'subscription_starts_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'is_active' => 'boolean',
        'onboarding_completed' => 'boolean',
        'settings' => 'array',
    ];

    // Subscription plans
    const PLAN_STARTER = 'starter';
    const PLAN_PROFESSIONAL = 'professional';
    const PLAN_ENTERPRISE = 'enterprise';

    // Subscription status
    const STATUS_TRIAL = 'trial';
    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    // Billing cycles
    const BILLING_MONTHLY = 'monthly';
    const BILLING_YEARLY = 'yearly';

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function superAdmin(): BelongsTo
    {
        return $this->belongsTo(SuperAdmin::class, 'created_by');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    // Pricing methods
    public function getPlanPrice(): int
    {
        $prices = [
            self::PLAN_STARTER => [
                self::BILLING_MONTHLY => 7500,
                self::BILLING_YEARLY => 76500,
            ],
            self::PLAN_PROFESSIONAL => [
                self::BILLING_MONTHLY => 10000,
                self::BILLING_YEARLY => 102000,
            ],
            self::PLAN_ENTERPRISE => [
                self::BILLING_MONTHLY => 15000,
                self::BILLING_YEARLY => 153000,
            ],
        ];

        return $prices[$this->subscription_plan][$this->billing_cycle] ?? 0;
    }

    public function isOnTrial(): bool
    {
        return $this->subscription_status === self::STATUS_TRIAL &&
               $this->trial_ends_at &&
               $this->trial_ends_at->isFuture();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscription_status === self::STATUS_ACTIVE &&
               $this->subscription_ends_at &&
               $this->subscription_ends_at->isFuture();
    }

    public function canAccess(): bool
    {
        return $this->is_active && ($this->isOnTrial() || $this->hasActiveSubscription());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
