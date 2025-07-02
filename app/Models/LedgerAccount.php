<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LedgerAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'account_group_id',
        'opening_balance',
        'balance_type',
        'address',
        'phone',
        'email',
        'is_active',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function accountGroup()
    {
        return $this->belongsTo(AccountGroup::class);
    }

    public function voucherEntries()
    {
        return $this->hasMany(VoucherEntry::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByGroup($query, $groupId)
    {
        return $query->where('account_group_id', $groupId);
    }

    // Methods
    public function getCurrentBalance($asOfDate = null)
    {
        $query = $this->voucherEntries()
            ->whereHas('voucher', function ($q) use ($asOfDate) {
                $q->where('status', 'posted');
                if ($asOfDate) {
                    $q->where('voucher_date', '<=', $asOfDate);
                }
            });

        $totalDebits = $query->sum('debit_amount');
        $totalCredits = $query->sum('credit_amount');

        $currentBalance = $this->opening_balance + $totalDebits - $totalCredits;

        return $currentBalance;
    }

    public function getBalanceType($balance = null)
    {
        $balance = $balance ?? $this->getCurrentBalance();

        // For asset and expense accounts, positive balance is debit
        if (in_array($this->accountGroup->nature, ['assets', 'expenses'])) {
            return $balance >= 0 ? 'dr' : 'cr';
        }

        // For liability and income accounts, positive balance is credit
        return $balance >= 0 ? 'cr' : 'dr';
    }

    public function getFormattedBalance($asOfDate = null)
    {
        $balance = abs($this->getCurrentBalance($asOfDate));
        $type = $this->getBalanceType();

        return [
            'amount' => $balance,
            'type' => $type,
            'formatted' => number_format($balance, 2) . ' ' . strtoupper($type)
        ];
    }

    public function getLedgerEntries($fromDate = null, $toDate = null)
    {
        $query = $this->voucherEntries()
            ->with(['voucher'])
            ->whereHas('voucher', function ($q) use ($fromDate, $toDate) {
                $q->where('status', 'posted');
                if ($fromDate) {
                    $q->where('voucher_date', '>=', $fromDate);
                }
                if ($toDate) {
                    $q->where('voucher_date', '<=', $toDate);
                }
            })
            ->orderBy('created_at');

        return $query->get();
    }
}
