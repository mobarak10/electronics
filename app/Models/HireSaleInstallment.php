<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HireSaleInstallment extends Model
{
    protected $fillable = [
        'hire_sale_id',
        'installment_date',
        'installment_amount',
    ];
    protected $dates = ['installment_date'];

    /**
     * get hire sale details
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hireSale()
    {
        return $this->belongsTo(HireSale::class, 'hire_sale_id', 'id');
    }
}
