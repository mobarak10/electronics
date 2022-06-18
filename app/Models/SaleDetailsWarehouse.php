<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetailsWarehouse extends Model
{
    protected $dates = ['created_at'];

    public function sale(){
        return $this->belongsTo('App\Models\Sale', 'sale_id');
    }

    public function saleDetails(){
        return $this->belongsTo('App\Models\SaleDetails', 'sale_details_id');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function warehouse(){
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_id');
    }
}
