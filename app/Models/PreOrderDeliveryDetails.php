<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreOrderDeliveryDetails extends Model
{
    protected $casts = ['delivery_quantity_in_unit' => 'array'];
    protected $fillable = [
        'pre_order_id',
        'product_id',
        'date',
        'delivery_quantity',
        'delivery_quantity_in_unit',
    ];

    protected $dates = ['date'];

    /**
     * get product details
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
