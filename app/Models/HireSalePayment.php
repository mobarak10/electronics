<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HireSalePayment extends Model
{
    protected $fillable = [
        'hire_sale_id',
        'payment_method',
        'cash_id',
        'bank_account_id',
        'check_number',
        'branch',
        'issue_date',
        'bkash_number',
    ];
}
