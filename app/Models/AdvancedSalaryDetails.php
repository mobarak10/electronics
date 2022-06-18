<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvancedSalaryDetails extends Model
{
    protected $fillable = [
    	'advanced_salary_id',
    	'adv_amount',
        'installment_amount',
        'note',
        'is_paid',
    ];

    protected $appends = ['total_paid', 'total_due'];

    public function advancedSalary() {
        return $this->belongsTo('App\Models\AdvancedSalary', 'advanced_salary_id', 'id');
    }

    public function advancedSalaryPaidDetails(){
        return $this->hasMany('App\Models\AdvancedSalaryPaidDetails', 'advanced_salary_details_id', 'id');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', 0);
    }

    public function getTotalPaidAttribute(){
        return $this->advancedSalaryPaidDetails->sum('installment_pay');
    }

    public function getTotalDueAttribute(){
        return $this->adv_amount - $this->advancedSalaryPaidDetails->sum('installment_pay');
    }

}
