<?php

namespace App\Models;

use App\Helpers\Converter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseDetails extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'purchase_price',
        'quantity',
        'quantity_in_unit',
        'discount',
        'discount_type',
        'line_total',
    ];

    protected $casts = ['quantity_in_unit' => 'array'];

//    protected $appends = [
//        'purchase_total_quantities',
//        'purchase_total_quantities_in_unit'
//    ];

    /*
     * get purchase details
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * get Product details
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
