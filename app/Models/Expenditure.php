<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenditure extends Model
{

    // which field in fillable
	protected $fillable = [
		'gl_account_id',
		'gl_account_head_id',
        'date',
        'amount',
		'cash_id',
		'bank_id',
		'bank_account_id',
		'note',
		'user_id',
        'business_id'
	];

	/**
	 * get all the value from User table in Expense table
	**/
	public function user() {
		return $this->belongsTo('App\Models\User\User');
	}

	public function glAccount() {
		return $this->belongsTo('App\Models\GlAccount');
	}

	public function glAccountHead() {
		return $this->belongsTo('App\Models\GlAccountHead');
	}

	public function cash(){
		return $this->belongsTo('App\Models\Cash');
	}

	public function bankAccount(){
		return $this->belongsTo('App\Models\BankAccount', 'bank_account_id', 'id');
	}

	// /**
    //  * Get all of the ledgers for the Expense.
    //  */
    // public function ledgers() {
    //     return $this->morphToMany('App\Models\Ledger', 'ledgerable');
	// }


}
