<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {
    protected $fillable = ['meta_key', 'meta_value', 'meta_description', 'business_id'];

    public function business(){
        return $this->belongsTo('App\Models\Business');
    }

    /**
     * Media
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function media() {
        return $this->belongsTo('App\Models\Media', 'meta_value', 'code');
    }
}
