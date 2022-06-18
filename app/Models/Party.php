<?php

namespace App\Models;

use App\Helpers\CustomMetaAccessor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Party extends Model
{
    use SoftDeletes;
    use CustomMetaAccessor;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'thumbnail',
        'balance',
        'type',
        'address',
        'division',
        'district',
        'thana',
        'description',
        'code',
        'type',
        'genus',
        'active',
        'business_id',
        'zone_id',
    ];

    /*Scope Start*/

    /**
     * Active Parties
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Suppliers
     * @param $query
     * @return mixed
     */
    public function scopeSuppliers($query)
    {
        return $query->where('genus', 'supplier');
    }

    /**
     * Customers
     * @param $query
     * @return mixed
     */
    public function scopeCustomers($query)
    {
        return $query->where('genus', 'customer');
    }
    /*Scope End*/

    /*-------------------Relation Start-------------------*/

    /**
     * Media
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function media()
    {
        return $this->belongsTo('\App\Models\Media', 'thumbnail', 'code');
    }

    /**
     * Get Brands from suppliers
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function brands()
    {
        return $this->hasMany('\App\Models\Brand');
    }

    /**
     * Get Products from suppliers
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('\App\Models\Product');
    }


    /**
     * Sells for customer
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sells()
    {
        return $this->hasMany('\App\Models\Sale', 'party_id');
    }

    /**
     * Get Party Meta
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function metas()
    {
        return $this->morphMany('\App\Models\Meta', 'metaable');
    }

    /**
     * Get all of the ledgers for the party.
     */
    public function ledgers() {
        return $this->morphToMany('App\Models\Ledger', 'ledgerable');
    }


    /**
     * Supplier purchases
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchases()
    {
        return $this->hasMany('\App\Models\Purchase');
    }
    /*-------------------Relation End-------------------*/


    /**
     * Delete metas
     * @return bool|void|null
     * @throws \Exception
     */
    public function delete()
    {
        if(parent::delete()) {
            $this->metas()->delete();
        }
    }
}
