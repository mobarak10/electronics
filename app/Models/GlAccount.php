<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlAccount extends Model
{
    public function operator() {
    	return $this->belongsTo('App\Models\User\User');
    }

    public function allGLAccountHead() {
    	return $this->hasMany('App\Models\GlAccountHead');
    }
}
