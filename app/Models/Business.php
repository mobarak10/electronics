<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\CustomMetaAccessor;

class Business extends Model {
    use SoftDeletes, CustomMetaAccessor;

    protected $fillable = [
    	'name', 
    	'thumbnail', 
    	'description', 
    	'address', 
    	'phone', 
    	'email'
    ];

    public function user(){
        return $this->hasMany('App\Models\User\User', 'business_id');
    }

    /**
     * Media
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function media() {
        return $this->belongsTo('\App\Models\Media', 'thumbnail', 'code');
    }

   
}
