<?php

namespace App\Policies;

use App\Models\User\User;
use App\Models\Cash;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Helpers\PermissionPolicy;
use App\Helpers\MaxSOP;

class CashPolicy
{
    use HandlesAuthorization, PermissionPolicy;

    private $menuID;

    public function __construct() {
        $this->menuID = MaxSOP::getMenu('cash')->id;
    }
    
    /**
     * Determine whether the user can view any cashes.
     *
     * @param  \App\Models\User\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the cash.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Cash  $cash
     * @return mixed
     */
    public function view(User $user, Cash $cash) {
        return $this->hasPermission($this->menuID, 'show');
    }

    /**
     * Determine whether the user can create cashes.
     *
     * @param  \App\Models\User\User  $user
     * @return mixed
     */
    public function create(User $user) {
        return $this->hasPermission($this->menuID, 'create');
    }

    /**
     * Determine whether the user can update the cash.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Cash  $cash
     * @return mixed
     */
    public function update(User $user, Cash $cash) {
        return $this->hasPermission($this->menuID, 'edit');
    }

    /**
     * Determine whether the user can delete the cash.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Cash  $cash
     * @return mixed
     */
    public function delete(User $user, Cash $cash) {
        return $this->hasPermission($this->menuID, 'destroy');
    }

    /**
     * Determine whether the user can restore the cash.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Cash  $cash
     * @return mixed
     */
    public function restore(User $user, Cash $cash)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the cash.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Cash  $cash
     * @return mixed
     */
    public function forceDelete(User $user, Cash $cash)
    {
        //
    }

}
