<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    protected $fillable = [
        'date',
        'payment_type',
        'party_id',
        'cash_id',
        'bank_account_id',
        'warehouse_id',
        'voucher_no',
        'subtotal',
        'discount',
        'discount_type',
        'labour_cost',
        'transport_cost',
        'paid',
        'due',
        'previous_balance',
        'note',
        'user_id',
        'business_id',
    ];
    protected $dates = ['date'];
    protected $appends = ['grand_total'];

    /**
     * get grand total
     * @return mixed
     */
    public function getGrandTotalAttribute()
    {
        return $this->subtotal
            + $this->transport_cost
            + $this->labour_cost
            - $this->discount;
    }

    /*==== Relationship Start ====*/

    /**
     * Purchase details
     * @return HasMany
     */
    public function details(): HasMany
    {
        return $this->hasMany(PurchaseDetails::class);
    }

    /**
     * Purchase returns
     * @return HasMany
     */
    public function purchaseReturns(): HasMany
    {
        return $this->hasMany(PurchaseReturn::class);
    }

    /**
     * Supplier
     * @return BelongsTo
     */
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    /**
     * Get associated cash
     *
     * @return BelongsTo
     */
    public function cash()
    {
        return $this->belongsTo(Cash::class);
    }

    /**
     * Get associated bank account
     *
     * @return BelongsTo
     */
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * Get associated warehouse
     *
     * @return BelongsTo
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get related products
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function products()
    {
        // country -> purchase
        // post -> product
        // user -> purchase details
        return $this->hasManyThrough(
            Product::class,
            PurchaseDetails::class,
            'purchase_id',
            'id',
            'id',
            'product_id'
        );
    }

    /*==== Relationship End ====*/
}
