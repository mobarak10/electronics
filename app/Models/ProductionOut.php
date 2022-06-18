<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionOut extends Model
{
    protected $fillable = [
        'date',
        'voucher_no',
        'business_id',
        'user_id',
        'note',
        ];
}
