<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvancedSalary extends Model
{
    protected $fillable =  [
        'user_id',
    ];

    protected $appends = ['total_installment_paid'];

    public function advancedSalaryDetails() {
        return $this->hasMany('App\Models\AdvancedSalaryDetails', 'advanced_salary_id', 'id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User\User', 'user_id', 'id');
    }

    public function getTotalInstallmentPaidAttribute(){
    	return $this->advancedSalaryDetails->sum('total_paid');
    }

}
