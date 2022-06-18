<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseReturn extends Model
{
    protected $fillable = [
        'return_no',
        'user_id',
        'party_id',
        'warehouse_id',
        'date',
        'subtotal',
        'discount',
        'paid',
        'paid_from',
        'cash_id',
        'bank_account_id',
        'note',
        'business_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

//    protected $appends = [
//        'purchase_return_subtotal',
//        'purchase_return_total'
//    ];

    /**
     * @return HasMany
     */
    public function purchaseReturnProducts(): HasMany
    {
        return $this->hasMany(PurchaseReturnProduct::class);
    }


    /*---Accessor Start----*/
    /**
     * get user details
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * get purchase details
     * @return BelongsTo
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get associated party
     *
     * @return BelongsTo
     */
    public function party()
    {
        return $this->belongsTo(Party::class);
    }
    /*---Accessor End----*/
}
