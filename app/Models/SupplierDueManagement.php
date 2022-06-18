<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierDueManagement extends Model
{
    protected $fillable = [
        'party_id',
        'date',
        'amount',
        'paid_from',
        'cash_id',
        'bank_account_id',
        'check_issue_date',
        'check_number',
        'user_id',
        'description',
        'current_balance',
        'business_id',
    ];

    protected $dates = ['date'];

    /**
     * get party details
     *
     * @return void
     */
    public function party()
    {
        return $this->belongsTo(Party::class);
    }
}
