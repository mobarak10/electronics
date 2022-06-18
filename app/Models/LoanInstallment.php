<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LoanInstallment extends Model
{
    protected $fillable = [
        'loan_id',
        'date',
        'amount',
        'adjustment',
        'note',
        'transactionable_type',
        'transactionable_id',
    ];

    /* ==== Mutator Start ==== */

    public function setAdjustmentAttribute($value)
    {
        $this->attributes['adjustment'] = $value ?? 0;
    }

    /* ==== Mutator End ==== */

    /* ==== Local Scope Start ==== */

    /**
     * Add payment type formatted column
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAddPaymentMethod(Builder $query): Builder
    {
        return $query->selectSub("IF(
                    loan_installments.transactionable_type = 'App\\\Models\\\Cash',
                    'cash',
                    IF(
                        loan_installments.transactionable_type = 'App\\\Models\\\BankAccount',
                        'bank_account',
                        'unknown'
                    )
                )", 'payment_method');
    }

    /* ==== Local Scope End ==== */

    /* ==== Relationship Start ==== */

    /**
     * Get associated loan
     *
     * @return BelongsTo
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Get transaction
     *
     * @return MorphTo
     */
    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    /* ==== Relationship End ==== */
}
