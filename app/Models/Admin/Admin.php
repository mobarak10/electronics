<?php

namespace App\Models\Admin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Helpers\CustomMetaAccessor;

class Admin extends Authenticatable
{
    use Notifiable, CustomMetaAccessor;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'username', 'email', 'password', 'thumbnail', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Media
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function media() {
        return $this->belongsTo('\App\Models\Media', 'thumbnail', 'code');
    }

    /**
     * Get Party Meta
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function metas() {
        return $this->morphMany('\App\Models\Meta', 'metaable');
    }

    /**
     * Get setting Meta
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function settings() {
        return $this->morphMany('\App\Models\Setting', 'business_id');
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

    /**
     * 
     */
    public function accessLogs() {
        return $this->hasMany('App\Models\Accesslog', 'user', 'id')->where('genus', '=', 'admin');
    }

    public function getNameAttribute($value) {
        return ucfirst($value);
    }

    public function hasAccess(array $permissions) {
        foreach($this->roles as $role) {
            if($role->hasAccess($permissions)) {
                return true;
            }
        }

        return false;
    }

}
