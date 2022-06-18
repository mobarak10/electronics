<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class LoanAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'address',
    ];

    /* ==== Relationship Start ==== */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
    /* ==== Relationship End ==== */

    /* ==== Local Scope End ==== */
    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeAddTotalLoan(Builder $query): Builder
    {
        return $query->addSelect([
            'total_loan' => Loan::query()
            ->selectRaw('SUM(amount)')
            ->whereColumn('loan_account_id', 'loan_accounts.id'),
        ]);
    }

    /**
     * Add total paid
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAddTotalPaid(Builder $query): Builder
    {
        return $query->addSelect([
            'total_paid' => Loan::query()
                ->selectRaw("SUM(loan_installments.amount)")
                ->whereColumn('loan_account_id', 'loan_accounts.id')
                ->join('loan_installments', 'loans.id', '=', 'loan_installments.loan_id')
        ]);
    }

    /**
     * Add total adjustment
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAddTotalAdjustment(Builder $query): Builder
    {
        return $query->addSelect([
            'total_adjustment' => Loan::query()
                ->selectRaw("SUM(loan_installments.adjustment)")
                ->whereColumn('loan_account_id', 'loan_accounts.id')
                ->join('loan_installments', 'loans.id', '=', 'loan_installments.loan_id')
        ]);
    }

    /**
     * Add total due
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAddTotalDue(Builder $query): Builder
    {
        return $query->addSelect([
            'total_due' => Loan::query()
                ->selectRaw("loans.amount + IF (ISNULL(SUM(loan_installments.amount)), 0, SUM(loan_installments.amount)) + IF (ISNULL(SUM(loan_installments.adjustment)), 0, SUM(loan_installments.adjustment))")
                ->whereColumn('loan_account_id', 'loan_accounts.id')
                ->join('loan_installments', 'loans.id', '=', 'loan_installments.loan_id', 'left outer')
                ->groupBy('loans.amount')
                ->limit(1)
        ]);
    }

    /* ==== Local Scope End ==== */
}
