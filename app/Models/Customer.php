<?php

namespace App\Models;

use App\Helpers\CustomMetaAccessor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use CustomMetaAccessor;
    protected $fillable = [
        'code',
        'name',
        'description',
        'phone',
        'balance',
        'email',
        'type',
        'credit_limit',
        'address',
        'thumbnail',
        'active',
        'business_id',
    ];


    /**
     * Get Party Meta
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function metas()
    {
        return $this->morphMany('\App\Models\Meta', 'metaable');
    }

    /**
     * Get all of the ledgers for the customer.
     */
    public function ledgers() {
        return $this->morphToMany('App\Models\Ledger', 'ledgerable');
    }
}
