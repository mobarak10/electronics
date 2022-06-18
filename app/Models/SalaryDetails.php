<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryDetails extends Model
{
    protected $fillable = [
        'salary_id',
        'purpose',
        'dtls_amount',
        'type',
    ];
}
