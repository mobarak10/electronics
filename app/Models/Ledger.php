<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{

    protected $fillable = ['date', 'description', 'note', 'debit', 'credit', 'balance'];
    protected $dates = ['date'];

    /**
     * Get all of the cashes that are assigned this ledger.
     */
    public function cashes()
    {
        return $this->morphedByMany('App\Models\Cash', 'ledgerable');
    }

    /**
     * Get all of the bank-accounts that are assigned this ledger.
     */
    public function bankAccounts()
    {
        return $this->morphedByMany('App\Models\BankAccount', 'ledgerable');
    }

    /**
     * Get all of the balance-transfers that are assigned this ledger.
     */
    public function balanceTransfers()
    {
        return $this->morphedByMany('App\Models\BalanceTransfer', 'ledgerable');
    }

    /**
     * Get all of the balance-transfers that are assigned this ledger.
     */
    /* public function expenses() {
        return $this->morphedByMany('App\Models\Expense', 'ledgerable');
    } */

    /**
     * Get all of the balance-transfers that are assigned this ledger.
     */
    public function parties()
    {
        return $this->morphedByMany('\App\Models\Party', 'ledgerable');
    }
}
