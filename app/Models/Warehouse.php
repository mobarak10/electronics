<?php

namespace App\Models;

use App\Helpers\Converter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User\User;

class Warehouse extends Model{
    use SoftDeletes;

    protected $appends = ['product_quantity_in_unit', 'sale_return_quantity_in_unit', 'sale_product_rest_quantity', 'sale_product_rest_quantity_in_unit', 'purchase_quantity_in_unit', 'purchase_return_quantity_in_unit', 'purchase_product_rest_quantity', 'purchase_product_rest_quantity_in_unit', 'purchase_product_rest_quantity_warehouse_wise', 'purchase_product_rest_quantity_warehouse_wise_in_unit', 'sale_return_product_rest_quantity_warehouse_wise', 'sale_return_product_rest_quantity_warehouse_wise_in_unit'];

    /*--------------Relation Start--------------*/

    /**
     * User
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo('App\Models\User\User');
    }

    public function damageStock()
    {
        return $this->belongsTo('App\Models\DamageStock');
    }

    /**
     * Get products with quantity
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('\App\Models\Product', 'stocks')
            ->withPivot('quantity', 'id', 'average_purchase_price')
            ->withTimestamps()
            ->as('stock');
    }

    /**
     * Get damage product with quantity
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function damageProductsInWarehouse()
    {
        return $this->belongsToMany('\App\Models\Product', 'damage_stocks')
            ->withPivot('quantity','id', 'operator_id')
            ->withTimestamps()
            ->as('damage');
    }

    /**
     * Sell details
     */
    public function sellDetails()
    {
        return $this->hasMany(SaleDetails::class, 'warehouse_id', 'id');
    }

    /**
     * Quantities of return (sale details wise)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function saleReturns()
    {
        return $this->belongsToMany('\App\Models\SaleReturn', 'sale_return_quantities')
            ->withPivot('sale_id', 'product_id', 'quantity')
            ->withTimestamps()
            ->as('sale_return_quantity');
    }

    /**
     * Purchased product details
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function purchaseDetails()
    {
        return $this->belongsToMany('\App\Models\PurchaseDetails', 'purchase_quantities')
            ->withPivot('quantity', 'product_id', 'free_quantity')
            ->withTimestamps()
            ->as('quantity');
    }


    /*--------------Relation End--------------*/

    /*--------------Helper Start--------------*/
    /**
     * Convert quantity
     * @param $amount
     * @param $unit_code
     * @param string $format
     * @return array
     */
    public function convertQuantity($amount, $unit_code, $format = 'u')
    {
        return Converter::convert($amount, $unit_code, $format);
    }
    /*--------------Helper End--------------*/


    /*--------------Accessor Start--------------*/

    /**
     * Get Product units
     * @return array
     */
    public function getProductQuantityInUnitAttribute()
    {
        return ($this->stock) ? Converter::convert($this->stock->quantity, Product::find($this->stock->product_id)->unit_code, 'd') : null;
    }

    /**
     * Get Display quantity
     * @return mixed
     */
    public function getDisplayQuantityAttribute()
    {
        return $this->getProductQuantityInUnitAttribute()['display'];
    }


    /**
     * Sale return product rest quantity warehouse wise
     * @return |null
     */
    public function getSaleReturnProductRestQuantityWarehouseWiseAttribute()
    {
        if(!isset($this->quantity->sale_id)) return null;

        $warehouse_id = $this->id;

        $rest_quantity = $this->quantity->quantity;

        $saleReturns = Sale::find($this->quantity->sale_id)->saleReturns;

        foreach ($saleReturns as $saleReturn) {
            $current_product =  $saleReturn->returnProducts->where('product_id', $this->quantity->product_id)->first();

            if (!$current_product) continue;

            $previous_return_quantity =  $current_product->quantities->where('id', $warehouse_id)->first();

            if (!$previous_return_quantity) continue;

                $rest_quantity -= $previous_return_quantity->sale_return_quantity->quantity;
        }

        return $rest_quantity;
    }

    /**
     * Sale return product rest quantity warehouse wise in unit
     * @return |null
     */
    public function getSaleReturnProductRestQuantityWarehouseWiseInUnitAttribute()
    {
        if (!$this->sale_return_product_rest_quantity_warehouse_wise) return null;

        $unit_code = Product::find($this->quantity->product_id)->unit_code;

        return $this->convertQuantity($this->sale_return_product_rest_quantity_warehouse_wise, $unit_code, 'd');

    }

    /**
     * Get sale return quantity
     * @return mixed
     */
    public function getSaleReturnQuantityInUnitAttribute()
    {
        if(!isset($this->quantity->product_id)) return null;

        $unit_code = Product::find($this->quantity->product_id)->unit_code;
        return $this->convertQuantity($this->quantity['quantity'], $unit_code, 'd');
    }

    /**
     * Rest product quantity warehouse wise
     * @return |null
     */
    public function getSaleProductRestQuantityAttribute()
    {
        if(!isset($this->quantity->sale_id)) return null;

        $rest_quantity = $this->quantity->quantity;

        $saleReturns = Sale::find($this->quantity->sale_id)->saleReturns;

        foreach ($saleReturns as $saleReturn) {
            $current_product =  $saleReturn->returnProducts->where('product_id', $this->quantity->product_id)->first();

            if (!$current_product) continue;

            foreach ($current_product->quantities as $quantity) {
                $rest_quantity -= $quantity->sale_return_quantity->quantity;
            }
        }
        return $rest_quantity;
    }

    /**
     * Rest product quantity in units warehouse wise
     * @return array|null
     */
    public function getSaleProductRestQuantityInUnitAttribute()
    {
        if(!isset($this->quantity->product_id)) return null;

        $unit_code = Product::find($this->quantity->product_id)->unit_code;

        return $this->convertQuantity($this->sale_product_rest_quantity, $unit_code, 'd');
    }

    /**
     * Get purchase quantity in unit
     * @return array|null
     */
    public function getPurchaseQuantityInUnitAttribute()
    {
        if (!isset($this->quantity->product_id)) return null;

        return Converter::convert($this->quantity->quantity, $product = Product::find($this->quantity->product_id)->unit_code, 'd');
    }

    /**
     * Return quantity in unit
     * @return array|null
     */
    public function getPurchaseReturnQuantityInUnitAttribute()
    {
        if(!isset($this->purchase_return_quantity->purchase_id)) return null;

        $unit_code = Product::find($this->purchase_return_quantity->product_id)->unit_code;
        return Converter::convert($this->purchase_return_quantity->quantity, $unit_code, 'd');
    }


    /**
     * Get Purchase product rest quantity warehouse wise
     * @return |null
     */
    public function getPurchaseProductRestQuantityWarehouseWiseAttribute()
    {
        if(!isset($this->quantity->purchase_details_id)) return null;

        $warehouse_id = $this->id;

        $rest_quantity = $this->quantity->quantity;

        $purchase_returns = PurchaseDetails::find($this->quantity->purchase_details_id)->purchase->purchaseReturns;

        foreach ($purchase_returns as $purchase_return) {

            $current_product = $purchase_return->purchaseReturnProducts->where('product_id', $this->quantity->product_id)->first();

            if (!$current_product) continue;

            $previous_return_quantity =  $current_product->quantities->where('id', $warehouse_id)->first();

            if (!$previous_return_quantity) continue;

            $rest_quantity -= $previous_return_quantity->purchase_return_quantity->quantity;
        }

        return $rest_quantity;
    }

    /**
     * Get Purchase product rest quantity warehouse wise in unit
     * @return |null
     */
    public function getPurchaseProductRestQuantityWarehouseWiseInUnitAttribute()
    {
        if (!isset($this->purchase_product_rest_quantity_warehouse_wise)) return null;

        $unit_code = Product::find($this->quantity->product_id)->unit_code;

        return Converter::convert($this->purchase_product_rest_quantity_warehouse_wise, $unit_code, 'd');
    }

    /**
     * Purchase product rest quantity
     * @return |null
     */
    public function getPurchaseProductRestQuantityAttribute()
    {
        if(!isset($this->quantity->purchase_details_id)) return null;

        $rest_quantity = $this->quantity->quantity;

        $purchase_returns = PurchaseDetails::find($this->quantity->purchase_details_id)->purchase->purchaseReturns;

        foreach ($purchase_returns as $purchase_return){
            $current_product = $purchase_return->purchaseReturnProducts->where('product_id', $this->quantity->product_id)->first();

            if (!$current_product) continue;

            foreach ($current_product->quantities as $quantity){
                $rest_quantity -= $quantity->purchase_return_quantity->quantity;
            }

        }
        return $rest_quantity;
    }

    /**
     * Purchase product rest quantity
     * @return array|null
     */
    public function getPurchaseProductRestQuantityInUnitAttribute()
    {
        if (!isset($this->quantity->purchase_details_id)) return null;
        $unit_code = Product::find($this->quantity->product_id)->unit_code;

        return Converter::convert($this->purchase_product_rest_quantity, $unit_code, 'd');
    }

    /*--------------Accessor End--------------*/

    /*----------------Scope Start----------------*/

    /**
     * Active Warehouses
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Inactive Warehouses
     * @param $query
     * @return mixed
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    /*----------------Scope End----------------*/



}
