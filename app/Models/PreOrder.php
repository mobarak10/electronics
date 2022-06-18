<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreOrder extends Model
{
    protected $fillable = [
        'date',
        'order_no',
        'customer_id',
        'warehouse_id',
        'cash_id',
        'bank_account_id',
        'user_id',
        'payment_type',
        'subtotal',
        'vat',
        'discount',
        'discount_type',
        'labour_cost',
        'transport_cost',
        'paid',
        'due',
        'change',
        'customer_balance',
        'delivered',
        'comment',
        'business_id',
    ];
    protected $dates = ['date'];
    protected $appends = [
        'pre_order_grand_total', 'pre_order_paid'
    ];

    /**
     * Get associated cash
     *
     * @return BelongsTo
     */
    public function cash(): BelongsTo
    {
        return $this->belongsTo(Cash::class);
    }

    /**
     * Get associated bank account
     *
     * @return BelongsTo
     */
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * get all pre-order details
     * @return HasMany
     */
    public function preOrderDetails(): HasMany
    {
        return $this->hasMany(PreOrderDetails::class);
    }

    /**
     * get all pre-order delivery details
     * @return HasMany
     */
    public function preOrderDeliveryDetails(): HasMany
    {
        return $this->hasMany(PreOrderDeliveryDetails::class);
    }

    /**
     * get pre-order customer
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get related products
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function products()
    {
        // country -> pre order
        // post -> product
        // user -> pre order details
        return $this->hasManyThrough(
            Product::class,
            PreOrderDetails::class,
            'pre_order_id',
            'id',
            'id',
            'product_id'
        );
    }

    /**
     * Get associated warehouse
     *
     * @return BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /*-----------   Accessor Start   ------------ */
    public function getPreOrderGrandTotalAttribute()
    {
        $total = $this->subtotal;

        $discount = $this->discount;
        if ($this->discount_type === 'flat') {
            $total -= $discount;
        } else {
            $total -= (($total * $discount) / 100);
        }
        $total += $this->labour_cost + $this->transport_cost;

        return $total;
    }

    public function getPreOrderPaidAttribute()
    {
        return $this->paid;
    }
    /*-----------   Accessor End ------------ */
}
