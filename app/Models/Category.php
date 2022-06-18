<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = ['code', 'name', 'slug', 'description', 'active', 'business_id'];

    /*--------------------Scope Start--------------------*/
    /**
     * Active Categories
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
    /**
     * Inactive Categories
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

    /*--------Relation Start---------*/

    public function brands()
    {
        return $this->belongsToMany('\App\Models\Brand');
    }

    /*--------Relation End---------*/


}
