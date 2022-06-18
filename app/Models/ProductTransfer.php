<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

    class ProductTransfer extends Model {

    public function fromWarehouse(){
        return $this->belongsTo('App\Models\Warehouse', 'from_warehouse_id');
    }

    public function toWarehouse(){
        return $this->belongsTo('App\Models\Warehouse', 'to_warehouse_id');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    /**
     * get operator
     **/
    public function operator(){
        return $this->belongsTo('App\Models\User\User', 'user_id');
    }
}
