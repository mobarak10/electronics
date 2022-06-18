<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDueManagement extends Model
{
    protected $fillable = [
        'customer_id',
        'date',
        'amount',
        'paid_from',
        'brand_id',
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
     * get customer details
     *
     * @return void
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * get brands details
     *
     * @return void
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
