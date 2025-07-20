<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'nature',
        'parent_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function parent()
    {
        return $this->belongsTo(AccountGroup::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AccountGroup::class, 'parent_id');
    }

    public function ledgerAccounts()
    {
        return $this->hasMany(LedgerAccount::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByNature($query, $nature)
    {
        return $query->where('nature', $nature);
    }

    // Methods
    public function isParent()
    {
        return $this->children()->count() > 0;
    }

    public function getFullNameAttribute()
    {
        if ($this->parent) {
            return $this->parent->full_name . ' → ' . $this->name;
        }
        return $this->name;
    }

    public function scopeByLevel($query, $level = 0)
    {
        if ($level === 0) {
            return $query->whereNull('parent_id');
        }

        return $query->whereNotNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getLevel()
    {
        $level = 0;
        $parent = $this->parent;
        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }
        return $level;
    }

    public function getAllChildren()
    {
        $children = collect();

        foreach ($this->children as $child) {
            $children->push($child);
            $children = $children->merge($child->getAllChildren());
        }

        return $children;
    }

    public function getDefaultBalanceType()
    {
        if ($this->balance_type) {
            return $this->balance_type;
        }

        // Default balance types based on nature
        $defaults = [
            'assets' => 'dr',
            'expenses' => 'dr',
            'liabilities' => 'cr',
            'income' => 'cr',
            'equity' => 'cr'
        ];

        return $defaults[$this->nature] ?? 'dr';
    }

    public function getTotalBalance($asOfDate = null)
    {
        $balance = 0;

        // Get balance from direct ledger accounts
        foreach ($this->ledgerAccounts()->active()->get() as $ledger) {
            $accountBalance = $ledger->getCurrentBalance($asOfDate);

            // Normalize balance based on account type
            if (in_array($this->nature, ['liabilities', 'income', 'equity'])) {
                $balance += $accountBalance;
            } else {
                $balance += $accountBalance;
            }
        }

        // Get balance from child groups
        foreach ($this->children()->active()->get() as $child) {
            $balance += $child->getTotalBalance($asOfDate);
        }

        return $balance;
    }
}
