<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Bank extends Model
{
    use SoftDeletes;
	protected $fillable = [
		'code',
		'name',
		'slug',
		'status'
	];
    public function bankAccounts() {
    	return $this->hasMany('App\Models\BankAccount');
    }

}
