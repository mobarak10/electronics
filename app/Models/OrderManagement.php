<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderManagement extends Model
{
    protected $table = 'order_management';
    protected $fillable = [
        'party_id',
        'business_id',
        'sr_id',
        'delivery_man_id ',
        'status ',
        'order_no'
    ];

    public function orderManagementDetails(){
        return $this->hasMany("App\Models\OrderManagementDetails");
    }

    public function customers(){
        return $this->belongsTo('App\Models\Party', 'party_id', 'id');
    }

    public function users(){
        return $this->belongsTo('App\Models\User\User', 'sr_id', 'id');
    }

    public function deliveryMan(){
        return $this->belongsTo('App\Models\User\User', 'delivery_man_id', 'id');
    }
}
