<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class ClosingBalance extends Model
{
    protected $fillable = [
        'date',
        'user_id',
        'amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
