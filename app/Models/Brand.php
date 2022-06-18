<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'code', 'party_id', 'slug', 'active', 'business_id'];

    /*--------------------Scope Start--------------------*/
    /**
     * Active Brands
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
    /**
     * Inactive Brands
     * @param $query
     * @return mixed
     */
    public function scopeInactive($query)
    {
        return $query->where('active', 0);
    }
    /*--------------------Scope End--------------------*/

    /*Accessor Start*/
    /**
     * Get Active Value
     * @return mixed
     */
    public function getActiveValueAttribute()
    {
        return config('coderill.common.input_field.active')[$this->active];
    }
    /*Accessor End*/

    /*Relation Start*/

    /**
     * Get Party
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function party()
    {
        return $this->belongsTo('\App\Models\Party');
    }

    /**
     * Get Categories
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany('\App\Models\Category');
    }




    /*Relation End*/
}
