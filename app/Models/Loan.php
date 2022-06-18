<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'loan_account_id',
        'date',
        'amount',
        'expired_date',
        'note',
        'transactionable_type',
        'transactionable_id',
    ];

    /* ==== Local Scope Start ==== */

    /**
     * Add paid
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAddPaid(Builder $query): Builder
    {
        return $query->addSelect([
            'paid' => LoanInstallment::selectRaw("IF (ISNULL(SUM(amount)), 0, SUM(amount))")
                ->whereColumn('loan_id', 'loans.id')
        ]);
    }

    /**
     * Add adjustment
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAddAdjustment(Builder $query): Builder
    {
        return $query->addSelect([
            'adjustment' => LoanInstallment::selectRaw("IF (ISNULL(SUM(adjustment)), 0, SUM(adjustment))")
                ->whereColumn('loan_id', 'loans.id')
        ]);
    }

    /**
     * Add due
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAddDue(Builder $query): Builder
    {
        return $query->addSelect([
            'due' => LoanInstallment::selectRaw("loans.amount + IF (ISNULL(SUM(amount + adjustment)), 0, SUM(amount + adjustment))")
                ->whereColumn('loan_id', 'loans.id'),
        ]);
    }

    /**
     * Add loan account name
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAddLoanAccountName(Builder $query): Builder
    {
        return $query->addSelect([
            'loan_account_name' => LoanAccount::select('name')
                ->whereColumn('loan_account_id', 'loan_accounts.id')
                ->limit(1)
        ]);
    }

    /**
     * Add payment type formatted column
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAddPaymentMethod(Builder $query): Builder
    {
        return $query->selectSub("IF(
                    loans.transactionable_type = 'App\\\Models\\\Cash',
                    'cash',
                    IF(
                        loans.transactionable_type = 'App\\\Models\\\BankAccount',
                        'bank_account',
                        'unknown'
                    )
                )", 'payment_method');
    }

    /* ==== Local Scope End ==== */

    /* ==== Relationship Start ==== */

    /**
     * Get associated loan account
     *
     * @return BelongsTo
     */
    public function loanAccount(): BelongsTo
    {
        return $this->belongsTo(LoanAccount::class);
    }

    /**
     * Get related loan installment
     *
     * @return HasMany
     */
    public function loanInstallments(): HasMany
    {
        return $this->hasMany(LoanInstallment::class);
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
