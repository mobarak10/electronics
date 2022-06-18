<?php

namespace App\Helpers;

class PaginatorHelper
{
    public static function paginatorHelper($data)
    {
        return $data->perPage() * ($data->currentPage() - 1);
    }
}
