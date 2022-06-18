<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model {
    
    public function permissions() {
        return $this->hasMany('App\Models\Permission');
    }
    

}
