<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {

    // public $timestamps = false;
    protected $fillable = ['menu_id'];

    public function menu() {
        return $this->belongsTo('App\Models\Menu', 'menu_id');
    }


}
