<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'unit_id',
        'type',
        'name',
        'sku',
        'description',
        'category',
        'brand',
        'model',
        'hsn_code',
        'purchase_rate',
        'sales_rate',
        'mrp',
        'minimum_selling_price',
        'primary_unit',
        'secondary_unit',
        'opening_stock',
        'current_stock',
        'available_stock',
        'allocated_stock',
        'reorder_level',
        'maximum_stock',
        'tax_rate',
        'tax_type',
        'tax_inclusive',
        'size',
        'color',
        'weight',
        'weight_unit',
        'barcode',
        'image_path',
        'maintain_stock',
        'is_active',
        'is_saleable',
        'is_purchasable',
        'track_serial_numbers',
        'track_batch_numbers',
        'perishable',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'purchase_rate' => 'decimal:2',
        'sales_rate' => 'decimal:2',
        'mrp' => 'decimal:2',
        'minimum_selling_price' => 'decimal:2',
        'opening_stock' => 'decimal:2',
        'current_stock' => 'decimal:2',
        'available_stock' => 'decimal:2',
        'allocated_stock' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'maximum_stock' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'weight' => 'decimal:3',
        'maintain_stock' => 'boolean',
        'is_active' => 'boolean',
        'is_saleable' => 'boolean',
        'is_purchasable' => 'boolean',
        'track_serial_numbers' => 'boolean',
        'track_batch_numbers' => 'boolean',
        'perishable' => 'boolean',
        'tax_inclusive' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the tenant that owns the product.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

       /**
     * Get the category that the product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }


        /**
     * Get the unit of measurement for the product.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the user who created the product.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the product.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the stock movements for the product.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get the product's stock status.
     */
    public function getStockStatusAttribute(): string
    {
        if (!$this->maintain_stock) {
            return 'N/A';
        }

        if ($this->current_stock <= 0) {
            return 'Out of Stock';
        }

        if ($this->reorder_level && $this->current_stock <= $this->reorder_level) {
            return 'Low Stock';
        }

        return 'In Stock';
    }

    /**
     * Check if product is low on stock.
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->maintain_stock &&
               $this->reorder_level &&
               $this->current_stock <= $this->reorder_level &&
               $this->current_stock > 0;
    }

    /**
     * Check if product is out of stock.
     */
    public function getIsOutOfStockAttribute(): bool
    {
        return $this->maintain_stock && $this->current_stock <= 0;
    }

    /**
     * Get the product's display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ($this->sku ? ' (' . $this->sku . ')' : '');
    }

    /**
     * Get the product's profit margin.
     */
    public function getProfitMarginAttribute(): float
    {
        if ($this->purchase_rate <= 0) {
            return 0;
        }

        return (($this->sales_rate - $this->purchase_rate) / $this->purchase_rate) * 100;
    }

    /**
     * Get the product's profit amount.
     */
    public function getProfitAmountAttribute(): float
    {
        return $this->sales_rate - $this->purchase_rate;
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

        /**
     * Scope a query to only include products in a specific category.
     */
    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }


    /**
     * Scope a query to only include saleable products.
     */
    public function scopeSaleable($query)
    {
        return $query->where('is_saleable', true);
    }

    /**
     * Scope a query to only include purchasable products.
     */
    public function scopePurchasable($query)
    {
        return $query->where('is_purchasable', true);
    }

    /**
     * Scope a query to only include items (not services).
     */
    public function scopeItems($query)
    {
        return $query->where('type', 'item');
    }

    /**
     * Scope a query to only include services.
     */
    public function scopeServices($query)
    {
        return $query->where('type', 'service');
    }

    /**
     * Scope a query to only include products with low stock.
     */
    public function scopeLowStock($query)
    {
        return $query->where('maintain_stock', true)
                    ->whereColumn('current_stock', '<=', 'reorder_level')
                    ->where('current_stock', '>', 0);
    }

    /**
     * Scope a query to only include out of stock products.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('maintain_stock', true)
                    ->where('current_stock', '<=', 0);
    }

    /**
     * Update stock levels.
     */
    public function updateStock(float $quantity, string $type = 'adjustment', array $options = []): void
    {
        if (!$this->maintain_stock) {
            return;
        }

            $oldStock = $this->current_stock;
            $newStock = $oldStock + $quantity;

            // Ensure stock doesn't go below zero
            if ($newStock < 0) {
                $newStock = 0;
            }

            $this->update([
                'current_stock' => $newStock,
                'available_stock' => $newStock - $this->allocated_stock,
            ]);

            // Create stock movement record
            StockMovement::create([
                'tenant_id' => $this->tenant_id,
                'product_id' => $this->id,
                'type' => $type,
                'quantity' => $quantity,
                'old_stock' => $oldStock,
                'new_stock' => $newStock,
                'rate' => $options['rate'] ?? $this->purchase_rate,
                'reference' => $options['reference'] ?? null,
                'remarks' => $options['remarks'] ?? null,
                'created_by' => auth()->id(),
            ]);
        }

        /**
         * Allocate stock for orders/invoices.
         */
        public function allocateStock(float $quantity): bool
        {
            if (!$this->maintain_stock) {
                return true;
            }

            if ($this->available_stock < $quantity) {
                return false;
            }

            $this->update([
                'allocated_stock' => $this->allocated_stock + $quantity,
                'available_stock' => $this->available_stock - $quantity,
            ]);

            return true;
        }

        /**
         * Release allocated stock.
         */
        public function releaseStock(float $quantity): void
        {
            if (!$this->maintain_stock) {
                return;
            }

            $this->update([
                'allocated_stock' => max(0, $this->allocated_stock - $quantity),
                'available_stock' => min($this->current_stock, $this->available_stock + $quantity),
            ]);
        }

        /**
         * Generate SKU if not provided.
         */
        public static function generateSku(string $name, ?string $category = null): string
        {
            $prefix = '';

            if ($category) {
                $prefix = strtoupper(substr($category, 0, 3));
            } else {
                $prefix = 'PRD';
            }

            $namePrefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 3));
            $timestamp = substr(time(), -4);

            return $prefix . '-' . $namePrefix . '-' . $timestamp;
        }

        /**
         * Boot the model.
         */
        protected static function boot()
        {
            parent::boot();

            static::creating(function ($product) {
                // Generate SKU if not provided
                if (empty($product->sku)) {
                    $product->sku = self::generateSku($product->name, $product->category);
                }

                // Set default values
                if ($product->type === 'service') {
                    $product->maintain_stock = false;
                    $product->opening_stock = 0;
                    $product->current_stock = 0;
                    $product->available_stock = 0;
                    $product->allocated_stock = 0;
                } else {
                    $product->current_stock = $product->opening_stock ?? 0;
                    $product->available_stock = $product->opening_stock ?? 0;
                    $product->allocated_stock = 0;
                }
            });
        }
}
