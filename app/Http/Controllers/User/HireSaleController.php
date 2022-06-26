<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Cash;
use App\Models\Customer;
use App\Models\HireSale;
use App\Models\HireSaleInstallment;
use App\Models\HireSalePayment;
use App\Models\HireSaleProduct;
use App\Models\Party;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HireSaleController extends Controller
{

    private $meta = [
        'title'   => 'Hire Sale',
        'menu'    => 'hire-sale',
        'submenu' => '',
        'header'  => false
    ];
    private $hire_sale;

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->meta['submenu'] = 'list';

        $hire_sales = HireSale::with('customer.metas')->orderBy('id', 'Desc')->paginate(30);
        $date = null;
        $customers = Customer::all();

        if (request()->search) {
            $hire_sales = HireSale::query(); // create sale instance
            $where = [];
            $date = []; // set date

            // pluck all the employees using phone number
            $party = Customer::where('phone', request()->condition['phone'])->get()->pluck('id');

            foreach (request()->condition as $input => $value) {
                if ($value != null) {
                    if ($input == 'phone') {
                        $where[] = ['customer_id', '=', $party];
                    } else {
                        $where[] = [$input, '=', $value];
                    }
                }
            }

            // set query
            $hire_sales = $hire_sales->where($where);

            // get data
            $hire_sales = $hire_sales->paginate(30);
        }

        return view('user.hire-sale.index', compact('hire_sales', 'customers'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->meta['submenu'] = 'add';
        $this->meta['aside'] = false; //hide aside

        $warehouses = Warehouse::with('products')->active()->get();
        $cashes = Cash::all();
        $bank_accounts = BankAccount::with('bank')->get();
        $customers = Customer::where('type', 'hire_customer')->get();

        return view('user.hire-sale.create', compact('warehouses', 'cashes', 'bank_accounts', 'customers'))
            ->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        return $request->all();
        DB::transaction(function() use($request) {
            $hire_sale_data = $request->validate([
                'date' => 'required|date',
                'warehouse_id' => 'required|integer',
                'customer_id' => 'required|integer',
                'subtotal' => 'required|numeric',
                'due' => 'nullable|numeric',
                'added_value' => 'nullable|numeric',
                'down_payment' => 'nullable|numeric',
                'installment_number' => 'nullable|numeric',
            ]);

            $total_due = $request->due + $request->added_value;

            $customer = Customer::find($request->customer_id);
            if ($total_due >= 0){
                $customer->increment('balance', $total_due);
            }else{
                $customer->decrement('balance', $total_due);
            }

            $voucher_number = date('Ymd').str_pad(HireSale::max('id') + 1, 4, '0', STR_PAD_LEFT);
            $hire_sale_data['voucher_no'] = $voucher_number;

            // store data into hire sale table
            $this->hire_sale = HireSale::create($hire_sale_data);
            $hire_sale = $this->hire_sale;

            // store hire sale product
            $this->hireSaleProduct($request, $hire_sale);
            // store hire sale payment
            $this->hireSalePayment($request, $hire_sale);
            // store hire sale installment
            $this->hireSaleInstallment($request, $hire_sale);

        });

        return response($this->hire_sale, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($voucher_no)
    {
        $hire_sale = HireSale::with('hireSaleProducts', 'hireSaleInstallments', 'installmentCollection')->where('voucher_no', $voucher_no)->first();
        return view('user.hire-sale.show', compact('hire_sale'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->meta['submenu'] = 'hire-sale';
        $this->meta['aside'] = false; //hide aside

        $old_hiresale = HireSale::with(
            'hireSaleProducts.product',
            'hireSaleInstallments',
            'hireSalePayment',
            'installmentCollection')
            ->findOrFail($id);
        $warehouses = Warehouse::with('products')->active()->get();
        $cashes = Cash::all();
        $bank_accounts = BankAccount::with('bank')->get();
        $customers = Customer::where('type', 'hire_customer')->get();

        return view('user.hire-sale.edit', compact('warehouses', 'old_hiresale', 'cashes', 'bank_accounts', 'customers'))
            ->with($this->meta);
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
        DB::transaction(function() use($request, $id) {
            $old_hire_sale = HireSale::findOrFail($id);
            $this->updateOldHireSale($old_hire_sale);
            $old_hire_sale->hireSalePayment->delete();
            if (count($old_hire_sale->hireSaleInstallments) > 0) {
                $old_hire_sale->hireSaleInstallments()->delete();
            }
            if (count($old_hire_sale->installmentCollection) > 0) {
                $old_hire_sale->installmentCollection()->delete();
            }

            $hire_sale_data = $request->validate([
                'date' => 'required|date',
                'warehouse_id' => 'required|integer',
                'customer_id' => 'required|integer',
                'subtotal' => 'required|numeric',
                'due' => 'nullable|numeric',
                'added_value' => 'nullable|numeric',
                'down_payment' => 'nullable|numeric',
                'installment_number' => 'nullable|numeric',
            ]);

            $total_due = $request->due + $request->added_value;

            $customer = Customer::find($request->customer_id);
            if ($total_due >= 0){
                $customer->increment('balance', $total_due);
            }else{
                $customer->decrement('balance', $total_due);
            }

            // store data into hire sale table
            $old_hire_sale->update($hire_sale_data);
            $this->hire_sale = $old_hire_sale;

                // store hire sale product
            $this->hireSaleProduct($request, $old_hire_sale);
            // store hire sale payment
            $this->hireSalePayment($request, $old_hire_sale);
            // store hire sale installment
            $this->hireSaleInstallment($request, $old_hire_sale);
        });

        return response()->json($this->hire_sale, 200);
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

    /**
     * store hire sale product
     * @param $request
     * @param $hire_sale
     * @return void
     */
    public function hireSaleProduct($request, $hire_sale)
    {
        foreach ($request->products as $product){
            $data = [];
            $data['product_id'] = $product['id'];
            $data['hire_sale_id'] = $hire_sale->id;
            $data['product_serial'] = $product['serial_number'];
            $data['quantity'] = $product['quantity'];
            $data['sale_price'] = $product['sale_price'];
            $data['purchase_price'] = $product['purchase_price'];
            $data['line_total'] = $product['line_total'];

            $product = Product::find($product['id']);
            $current_warehouse = $product->warehouses->where('id', $request->warehouse_id)->first();
            $present_quantity = $current_warehouse->stock->quantity;
            $current_quantity = $present_quantity - $product['quantity'];

            $current_warehouse->stock->quantity = $current_quantity;
            $current_warehouse->push(); // save current stock

            // store data into hire sale product table
            HireSaleProduct::create($data);
        }
    }

    /**
     * store hire sale payment data
     * @param $request
     * @param $hire_sale
     * @return void
     */
    public function hireSalePayment($request, $hire_sale)
    {
        if ($request->where === 'cash') {
            $payment_data = [];

            $cash = Cash::find($request->cash_id); // find cash
            $cash->increment('amount', $request->down_payment); // increment cash balance

            $payment_data['payment_method'] = 'cash';
            $payment_data['hire_sale_id'] = $hire_sale->id;
            $payment_data['cash_id'] = $request->cash_id;

            HireSalePayment::create($payment_data);
        }
        elseif ($request->where === 'bank') {
            $payment_data = [];

            $bank_account = BankAccount::find($request->bank_account_id); // find bank account
            $bank_account->increment('balance', $request->down_payment); // increment bank account balance

            $payment_data['payment_method'] = 'bank';
            $payment_data['bank_account_id'] = $request->bank_account_id;
            $payment_data['hire_sale_id'] = $hire_sale->id;
            $payment_data['check_no'] = $request->check_number;
            $payment_data['branch'] = $request->branch;
            $payment_data['issue_date'] = $request->issue_date;

            HireSalePayment::create($payment_data);
        }
        elseif ($request->where === 'bkash') {
            $payment_data = [];

            $payment_data['payment_method'] = 'bkash';
            $payment_data['hire_sale_id'] = $hire_sale->id;
            $payment_data['bkash_number'] = $request->bkash_no;

            HireSalePayment::create($payment_data);
        }
    }

    /**
     * store hire sale installment data
     * @param $request
     * @param $hire_sale
     * @return void
     */
    public function hireSaleInstallment($request, $hire_sale)
    {
        $sale_date = $request->date;
        $now = strtotime($sale_date);

        for ($i = 1; $i <= $request->installment_number; $i++) {
            $date = date('Y-m-d', strtotime('+' . $i .' month', $now));

            $installment_data = [];

            $installment_data['installment_date'] = $date;
            $installment_data['hire_sale_id'] = $hire_sale->id;
            $installment_data['installment_amount'] = $request->installment_amount;

            HireSaleInstallment::create($installment_data);
        }
    }

    /**
     * update old hire sale data
     * @param $old_hire_sale
     * @return void
     */
    public function updateOldHireSale($old_hire_sale)
    {
        // TODO balance customer balance
        $old_customer = Customer::find($old_hire_sale->customer_id);
        if ($old_hire_sale->due) {
            // if customer has due then decrement the due in customer balance
            $old_customer->decrement('balance', $old_hire_sale->due);
        }

        if ($old_hire_sale->down_payment > 0) {
            if ($old_hire_sale->hireSalePayment->payment_method == 'cash'){
                Cash::findOrFail($old_hire_sale->hireSalePayment->cash_id)->decrement('amount', $old_hire_sale->down_payment);
            }else{
                BankAccount::findOrFail($old_hire_sale->hireSalePayment->bank_account_id)->decrement('balance', $old_hire_sale->down_payment);
            }
        }

        $removed_hire_sale_products = $old_hire_sale->hireSaleProducts->pluck('id');
        $this->removePreviousHireSaleItemUpdateQuantity($old_hire_sale, $removed_hire_sale_products);
    }

    /**
     * remove previous hire sale product and update quantity
     * @param $removed_hire_sale_products
     * @return void
     */
    public function removePreviousHireSaleItemUpdateQuantity($old_hire_sale, $removed_hire_sale_products)
    {
        if ($removed_hire_sale_products){
            foreach ($removed_hire_sale_products as $item){
                // get deleted product
                $details = $old_hire_sale->hireSaleProducts->where('id', $item)->first();
                // quantity that should be add in warehouse
                $quantity = $details->quantity;
                // get product
                $product = Product::findOrFail($details->product_id);
                // get warehouse
                $_warehouse = $product->warehouses->where('id', $old_hire_sale->warehouse_id)->first();
                if($_warehouse) {
                    //get exists quantity
                    $previous_quantity = $_warehouse->stock->quantity;
                    //update stocks
                    $product->warehouses()->updateExistingPivot($old_hire_sale->warehouse_id, [
                        'quantity' => $previous_quantity + $quantity,
                    ]);
                }else{ // no previous warehouse exists
                    //add new stock in for products
                    $product->warehouses()->attach([
                        $old_hire_sale->warehouse_id =>  [
                            'quantity' => $quantity,
                            'average_purchase_price' => $product->purchase_price,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    ]);
                }
                // deleted that product which in no available in new updated sale
                $old_hire_sale->hireSaleProducts->find($item)->delete();
            }
        }
    }
}
