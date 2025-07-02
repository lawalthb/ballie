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

    public function getTotalBalance()
    {
        $balance = 0;

        // Get balance from direct ledger accounts
        foreach ($this->ledgerAccounts as $ledger) {
            $balance += $ledger->getCurrentBalance();
        }

        // Get balance from child groups
        foreach ($this->children as $child) {
            $balance += $child->getTotalBalance();
        }

        return $balance;
    }
}
