<?php

namespace App\Policies;

use App\Models\User\User;
use App\Models\Stock;
use App\Helpers\PermissionPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockPolicy
{
    use HandlesAuthorization, PermissionPolicy;
    
    private $menuID;

    public function __construct() {
        $this->menuID = MaxSOP::getMenu('stock')->id;
    }

    /**
     * Determine whether the user can view any stocks.
     *
     * @param  \App\Models\User\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the stock.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Stock  $stock
     * @return mixed
     */
    public function view(User $user, Stock $stock)
    {
        //
    }

    /**
     * Determine whether the user can create stocks.
     *
     * @param  \App\Models\User\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the stock.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Stock  $stock
     * @return mixed
     */
    public function update(User $user, Stock $stock)
    {
        return $this->hasPermission($this->menuID, 'edit');
    }

    /**
     * Determine whether the user can delete the stock.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Stock  $stock
     * @return mixed
     */
    public function delete(User $user, Stock $stock)
    {
        return $this->hasPermission($this->menuID, 'destroy');
    }

    /**
     * Determine whether the user can restore the stock.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Stock  $stock
     * @return mixed
     */
    public function restore(User $user, Stock $stock)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the stock.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Stock  $stock
     * @return mixed
     */
    public function forceDelete(User $user, Stock $stock)
    {
        //
    }
}
