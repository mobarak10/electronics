<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\DamageStock;
use App\Models\Warehouse;
use App\Models\Stock;
use App\Models\Product;
use App\Helpers\Converter;
use App\Helpers\unique;
use Auth;

class DamageStockController extends Controller
{

    private $meta = [
        'title' => 'Damage Stock',
        'menu' => 'stock',
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
    public function index(){
        $business_id = Auth::user()->business_id;
        $damage_stocks = DamageStock::where('business_id', $business_id)->paginate(15);
        return view('user.stock.damage.index', compact('damage_stocks'))->with($this->meta);

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
        $business_id = Auth::user()->business_id;
        $damageStock = DamageStock::where('business_id', $business_id)->find($id);
        return view('user.stock.damage.edit', compact('damageStock'))->with($this->meta);
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
        return view('user.stock.damage.addDamage', compact('product'))->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $business_id = Auth::user()->business_id;
        // get product details
        $product = Product::find($id);

        foreach($request->quantities as $warehouse => $quantity) {
            // set quantity
            foreach($quantity as $i => $q) {
                if(empty($q)) {
                    $quantity[$i] = 0;
                }
            }


            // convart to lowest unit
            $lowQuantity = Converter::convert(join('/', $quantity), $product->unit_code)['result'];

            //if lowest quantity is greater than 0 then insert into damage_stocks table
            if($lowQuantity > 0) {
                // insert damage stock
                $damageStock = [
                    'quantity'    => $lowQuantity,
                    'operator_id' => Auth::user()->id,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
                $damageStock['business_id'] = Auth::user()->business_id;

                $product->damageProductsInWarehouse()->attach($warehouse, $damageStock);

                /*
                update stock table
                */

                // get the stock table quantity
                $main_quantity = $product->warehouses->where('id', $warehouse)->first()->stock->quantity;

                // substract damage quantity from stock table quantity
                $current_quantity = $main_quantity - $lowQuantity;

                // if current quantity is greater than 0 then update stock table quantity
                if ($current_quantity > 0 ) {
                    $product->warehouses()->updateExistingPivot($warehouse, [
                        'quantity' => $current_quantity
                    ]);
                // if current quantity is smaller than 0 then detach/delete stock table quantity
                }else{
                     $product->warehouses()->detach($warehouse);
                }

            }

        }
        if ($product->save()) {
            return redirect(route('damageStock.index'))->with($this->meta);
        }
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

    public function editDamage($id){
        $business_id = Auth::user()->business_id;
        $damage_stock = DamageStock::where('business_id', $business_id)->find($id);
        $product = Product::where('id', $damage_stock->product_id)->first();
        return view('user.stock.damage.edit', compact('damage_stock', 'product'))->with($this->meta);
    }

    public function updateDamage(Request $request, $id){

        //
        $damage_stock = DamageStock::find($id);

        //convert stock quantity into unit
        $quantity = Converter::convert(join('/', $request->quantity), $damage_stock->product->unit_code)['result'];

        $damage_stock->quantity = $quantity;
        $previous_quantity = $damage_stock->getOriginal('quantity');

        /*
        update stock table
        */

        // get the stock table quantity
        $product = Product::find($damage_stock->product_id);
        $main_quantity = $product->warehouses->where('id', $damage_stock->warehouse_id)->first()->stock->quantity;

        // substract damage quantity from stock table quantity if previous qunatity is smaller than now quantity
        if ($damage_stock->quantity > $previous_quantity) {

            $current_quantity = $main_quantity - ($damage_stock->quantity - $previous_quantity);

            $product->warehouses()->updateExistingPivot($damage_stock->warehouse_id, [
                'quantity' => $current_quantity
            ]);
        }
        //add damage quantity from stock table quantity if previous qunatity is greater than now quantity
        elseif($damage_stock->quantity < $previous_quantity){

            $current_quantity = $main_quantity + ($previous_quantity - $damage_stock->quantity);

            $product->warehouses()->updateExistingPivot($damage_stock->warehouse_id, [
                'quantity' => $current_quantity
            ]);
        }

        // if product quantity is zero(0) remove that product
        if ($quantity > 0) {
            $product = Product::find($damage_stock->product_id);
            $product->damageProductsInWarehouse()->updateExistingPivot($damage_stock->warehouse_id, [
                'quantity' => $quantity
            ]);
        }
        //if product quantity is zero(0) remove that product
        elseif ($quantity <= 0) {
            $product = Product::find($damage_stock->product_id);
            $product->damageProductsInWarehouse()->detach($damage_stock->warehouse_id);
        }

        session()->flash('success', 'Damage product updated successfuly.');
        return redirect(route('damageStock.index'));
    }
}

