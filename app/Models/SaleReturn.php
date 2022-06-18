<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    protected $fillable = [
        'return_no',
        'user_id',
        'customer_id',
        'warehouse_id',
        'brand_id',
        'date',
        'subtotal',
        'discount',
        'paid',
        'paid_from',
        'customer_balance',
        'cash_id',
        'bank_account_id',
        'note',
        'business_id',
    ];

    protected $dates = ['date'];
    protected $appends = [
        'return_grand_total',
        'return_paid',
        'return_product_price_total',
        'return_product_purchase_price_total'
    ];

    /**
     * Sale Return products
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function returnProducts()
    {
        return $this->hasMany('\App\Models\SaleReturnProduct');
    }

    /**
     * Cash
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cash()
    {
        return $this->belongsTo('\App\Models\Cash');
    }

    /**
     * Operator
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('\App\Models\User\User');
    }

    /**
     * get customer details
     *
     * @return void
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /*--- accessor start ----*/
    public function getReturnGrandTotalAttribute()
    {
        return ($this->subtotal - $this->discount);
    }

    /**
     * get return total price
     *
     * @return void
     */
    public function getReturnProductPriceTotalAttribute()
    {
        return $this->return_product_price_subtotal - $this->adjustment;
    }
    /**
     * get return total purchase price
     *
     * @return void
     */
    public function getReturnProductPurchasePriceTotalAttribute()
    {
        $total = 0;
        foreach ($this->returnProducts as $product){
            $total += $product->quantity * $product->purchase_price;
        }
        return $total;
    }


    /**
     * get return paid total
     * @return mixed
     */
    public function getReturnPaidAttribute()
    {
        return $this->paid;
    }
    /*--- accessor End ----*/
}
