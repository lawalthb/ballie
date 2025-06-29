<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'customer_type',
        'first_name',
        'last_name',
        'company_name',
        'tax_id',
        'email',
        'phone',
        'mobile',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'currency',
        'payment_terms',
        'total_spent',
        'last_invoice_date',
        'last_invoice_number',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_invoice_date' => 'datetime',
        'total_spent' => 'decimal:2',
    ];

    /**
     * Get the tenant that owns the customer.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the invoices for the customer.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the full name of the customer.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        if ($this->customer_type === 'individual') {
            return $this->first_name . ' ' . $this->last_name;
        }

        return $this->company_name;
    }

    /**
     * Get the full address of the customer.
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        $address = [];

        if ($this->address_line1) {
            $address[] = $this->address_line1;
        }

        if ($this->address_line2) {
            $address[] = $this->address_line2;
        }

        $cityStateZip = [];
        if ($this->city) {
            $cityStateZip[] = $this->city;
        }

        if ($this->state) {
            $cityStateZip[] = $this->state;
        }

        if ($this->postal_code) {
            $cityStateZip[] = $this->postal_code;
        }

        if (!empty($cityStateZip)) {
            $address[] = implode(', ', $cityStateZip);
        }

        if ($this->country) {
            $address[] = $this->country;
        }

        return implode(', ', $address);
    }

    /**
     * Get the outstanding balance for the customer.
     *
     * @return float
     */
    public function getOutstandingBalanceAttribute()
    {
        return $this->invoices()
            ->whereIn('status', ['sent', 'overdue', 'partial'])
            ->sum('balance_due');
    }

    /**
     * Scope a query to only include customers for a specific tenant.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $tenantId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope a query to only include active customers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include individual customers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIndividuals($query)
    {
        return $query->where('customer_type', 'individual');
    }

    /**
     * Scope a query to only include business customers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBusinesses($query)
    {
        return $query->where('customer_type', 'business');
    }

    /**
     * Update the customer's total spent amount and last invoice information.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function updateInvoiceStats(Invoice $invoice)
    {
        // Only update if the invoice is paid
        if ($invoice->status === 'paid') {
            $this->total_spent = $this->total_spent + $invoice->total_amount;
        }

        $this->last_invoice_date = $invoice->invoice_date;
        $this->last_invoice_number = $invoice->invoice_number;
        $this->save();
    }
}
