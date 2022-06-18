<?php

namespace App\Helpers;

use Auth;


trait PermissionPolicy {

    private $actions;
    
    public function hasPermission($menu_id, $action) {
        $this->actions = collect([]);

        foreach(Auth::user()->roles as $role) {
            $this->actions[] = $role->permissions->where('menu_id', $menu_id)->pluck('slug');
        }

        // return $this->actions->collapse()->unique()->all();
        return in_array($action, $this->actions->collapse()->unique()->all());
    }

}
