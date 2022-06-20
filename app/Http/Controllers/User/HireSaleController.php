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

class HireSaleController extends Controller
{

    private $meta = [
        'title'   => 'Hire Sale',
        'menu'    => 'hire-sale',
        'submenu' => '',
        'header'  => false
    ];

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
                        $where[] = ['party_id', '=', $party];
                    } else {
                        $where[] = [$input, '=', $value];
                    }
                }
            }

            // set query
            $hire_sales = $hire_sales->where($where);

            // set date
            if (request()->date['from'] != null) {
                $date[] = date(request()->date['from']);
            }

            if (request()->date['to'] != null) {
                $date[] = date(request()->date['to']);
            } else {
                if (request()->date['from'] != null) {
                    $date[] = date('Y-m-d');
                }
            }

            if (count($date) > 0) {
                $hire_sales = $hire_sales->whereBetween('date', $date);
            }

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
        $hire_sale_data = $request->validate([
            'date' => 'required|date',
            'warehouse_id' => 'required|integer',
            'party_id' => 'required|integer',
            'subtotal' => 'required|numeric',
            'due' => 'nullable|numeric',
            'previous_balance' => 'nullable|numeric',
            'added_value' => 'nullable|numeric',
            'down_payment' => 'nullable|numeric',
            'installment_number' => 'nullable|numeric',
        ]);

        $last_hire_sale_for_customer = HireSale::where('party_id', $request->party_id)->get()->last();
        if ($last_hire_sale_for_customer && $last_hire_sale_for_customer->status === 0){
            $last_hire_sale_for_customer->status = 1;
            $last_hire_sale_for_customer->save();
        }

        $total_due = $request->due + $request->added_value;

        if ($total_due >= 0){
            $customer = Party::find($request->party_id);
            $customer->balance = -1 * abs($total_due);
            $customer->save();
        }else{
            $customer = Party::find($request->party_id);
            $customer->balance = $total_due;
            $customer->save();
        }


        $voucher_number = date('Ymd').str_pad(HireSale::max('id') + 1, 4, '0', STR_PAD_LEFT);
        $hire_sale_data['voucher_no'] = $voucher_number;

        // store data into hire sale table
        $hire_sale = HireSale::create($hire_sale_data);

        foreach ($request->cart_products as $products){
            $data = [];

            $data['product_id'] = $products['product']['id'];
            $data['hire_sale_id'] = $hire_sale->id;
            $data['product_serial'] = $products['product']['serial_no'];
            $data['quantity'] = $products['product']['saleQuantity'];
            $data['sale_price'] = $products['product']['retail_price'];
            $data['line_total'] = $products['product']['retail_price'] * $products['product']['saleQuantity'];

            $product = Product::find($products['product']['id']);
            $current_warehouse = $product->warehouses->where('id', $request->warehouse_id)->first();
            $present_quantity = $current_warehouse->stock->quantity;
            $current_quantity = $present_quantity - $products['product']['saleQuantity'];

            if ($current_quantity > 0) { // if current stock is available
                $current_warehouse->stock->quantity = $current_quantity;
                $current_warehouse->push(); // save current stock
            } else { // if current stock empty
                $product->warehouses()->detach($request->warehouse_id); // remove warehouse relation
            }

            // store data into hire sale product table
            HireSaleProduct::create($data);
        }

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

        return response($hire_sale, 200);
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
