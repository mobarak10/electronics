<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentCollection extends Model
{
    protected $guarded = [];
    protected $appends = ['total_paid'];
    protected $dates = ['date'];

    /**
     * get hire sale details for installment
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hireSale()
    {
        return $this->belongsTo(HireSale::class);
    }

    /**
     * get party details
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return mixed
     */
    public function getTotalPaidAttribute()
    {
        return ($this->payment_amount + $this->remission + $this->adjustment);
    }
}
