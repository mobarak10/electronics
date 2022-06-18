<?php

namespace App\Models;

use App\Helpers\Converter;
use Illuminate\Database\Eloquent\Model;

class SaleDetails extends Model
{
    protected $appends = [
        'total_quantity_in_format',
        'sale_product_purchase_price_total',
        'sale_product_sale_price_total',
    ];
    protected $casts = ['quantity_in_unit' => 'array'];

    protected $fillable = [
        'sale_id',
        'product_id',
        'purchase_price',
        'sale_price',
        'quantity',
        'quantity_in_unit',
        'vat',
        'discount',
        'discount_type',
        'line_total',
    ];

    /*---Accessor Start---*/

    /**
     * Total sale quantities in units
     * @return array
     */
    public function getTotalQuantityInFormatAttribute()
    {
        return Converter::convert($this->quantity, $this->product->unit_code, 'd');
    }

    /**
     * get sale product purchase price total
     * @return float|int
     */
    public function getSaleProductPurchasePriceTotalAttribute()
    {
        return $this->purchase_price * $this->quantity;
    }

    /**
     * get sale product sale price total
     * @return float|int
     */
    public function getSaleProductSalePriceTotalAttribute()
    {
        return $this->sale_price * $this->quantity;
    }

    /**
     * Sale table relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sale()
    {
        return $this->belongsTo('\App\Models\Sale');
    }

    /**
     * Product
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('\App\Models\Product');
    }
}
