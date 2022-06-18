<?php

namespace App\Models;

use App\Helpers\Converter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseReturnProduct extends Model
{
    protected $fillable = [
        'purchase_return_id',
        'product_id',
        'return_price',
        'quantity',
        'quantity_in_unit',
        'line_total',
    ];

    protected $casts = [
        'quantity_in_unit' => 'array'
    ];

    protected $appends = ['total_quantity_in_format'];

    /**
     * Total sale quantities in units
     * @return array
     */
    public function getTotalQuantityInFormatAttribute()
    {
        return Converter::convert($this->quantity, $this->product->unit_code, 'd');
    }

    /**
     * Product
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    /*----Accessor Start-----*/

    /*----Accessor End-----*/

    /*---- Relation Start ----*/
    /**
     * get purchase return details
     * @return BelongsTo
     */
    public function purchaseReturn(): BelongsTo
    {
        return $this->belongsTo(PurchaseReturn::class);
    }
    /*---- Relation End ----*/
}
