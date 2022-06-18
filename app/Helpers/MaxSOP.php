<?php
namespace App\Helpers;

use App\Models\Menu;

class MaxSOP {

    /**
     * @param $slug
     * @return int
     */
    public static function getMenu($slug) {
        return Menu::where('slug', $slug)->first();
    }

}
