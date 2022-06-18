<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionIn extends Model
{
    protected $fillable = [
        'date',
        'voucher_no',
        'business_id',
        'user_id',
        'note',
    ];

    protected $dates = ['date'];

    /**
     * Purchase details
     * @return HasMany
     */
    public function details()
    {
        return $this->hasMany('\App\Models\ProductionInDetails');
    }

    public function user()
    {
        return $this->belongsTo('\App\Models\User\User', 'user_id', 'id');
    }
}
