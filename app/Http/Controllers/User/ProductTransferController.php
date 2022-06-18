<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\ProductTransfer;
use App\Models\Stock;
use App\Helpers\QuantityHelper;
use App\Helpers\Converter;
use Auth;

class ProductTransferController extends Controller {
    use QuantityHelper;

    private $meta = [
        'title' => 'Product Transfer',
        'menu' => 'stock',
        'submenu' => ''
    ];

    /**
     *
     */
    public function __construct() {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $transferProduct = ProductTransfer::paginate(15);
        return view('user.stock.product-transfer.index', compact('transferProduct'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $business_id = Auth::user()->business_id;
        $warehouses = Warehouse::with('products')->where('business_id', $business_id)->get();
        return view('user.stock.product-transfer.create', compact('warehouses'))->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $businessId = Auth::user()->business_id;
        $productTransfer = new ProductTransfer;
        $productTransfer->from_warehouse_id = $request->fromWarehouseId;
        $productTransfer->to_warehouse_id = $request->toWarehouseId;
        $productTransfer->product_id = $request->productId;
        $productTransfer->user_id = Auth::id();
        $productTransfer->business_id = $businessId;
        $productTransfer->date = date('y/m/d');

         $product = Product::where('id', $request->productId)->first();

         $total = 0;
        // set quantity for products
        foreach ($request['quantities'] as $warehouse_id => $quantity) {
            $_quantity = Converter::convert($this->formattedSingleQuantity($quantity, $product->unit->unit_length), $product->unit_code)['result'];
            $total += $_quantity;
        }
        $productTransfer->quantity = $total;

        $from_warehouse_quantity = $product->warehouses->where('id', $productTransfer->from_warehouse_id)->first()->stock->quantity;
        // return response($from_warehouse_quantity);
        if ($productTransfer->quantity > $from_warehouse_quantity){
            // return response()->json(['warning'=>'Insuficient Quantity'], 200);
             return session()->flash('error', 'Insuficient Quantity!!!');
        }

        if ($productTransfer->from_warehouse_id == $productTransfer->to_warehouse_id){
            // return response()->json(['warning'=>'Insuficient Quantity'], 200);
            return session()->flash('error', 'Product can not transfer same warehouse!!!');
        }

        if ($productTransfer->save()){
            // get the stock table quantity
            $from_warehouse_quantity = $product->warehouses->where('id', $productTransfer->from_warehouse_id)->first()->stock->quantity;
            // return response($from_warehouse_quantity);

            // substract damage quantity from stock table quantity
            $current_quantity = $from_warehouse_quantity - $productTransfer->quantity;

            // if current quantity is greater than 0 then update stock table quantity
            if ($current_quantity > 0 ) {
                $product->warehouses()->updateExistingPivot($productTransfer->from_warehouse_id, [
                    'quantity' => $current_quantity
                ]);
                // if current quantity is smaller than 0 then detach/delete stock table quantity
            }else{
                $product->warehouses()->detach($productTransfer->from_warehouse_id);
            }

             $exists = Stock::where('warehouse_id', $productTransfer->to_warehouse_id)->where('product_id', $productTransfer->product_id)->first();
            if ($exists){
                // increment quantity
                $exists->increment('quantity', $productTransfer->quantity);
            }else{
                $quantity = [
                    'quantity' => $productTransfer->quantity,
                ];
                $product->warehouses()->attach($productTransfer->to_warehouse_id, $quantity);
            }
             session()->flash('success', 'Product transfer successfully!!');
             // return response()->json(['message'=>'Product transfer successfully'], 200);
        }

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function getProductFromWarehouse(Request $request){
        $products = Warehouse::with('products')->where('id', $request->id)->first()->products;
        return response()->json($products);
    }
}
