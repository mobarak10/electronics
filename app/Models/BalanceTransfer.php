<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceTransfer extends Model {

    protected $fillable = ['code', 'transfer_date', 'transfer_from', 'transfer_from_id', 'transfer_to', 'transfer_to_id', 'cheque_no', 'cheque_issue_date', 'amount', 'note', 'operator', 'business_id'];

    /**
     * Get all of the ledgers for the balance-transfer.
     */
    public function ledgers() {
        return $this->morphToMany('App\Models\Ledger', 'ledgerable');
    }
}
