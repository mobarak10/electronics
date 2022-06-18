<?php

namespace App\Http\Controllers\User;

use App\Exceptions\PermissionForPropertyIsNotDeclaredInControllerException;
use App\Imports\StocksImport;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Warehouse;
use App\Models\Product;
use App\Models\DamageStock;
use App\Models\Stock;
use App\Helpers\Converter;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Party;
use Auth;
use Maatwebsite\Excel\Facades\Excel;

class StockController extends Controller
{

    private $meta = [
        'title'   => 'Current Stock',
        'menu'    => 'stock',
        'submenu' => ''
    ];

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $business_id = Auth::user()->business_id;
        $products = Product::with('stock')->where('business_id', $business_id)->paginate(27);
        $categories = Category::active()->where('business_id', $business_id)->get();
        $brands = Brand::active()->where('business_id', $business_id)->get();
        $warehouses = Warehouse::all();

        if(request()->search) {
            $products = [];
            // set condition
            $where = [
                ['business_id', '=', $business_id],
            ];

            foreach(request()->filter_by as $key => $value) {
                if($value != null) {
                    if($key == 'name') {
                        $where[] = [$key, 'like', '%' . $value . '%'];
                    } else {
                        $where[] = [$key, '=', $value];
                    }
                }
            }

            if (request()->warehouse_id and !empty(request()->warehouse_id)) {
                $warehouse_id = request()->warehouse_id;
                $products = Product::query()
                    ->with([
                        'warehouses' => function($query) use ($warehouse_id){
                            $query->where('warehouses.id', $warehouse_id);
                        }
                    ])
                ->whereHas('warehouses', function ($query) use ($warehouse_id) {
                    $query->where('warehouse_id', $warehouse_id);
                });
                // get data
                $products = $products->where($where)->paginate(50);
            }else{
                $products = Product::where($where)->paginate(50);
            }
        }

        return view('user.stock.index', compact('products', 'warehouses', 'brands', 'categories'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @return Application|Factory|View
     */
    public function excel()
    {
        return view('user.stock.excel')->with($this->meta);
    }

    public function import()
    {
        Excel::import(new StocksImport, request()->file('stock_excel'));

        return redirect('/')->with('success', 'All good!')->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $business_id = Auth::user()->business_id;
        $product = Product::where('business_id', $business_id)->find($id);
        return view('user.stock.edit', compact('product'))->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        // get product details
        $product = Product::find($id);

        foreach($request->quantities as $stockID => $quantity) {
            // set quantity
            foreach($quantity as $i => $q) {
                if(empty($q)) {
                    $quantity[$i] = 0;
                }
            }

            // convert stock quantity into unit
            $quantity = Converter::convert(join('/', $quantity), $product->unit_code)['result'];

            // stock update
            $stock = Stock::findOrFail($stockID);
            $stock->quantity = $quantity;

            if ($stock->save()) {
                // if product quantity is zero(0) remove that product
                if ($quantity <= 0) {
                    $product = Product::find($id);
                    $product->warehouses()->detach($stock->warehouse_id);
                }
            }
        }

        // set flase message
        session()->flash('success', 'Quantity updated successfully.');

        // return to view
        return redirect(route('stock.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getProductsFromWarehouse(Request $request){
        $products = Warehouse::with('products.warehouses')->where('id', $request->id)->first()->products;
        return response()->json($products);
    }
}
