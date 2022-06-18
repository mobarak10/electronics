<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $guarded = [];
    protected $appends = ["stock_price"];

    public function getStockPriceAttribute(){
		if(!isset($this->product->purchase_price)) return 0;
        return $this->product->purchase_price * $this->quantity;
    }

	public function product(){
		return $this->belongsTo('App\Models\Product', 'product_id');
	}

	public function warehouse(){
		return $this->belongsTo('App\Models\Warehouse', 'warehouse_id');
	}

}
