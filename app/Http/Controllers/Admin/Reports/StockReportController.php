<?php

namespace App\Http\Controllers\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\DamageStock;
use App\Models\Warehouse;
use App\Models\Stock;
use App\Models\Party;
use App\Models\Business;
use Illuminate\Database\Eloquent\Builder;

class StockReportController extends Controller {

    private $meta = [
        'title'   => 'Stock Report',
        'menu'    => 'report',
        'submenu' => ''
    ];

    public function __construct() {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function currentStock() {
        $parties    = Party::all();
        $businesses = Business::all();
        $warehouses = Warehouse::all();
        $products     = null;

        // return request();
        if(request()->search) {
            $products = Product::whereHas('warehouses', function($query){
                $query->where('business_id', request()->businessId);
            })->paginate(15);

            if (request()->warehouseId){
                $products = Product::whereHas('warehouses', function($query){
                    $query->where('warehouse_id', request()->warehouseId);
                })->paginate(15);
            }

        }

        return view('admin.reports.stock.currentstock', compact('parties', 'products', 'warehouses', 'businesses'))->with($this->meta);

    }

    public function damageStock(){

    	$damage_stock = DamageStock::all();
        $businesses = Business::all();
    	$warehouses = Warehouse::all();

    	// return request();
    	if (request()->search) {
    		$damage_stock = DamageStock::where('warehouse_id', '=', request()->warehouseId)->paginate(15);
    	}

    	return view('admin.reports.stock.damagestock', compact('warehouses', 'damage_stock', 'businesses'))->with($this->meta);
    }
}
