<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;
    public static $snakeAttributes = false; //camelcase relation true

    protected $appends = ['grand_total', 'total_discount'];
    protected $dates = ['date', 'promise_date'];

    protected $fillable = [
        'invoice_no',
        'date',
        'customer_id',
        'warehouse_id',
        'payment_type',
        'user_id',
        'subtotal',
        'discount',
        'discount_type',
        'labour_cost',
        'transport_cost',
        'paid',
        'due',
        'sale_due',
        'sale_amount',
        'business_id',
        'comment',
        'previous_balance',
        'delivered'
    ];

    /*======================Accessor Start======================*/

    /**
         * get total discount
         * @return float|int|mixed
         */
        public function getTotalDiscountAttribute()
        {
            $total_discount = 0;

            $discount = $this->discount;
            if ($this->discount_type === 'flat') {
                $total_discount += $discount;
            } else {
                $total_discount += (($this->subtotal * $discount) / 100);
            }

            return $total_discount;
        }

    /**
     * Get Grand Total of a sale
     *
     * @return number
     */
    public function getGrandTotalAttribute()
    {
        $vat = $this->vat;
        $subtotal = $this->subtotal;
        $total = $subtotal + (($subtotal * $vat) / 100);

        $discount = $this->discount;
        if ($this->discount_type === 'flat') {
            $total -= $discount;
        } else {
            $total -= (($total * $discount) / 100);
        }
        $total += $this->labour_cost + $this->transport_cost;

        return $total;
    }

    /*======================Accessor End======================*/


    /*======================Relationship Start======================*/

    /**
     * Sale Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function salePayment()
    {
        return $this->hasOne('\App\Models\SalePayment');
    }

    /**
     * Sale details
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function saleDetails()
    {
        return $this->hasMany('\App\Models\SaleDetails');
    }


    /**
     * Customer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('\App\Models\Customer', 'customer_id');
    }


    public function business()
    {
        return $this->belongsTo('\App\Models\Business', 'business_id');
    }

    /**
     * Salesman
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('\App\Models\User\User');
    }

    public function retailDueCollection()
    {
        return $this->hasMany(RetailDueCollection::class);
    }

    /*======================Relationship End======================*/
}
