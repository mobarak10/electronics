<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DamageStock extends Model
{
    protected $appends = ["stock_price"];

    public function warehouses(){
    	return $this->belongsTo('App\Models\Warehouse', 'warehouse_id');
    }

    public function getStockPriceAttribute(){
        return $this->product->purchase_price * $this->quantity;
    }

    public function product(){
    	return $this->belongsTo('App\Models\Product', 'product_id');
    }

    /**
     * get all the value from Admin table in Expense table
    **/
    public function operator(){
        return $this->belongsTo('App\Models\User\User');
    }


    // /**
    //  * Get products with quantity
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    //  */
    // public function products()
    // {
    //     return $this->belongsToMany('\App\Models\Product', 'stocks')
    //         ->withPivot('quantity', 'id')
    //         ->withTimestamps()
    //         ->as('stock');
    // }
    // /*--------------Relation End--------------*/
}
