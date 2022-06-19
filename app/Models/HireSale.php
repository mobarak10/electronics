<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HireSale extends Model
{
    protected $fillable = [
        'date',
        'voucher_no',
        'warehouse_id',
        'party_id',
        'previous_balance',
        'subtotal',
        'due',
        'pay',
        'added_value',
        'down_payment',
        'installment_number',
    ];
    protected $dates = ['date'];
    protected $appends = ['due_in_sale_time', 'total_due', 'total_pay', 'installment_amount'];

    /**
     * get customer details
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Party::class, 'party_id', 'id');
    }

    /**
     * get hiresale payment details
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hireSalePayment()
    {
        return $this->hasOne(HireSalePayment::class);
    }

    /**
     * get all product for a hire sale
     * @return HasMany
     */
    public function hireSaleProducts()
    {
        return $this->hasMany(HireSaleProduct::class, 'hire_sale_id', 'id');
    }

    /**
     * get all hire sale installment for a hire sale
     * @return HasMany
     */
    public function hireSaleInstallments(): HasMany
    {
        return $this->hasMany(HireSaleInstallment::class, 'hire_sale_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function installmentCollection(): HasMany
    {
        return $this->hasMany(InstallmentCollection::class, 'hire_sale_id', 'id');
    }

    /**
     * get amount of payment when sale
     */
    public function getDueInSaleTimeAttribute()
    {
        return ($this->subtotal + $this->added_value) - $this->down_payment;
    }

    /**
     * get total due
     */
    public function getTotalDueAttribute()
    {
        return ($this->due_in_sale_time - $this->total_pay);
    }

    /**
     * get total due
     */
    public function getTotalPayAttribute()
    {
        return $this->installmentCollection->sum('payment_amount');
    }

    /**
     * get installment amount
     * @return float|int
     */
    public function getInstallmentAmountAttribute()
    {
        return ($this->due + $this->added_value) / $this->installment_number;
    }
}
