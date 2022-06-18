<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreOrderDetails extends Model
{
    protected $casts = [
        'quantity_in_unit' => 'array',
        'delivery_quantity_in_unit' => 'array',
    ];
    protected $fillable = [
        'pre_order_id',
        'product_id',
        'purchase_price',
        'sale_price',
        'quantity',
        'quantity_in_unit',
        'delivery_quantity',
        'delivery_quantity_in_unit',
        'vat',
        'discount',
        'discount_type',
        'line_total',
    ];

    protected $appends = ['new_delivery_quantity', 'error'];

    /**
     * get product details
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return int
     */
    public function getNewDeliveryQuantityAttribute()
    {
        return 0;
    }

    /**
     * @return string
     */
    public function getErrorAttribute()
    {
        return '';
    }

}
