<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\CustomMetaAccessor;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Salary extends Model
{
    use HasApiTokens, Notifiable, CustomMetaAccessor;

    protected $fillable =[
        'user_id',
        'salary_of_month_year',
        'given_date',
    ];

    protected $dates = ['salary_of_month_year'];

    public function salaryDetails(){
        return $this->hasMany('App\Models\SalaryDetails', 'salary_id', 'id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User\User', 'user_id', 'id');
    }
}
