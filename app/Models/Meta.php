<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{

    protected $fillable = ['meta_key', 'meta_value', 'meta_description'];

    /**
     * Get the owning metaable model.
     */
    public function metaable() {
        return $this->morphTo();
    }
    
}
