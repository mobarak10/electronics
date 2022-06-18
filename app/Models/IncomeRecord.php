<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeRecord extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'income_sector_id',
        'user_id',
        'income_by',
        'cash_id',
        'bank_id',
        'bank_account_id',
        'description',
        'business_id',
    ];

    protected $dates = ['date'];

    public function incomeSector()
    {
        return $this->belongsTo('App\Models\IncomeSector', 'income_sector_id', 'id');
    }

    public function cash()
    {
        return $this->belongsTo('App\Models\Cash', 'cash_id', 'id');
    }

    public function bankAccount()
    {
        return $this->belongsTo('App\Models\BankAccount', 'bank_account_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo('App\Models\Bank', 'bank_id', 'id');
    }
}
