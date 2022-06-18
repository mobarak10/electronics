<?php

namespace App\Models;
use App\Helpers\Converter;
use Illuminate\Database\Eloquent\Model;

class OrderManagementDetails extends Model
{
    protected $fillable = [
    	'order_management_id',
    	'product_id',
    	'warehouse_id',
    	'quantity',
    ];
    protected $appends = ['order_total_quantities_in_unit'];

    public function products(){
    	return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function warehouses(){
    	return $this->belongsTo('App\Models\Warehouse', 'warehouse_id', 'id');
    }

    /**
     * Purchase total quantity in unit
     * @return array
     */
    public function getOrderTotalQuantitiesInUnitAttribute()
    {
        return Converter::convert($this->quantity, $this->products->unit_code, 'd');
    }

    /**
     * Purchase total quantity
     * @return int
     */
    // public function getOrderTotalQuantitiesAttribute()
    // {
    //     $total_quantities = 0;

    //     foreach ($this->orderManagementDetails as $quantities) {
    //         $total_quantities += $quantities->quantity;
    //     }

    //     return $total_quantities;
    // }
}
