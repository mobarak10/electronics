<?php

namespace App\Http\Controllers\User;

use App\Models\Cash;
use App\Models\Party;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User\User;
use App\Models\OrderManagement;
use App\Models\OrderManagementdetails;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class OrderManagementController extends Controller
{
    private $meta = [
        'title'   => 'Order Manage',
        'menu'    => 'pos',
        'submenu' => '',
    ];

    public function __construct() {
        $this->middleware('auth');
    }

    private $regular_vat = [
        'target' => 'total',
        'name' => 'Vat (0%)', //todo valid vat name
        'value' => '0%', //todo valid vat value
        'type' => 'vat'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = OrderManagement::orderBy('id', 'desc')->where('business_id', Auth::user()->business_id)->paginate(30);
        return view('user.order-manage.index', compact('orders'))->with($this->meta);
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
        // return $request->all();
        $order = OrderManagement::with('orderManagementDetails')->find($request->order_id);
        $customer_ballance = $order->customers->balance;
        $order->delivery_man_id = $request->delivery_man_id;
        $order->discount = $request->discount;
        $order->status = true;
        $order->save();
        $total_price = ($request->subtotal -$request->discount);
        // TO DO insert sale
        $sale_data = [
            'invoice_no' => 'INV' . str_pad(Sale::max('id') + 1, 4, '0', STR_PAD_LEFT),
            'party_id'   => $order->party_id,
            'user_id'    => Auth::user()->id,
            'payment_type' => 'cash',
            'subtotal' => $request->subtotal,
            'vat'      => 0.00,
            'discount' => $request->discount,
            'discount_type' => 'flat',
            'tendered' => 0.00,
            'due' => $total_price,
            'change' => 0.00,
            'customer_balance' => $customer_ballance - $total_price,
            'adjust_to_customer_balance' => false,
            'delivered' => true,
            'salesman_id' => $order->sr_id,
            'business_id' => Auth::user()->business_id,
        ];

        $sale = Sale::create($sale_data);

        foreach ($order->orderManagementDetails as $order_details) {
            // return $order_details;
            // To DO Order details insert warehouse id
            $warehouse_id = $request->productWarehouses[$order_details->product_id];
            $order_details->warehouse_id = $warehouse_id;
            $order_details->save();

            // sale details
            $sale_details_data = [
                'product_id' => $order_details->product_id,
                'purchase_price' => $order_details->products->purchase_price,
                'sale_price' => $order_details->products->wholesale_price,
                'sale_type' => 'wholesale',
                'discount' => 0.00,
                'discount_type' => 'flat',
                'line_total' => $order_details->products->wholesale_price * $order_details->quantity,
            ];
            $sale_details = $sale->saleDetails()->create($sale_details_data);
            
            // sale details warehouse
            $current_warehouse = $order_details->products->warehouses->where('id', $warehouse_id)->first();
            $present_quantity = $current_warehouse->stock->quantity;
            $sale_quantity = $order_details->quantity;
            if ($present_quantity >= $sale_quantity) { // if quantity greater or equal
                    $sale_details_warehouse = [];

                    $sale_details_warehouse['product_id'] = $order_details->product_id;
                    $sale_details_warehouse['sale_id'] = $sale->id; //sell id
                    $sale_details_warehouse['quantity'] = $order_details->quantity;
                    $sale_details_warehouse['created_at'] = now();
                    $sale_details_warehouse['updated_at'] = now();
                    //save quantity in sale_details_warehouses table
                    $sale_details->quantities()->attach($order_details->warehouse_id, $sale_details_warehouse);

                    //quantity after sell
                    $current_quantity = $present_quantity - $sale_quantity;

                    if ($current_quantity > 0) { // if current stock is available
                        $current_warehouse->stock->quantity = $current_quantity;
                        $current_warehouse->push(); // save current stock
                    } else { // if current stock empty
                        $product->warehouses()->detach($warehouse_id); // remove warehouse relation
                    }
                } else { // insufficient quantity
                    //todo throw an error
                }
        }
        // sale payment
        // save payment info
        $sale->salePayment()->create([
            'sale_id' => $sale->id,
            'cash_id' => 1,
        ]);

        return response()->json($sale, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = OrderManagement::with('orderManagementDetails.warehouses', 'orderManagementDetails.products.warehouses', 'deliveryMan')->find($id);
        $users = User::all();
        $total_price = 0;
        foreach ($order->orderManagementDetails as $details) {
            $total_price += $details->quantity * $details->products->wholesale_price;
        }
        // return $total_price;
        return view('user.order-manage.show', compact('order', 'total_price', 'users'))->with($this->meta);
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
}
