<?php

namespace App\Models\User;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Helpers\CustomMetaAccessor;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Business;

class User extends Authenticatable
{
    use SoftDeletes;
    use HasApiTokens, Notifiable, CustomMetaAccessor;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'username', 'email', 'password', 'account_type', 'activation_token', 'thumbnail', 'status', 'business_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeByActivationColumns(Builder $builder, $email, $token) {
        return $builder->where('email', $email)->where('activation_token', $token);
    }

    public function getNameAttribute($value) {
        return ucfirst($value);
    }

    /**
     * Media
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function media() {
        return $this->belongsTo('\App\Models\Media', 'thumbnail', 'code');
    }

    /**
     * business
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business() {
        return $this->belongsTo('\App\Models\Business', 'business_id');
    }

    /**
     *
     */
    public function accessLogs() {
        return $this->hasMany('App\Models\Accesslog', 'user', 'id')->where('genus', '=', 'web');
    }

    /**
     * Get Party Meta
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function metas() {
        return $this->morphMany('\App\Models\Meta', 'metaable');
    }

    /**
     * Delete metas
     * @return bool|void|null
     * @throws \Exception
     */
    public function delete() {
        if(parent::delete()) {
            $this->metas()->delete();
        }
    }

    public function roles() {
        return $this->belongsToMany('App\Models\Role');
    }

    public function advancedSalaries(){
        return $this->hasOne('App\Models\AdvancedSalary');
    }

    public function salaries(){
        return $this->hasMany('App\Models\Salary', 'user_id', 'id');
    }


    /**
     * Sells for salesman
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sells()
    {
        return $this->hasMany('\App\Models\Sale', 'salesman_id');
    }
}
