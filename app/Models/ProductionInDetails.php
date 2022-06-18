<?php

namespace App\Models;

use App\Helpers\Converter;
use Illuminate\Database\Eloquent\Model;

class ProductionInDetails extends Model
{
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
        'production_in_id'
    ];
    protected $appends = ['production_in_total_quantities_in_unit'];

    /**
     * Purchase total quantity in unit
     * @return array
     */
    public function getProductionInTotalQuantitiesInUnitAttribute()
    {
        return Converter::convert($this->quantity, $this->product->unit_code, 'd');
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
