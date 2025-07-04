<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'symbol',
        'description',
        'base_unit_id',
        'conversion_factor',
        'is_base_unit',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'conversion_factor' => 'decimal:6',
        'is_base_unit' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the tenant that owns the unit.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the base unit if this is a derived unit.
     */
    public function baseUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    /**
     * Get the derived units for this base unit.
     */
    public function derivedUnits(): HasMany
    {
        return $this->hasMany(Unit::class, 'base_unit_id');
    }

    /**
     * Get the products that use this unit.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope a query to only include active units.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include base units.
     */
    public function scopeBaseUnits($query)
    {
        return $query->where('is_base_unit', true);
    }

    /**
     * Scope a query to only include units for a specific tenant.
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Get the display name with symbol.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ' (' . $this->symbol . ')';
    }

    /**
     * Convert quantity from this unit to base unit.
     */
    public function convertToBaseUnit(float $quantity): float
    {
        if ($this->is_base_unit) {
            return $quantity;
        }

        return $quantity * $this->conversion_factor;
    }

    /**
     * Convert quantity from base unit to this unit.
     */
    public function convertFromBaseUnit(float $quantity): float
    {
        if ($this->is_base_unit) {
            return $quantity;
        }

        return $quantity / $this->conversion_factor;
    }
}
