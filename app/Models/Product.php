<?php

namespace App\Models;

use App\Helpers\Converter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'brand_id',
        'category_id',
        'code',
        'barcode',
        'description',
        'model',
        'name',
        'party_id',
        'purchase_price',
        'sale_price',
        'wholesale_price',
        'slug',
        'stock_alert',
        'unit_id',
        'vat',
        'active',
        'business_id'
    ];

    //to add code into json
    protected $appends = [
        'unit_code',
        'custom_price',
        'product_unit_labels',
        'total_product_quantity',
        'total_product_quantity_warehouse_wise'
    ];


    /*-----Scope Start-----*/

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', 0);
    }

    /*------Scope End-----*/

    /**
     * Get Warehouse with quantity
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function warehouses()
    {
        return $this->belongsToMany('\App\Models\Warehouse', 'stocks')
            ->withPivot('quantity', 'id', 'average_purchase_price')
            ->withTimestamps()
            ->as('stock');
    }

    // public function warehouse(){
    //     return $this->belongsTo('App\Models\Warehouse', 'warehouse_id');
    // }

    public function stock()
    {
        return $this->hasMany('\App\Models\Stock');
    }

    public function damageStock()
    {
        return $this->hasMany('\App\Models\DamageStock');
    }

    /**
     * Get Warehouse with quantity
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function damageProductsInWarehouse()
    {
        return $this->belongsToMany('\App\Models\Warehouse', 'damage_stocks')
            ->withPivot('quantity', 'id', 'operator_id')
            ->withTimestamps()
            ->as('damage');
    }

    /**
     * Get purchases products
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function purchaseProductsInWarehouse()
    {
        return $this->belongsToMany('\App\Models\Warehouse', 'purchase_quantities')
            ->withPivot('quantity', 'purchase_details_id', 'product_id', 'free_quantity')
            ->withTimestamps()
            ->as('quantity');
    }

    /**
     * Supplier
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo('\App\Models\Party', 'party_id');
    }

    /**
     * Brand
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo('\App\Models\Brand', 'brand_id');
    }

    /**
     * Category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('\App\Models\Category', 'category_id');
    }

    /**
     * Unit
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit()
    {
        return $this->belongsTo('\App\Models\Unit', 'unit_id');
    }

    /**
     * All sell details of product
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function saleDetails()
    {
        return $this->hasMany('\App\Models\SaleDetails');
    }

    /**
     * All sell details of product
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function saleDetailsWarehouse()
    {
        return $this->hasMany('\App\Models\SaleDetailsWarehouse');
    }

    /**
     * All return quantity of product
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function saleReturnQuantity()
    {
        return $this->hasMany('\App\Models\SaleReturnQuantity');
    }

    /**
     * All return details of product
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function saleReturnProduct()
    {
        return $this->hasMany('\App\Models\SaleReturnProduct');
    }

    /**
     * Get unit code
     * @return mixed
     */
    public function getUnitCodeAttribute()
    {
        return $this->unit->code;
    }

    public function getCustomPriceAttribute()
    {
        return 0;
    }

    public function getTotalProductQuantityAttribute()
    {
        return $this->stock()->sum('quantity');
    }

    public function getTotalProductQuantityWarehouseWiseAttribute()
    {
        return $this->warehouses->sum(function($warehouse){
                return $warehouse->stock->quantity;
            });
    }

    /**
     * Get Product unit labels
     * @return array
     */
    public function getProductUnitLabelsAttribute()
    {
        return explode('/', $this->unit->labels);
    }
}
