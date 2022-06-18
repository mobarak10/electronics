<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;
    //to add code into json
    protected $appends = ['unit_length'];

    /**
     * Products
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products() {

        return $this->hasMany('\App\Models\Product');
    }

    /**
     * Get unit length
     * @return mixed
     */
    public function getUnitLengthAttribute() {

        return count(explode('/', $this->relation));
    }
}
