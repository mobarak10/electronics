<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class DueCollectionController extends Controller
{
    private $meta = [
        'title'   => 'Due collection',
        'menu'    => 'due-collection',
        'submenu' => ''
    ];

    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {
        $sales = Sale::all();

        return view('user.due-collection.index', compact('sales'))->with($this->meta);
    }
}
