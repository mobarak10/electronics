<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HireSaleProduct extends Model
{
    protected $fillable = [
        'product_id',
        'hire_sale_id',
        'product_serial',
        'quantity',
        'sale_price',
        'line_total',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
