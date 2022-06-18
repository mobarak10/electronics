<?php

namespace App\Models;

use App\Helpers\CustomMetaAccessor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    use CustomMetaAccessor;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'thumbnail',
        'balance',
        'type',
        'address',
        'division',
        'district',
        'thana',
        'description',
        'code',
        'type',
        'active',
        'business_id'
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
