<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetailDueCollection extends Model
{
    protected $fillable = ['sale_id', 'date', 'paid', 'discount'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
