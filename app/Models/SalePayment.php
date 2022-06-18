<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{

    protected $fillable = [
        'sale_id',
        'cash_id',
        'bank_account_id',
        'cheque_number',
        'issue_date',
        'phone_number',
    ];

    /**
     * Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sale()
    {
        return $this->belongsTo('\App\Models\Sale');
    }

    /**
     * Cash
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cash()
    {
        return $this->belongsTo(Cash::class);
    }

    /**
     * Bank Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
}
