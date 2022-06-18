<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionOutDetails extends Model
{
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
        'production_out_id'
    ];
}
