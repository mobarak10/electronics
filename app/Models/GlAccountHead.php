<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlAccountHead extends Model
{
    public function glAccount(){
    	return $this->belongsTo('App\Models\GlAccount', 'gl_account_id');
    }

    public function operator(){
    	return $this->belongsTo('App\Models\User\User');
    }
}
