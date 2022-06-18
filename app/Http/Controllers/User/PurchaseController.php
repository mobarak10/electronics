<?php

namespace App\Http\Controllers\User;

use App\Helpers\SMS;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Cash;
use App\Models\Party;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SmsReport;
use App\Models\Warehouse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    private $meta = [
        'title' => 'Purchase',
        'menu' => 'purchase',
        'submenu' => '',
        'header' => false
    ];

    private $purchase;
    public $sender_id, $api_key;

    public function __construct()
    {
        $this->middleware('auth');
        $this->sender_id = env('SMS_SENDER_ID');
        $this->api_key = env('SMS_API_KEY');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $this->meta['submenu'] = 'list';

        $parties = Party::all();
        $purchases = Purchase::orderBy('id', 'desc')->paginate(30);

        return view('user.purchase.index', compact('parties', 'purchases'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->meta['submenu'] = 'add';
        $this->meta['aside'] = false; //hide aside

        $cashes = Cash::all();
        $warehouses = Warehouse::with('products.unit')->get();
        $products = Product::with('warehouses', 'unit')->get();
        $parties = Party::select('id', 'name', 'phone', 'address', 'balance')->get();
        $bank_accounts = BankAccount::with('bank')
            ->get();
        $lang = __('contents');

        return view('user.purchase.create', compact(
                'warehouses',
                'cashes',
                'parties',
                'products',
                'lang',
                'bank_accounts')
        )->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
//        return $request->all();
//        return $request->payment['due'];
        DB::transaction(function () use ($request) {
            $purchase_data = [
                'date' => $request->date,
                'payment_type' => $request->payment['method'],
                'party_id' => $request->party_id,
                'cash_id' => $request->payment['method'] == 'cash' ? $request->payment['purchase_payments']['cash_id'] : null,
                'bank_account_id' => $request->payment['method'] == 'bank' ? $request->payment['purchase_payments']['bank_account_id'] : null,
                'warehouse_id' => $request->warehouse_id,
                'subtotal' => $request->payment['subtotal'],
                'discount' => $request->payment['total_discount'],
                'discount_type' => 'flat',
                'labour_cost' => $request->labourCost,
                'transport_cost' => $request->transportCost,
                'paid' => $request->paid,
                'due' => $request->due ?? 0,
                'previous_balance' => $request->previous_balance,
                'note' => $request->note,
                'user_id' => Auth::id(),
                'business_id' => auth()->user()->business_id
            ];

            if ($request->voucher_no) {
                $purchase_data['voucher_no'] = $request->voucher_no;
            }else{
                $purchase_data['voucher_no'] = 'VOUCHER-' . str_pad(Purchase::max('id') + 1, 8, '0', STR_PAD_LEFT);
            }

            // insert purchase
            $this->purchase = Purchase::create($purchase_data);
            $purchase = $this->purchase;

            foreach ($request->products as $product) {
                $_product = Product::find($product['id']);

                $purchase_details_data = [
                    'product_id' => $product['id'],
                    'purchase_price' => $product['purchase_price'],
                    'quantity' => $product['quantity'],
                    'quantity_in_unit' => $product['quantity_in_unit'],
                    'discount' => 0.00,
                    'discount_type' => 'flat',
                    'line_total' => $product['line_total'],
                ];

                // create purchase details
                $purchase->details()->create($purchase_details_data);

                // decrement product quantity
                $warehouse = $_product->warehouses()
                    ->find($request->warehouse_id);

                if ($warehouse) {
                    //get exists quantity
                    $previous_quantity = $warehouse->stock->quantity;

                    // get total quantity
                    $total_quantity = $warehouse->stock->quantity + $product['quantity'];

                    // get percentage of previous quantity
                    $percentage_of_previous_quantity = ($previous_quantity * 100) / $total_quantity;

                    // get percentage of present quantity
                    $percentage_of_present_quantity = ($product['quantity'] * 100) / $total_quantity;

                    // get previous stock percentage price
                    $previous_average_purchase_price = $percentage_of_previous_quantity * ($warehouse->stock->average_purchase_price / 100);

                    // get present stock percentage price
                    $present_average_purchase_price = $percentage_of_present_quantity * ($_product->purchase_price / 100);

                    // total quantity purchase price
                    $total_price = $previous_average_purchase_price + $present_average_purchase_price;

                    //update stocks
                    $_product->warehouses()->updateExistingPivot($warehouse->id, [
                        'quantity' => $previous_quantity + $product['quantity'],
                        'average_purchase_price' => $total_price,
                    ]);

                } // no previous warehouse exists
                else {
                    //add new stock in for products
                    $_product->warehouses()->attach([
                        $request->warehouse_id => [
                            'quantity' => $product['quantity'],
                            'average_purchase_price' => $product['purchase_price'],
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    ]);
                }
            }

            $party = Party::findOrFail($request->party_id);

            $party->balance = -1 * $request->due;
            $party->save();

            if ($request->sms) {
                $message = "You have sale " .
                    number_format($purchase->grand_total, 2) .
                    " paid " .
                    number_format($purchase->paid, 2) .
                    " due " .
                    number_format($purchase->due, 2).
                    " Now your Balance is ".
                    number_format($party->balance, 2);
                $message = $message . " " . config('sms.regards');
                $report = SMS::customSmsSend($this->sender_id, $this->api_key, $party->phone, $message); //send sms
                $data = explode('|', $report); //for decode data

                if (($data[0] ?? '00') == '1101') {
                    $sms_report = new SmsReport();
                    $sms_report->sent_to = $party->phone;
                    $sms_report->message = $message;
                    $sms_report->total_character = strlen($message);
                    $sms_report->total_sms = 1;
                    $sms_report->status = "success";
                    $sms_report->save();
                }
            }

            // increment cash or bank account balance
            switch ($request->payment['method']) {
                case 'cash':
                    $cash_id = $request->payment['purchase_payments']['cash_id'];
                    Cash::find($cash_id)->decrement('amount', $request->paid);
                    break;

                case 'bank':
                    $bank_account_id = $request->payment['purchase_payments']['bank_account_id'];
                    BankAccount::find($bank_account_id)->decrement('balance', $request->paid);
                    break;
            }

        });

        return response()->json($this->purchase, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|Response|View
     */
    public function show($id)
    {
        $this->meta['submenu'] = 'list';
        $purchase = Purchase::findOrFail($id);
        return view('user.purchase.show', compact('purchase'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|Response|View
     */
    public function edit($id)
    {
        // TODO Check products are exists
        // TODO Check warehouse exists
        $old_purchase = Purchase::query()
            ->with('details')
            ->with('products.warehouses')
            ->findOrFail($id);

        $this->meta['submenu'] = 'add';
        $this->meta['aside'] = false; //hide aside

        $cashes = Cash::all();
        $warehouses = Warehouse::with('products.unit')->get();
        $products = Product::with('warehouses', 'unit')->get();
        $parties = Party::select('id', 'name', 'phone', 'address', 'balance')->get();
        $bank_accounts = BankAccount::with('bank')
            ->get();
        $lang = __('contents');

        return view('user.purchase.edit', compact(
                'warehouses',
                'cashes',
                'parties',
                'products',
                'lang',
                'bank_accounts',
                'old_purchase')
        )->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
//        return $request->all();
        $old_purchase = Purchase::query()
            ->with('details.product', 'warehouse.products')
            ->findOrFail($id);

        // calculate previous supplier paid
        $previous_supplier_paid = $old_purchase->paid;

        DB::transaction(function () use ($old_purchase, $previous_supplier_paid, $request) {
            $previous_paid = $old_purchase->paid - $old_purchase->grand_total;
            $purchase_due = $old_purchase->grand_total - $old_purchase->paid;
            // Balance supplier balance
            if ($purchase_due > 0) {
                $old_purchase->party()
                    ->increment('balance', $old_purchase->due);
            }else{
                $old_purchase->party()
                    ->decrement('balance', $previous_paid);
            }

            // Balance cash amount
            if ($old_purchase->cash_id) {
                $old_purchase->cash()
                    ->increment('amount', $previous_supplier_paid);

                $old_purchase->cash_id = null;
                $old_purchase->save();
            }

            // Balance bank account
            if ($old_purchase->bank_account_id) {
                $old_purchase->bankAccount()
                    ->increment('balance', $previous_supplier_paid);

                $old_purchase->bank_account_id = null;
                $old_purchase->save();
            }

            // Balance warehouse stock quantity
            foreach ($old_purchase->details as $old_detail) {
                $old_product = $old_purchase->warehouse
                    ->products
                    ->where('id', $old_detail->product_id)
                    ->first();

                // check old product exists
                if ($old_product) {
                    // decease quantity from stock
                    $old_product->stock
                        ->decrement('quantity', $old_detail->quantity);
                } else {
                    // add negative quantity on stock
                    $old_detail->warehouse
                        ->products()
                        ->attach($old_detail->product_id, [
                            'quantity' => ($old_detail->quantity * -1),
                            'average_purchase_price' => $old_detail->product->purchase_price
                        ]);
                }
            }

            // delete purchase details
            $old_purchase->details()
                ->delete();
            // update purchase

            $new_purchase_data = [
                'date' => $request->date,
                'payment_type' => $request->payment['method'],
                'party_id' => $request->party_id,
                'cash_id' => $request->payment['method'] == 'cash' ? $request->payment['purchase_payments']['cash_id'] : null,
                'bank_account_id' => $request->payment['method'] == 'bank' ? $request->payment['purchase_payments']['bank_account_id'] : null,
                'warehouse_id' => $request->warehouse_id,
                'voucher_no' => 'VOUCHER-' . str_pad(Purchase::max('id') + 1, 8, '0', STR_PAD_LEFT),
                'subtotal' => $request->payment['subtotal'],
                'discount' => $request->payment['total_discount'],
                'discount_type' => 'flat',
                'labour_cost' => $request->labourCost,
                'transport_cost' => $request->transportCost,
                'paid' => $request->payment['paid'],
                'due' => $request->payment['due'] ?? 0,
                'previous_balance' => $request->previous_balance ?? 0,
                'note' => $request->note,
                'user_id' => Auth::id(),
                'business_id' => auth()->user()->business_id
            ];

            // insert purchase
            $this->purchase = tap($old_purchase)->update($new_purchase_data);

            // same as new purchase
            $purchase = $this->purchase;

            foreach ($request->products as $product) {
                $_product = Product::find($product['id']);

                $purchase_details_data = [
                    'product_id' => $product['id'],
                    'purchase_price' => $product['purchase_price'],
                    'quantity' => $product['quantity'],
                    'quantity_in_unit' => $product['quantity_in_unit'],
                    'discount' => 0.00,
                    'discount_type' => 'flat',
                    'line_total' => $product['line_total'],
                ];

                // create purchase details
                $purchase->details()->create($purchase_details_data);

                // decrement product quantity
                $warehouse = $_product->warehouses()
                    ->find($request->warehouse_id);

                if ($warehouse) {
                    //get exists quantity
                    $previous_quantity = $warehouse->stock->quantity;

                    // get total quantity
                    $total_quantity = $warehouse->stock->quantity + $product['quantity'];

                    // get percentage of previous quantity
                    $percentage_of_previous_quantity = ($previous_quantity * 100) / $total_quantity;

                    // get percentage of present quantity
                    $percentage_of_present_quantity = ($product['quantity'] * 100) / $total_quantity;

                    // get previous stock percentage price
                    $previous_average_purchase_price = $percentage_of_previous_quantity * ($warehouse->stock->average_purchase_price / 100);

                    // get present stock percentage price
                    $present_average_purchase_price = $percentage_of_present_quantity * ($_product->purchase_price / 100);

                    // total quantity purchase price
                    $total_price = $previous_average_purchase_price + $present_average_purchase_price;

                    //update stocks
                    $_product->warehouses()->updateExistingPivot($warehouse->id, [
                        'quantity' => $previous_quantity + $product['quantity'],
                        'average_purchase_price' => $total_price,
                    ]);

                } // no previous warehouse exists
                else {
                    //add new stock in for products
                    $_product->warehouses()->attach([
                        $request->warehouse_id => [
                            'quantity' => $product['quantity'],
                            'average_purchase_price' => $product['purchase_price'],
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    ]);
                }

            }

            $party = Party::findOrFail($request->party_id);
            $party->balance = -1 * $request->due;
            $party->save();

            // increment cash or bank account balance
            switch ($request->payment['method']) {
                case 'cash':
                    $cash_id = $request->payment['purchase_payments']['cash_id'];
                    Cash::find($cash_id)->decrement('amount', $request->paid);
                    break;

                case 'bank':
                    $bank_account_id = $request->payment['purchase_payments']['bank_account_id'];
                    BankAccount::find($bank_account_id)->decrement('balance', $request->paid);
                    break;
            }
        });


        return response()->json($this->purchase);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $purchase = Purchase::query()
            ->with('details.product', 'warehouse.products')
            ->findOrFail($id);

        // calculate previous supplier paid
        $previous_supplier_paid = $purchase->paid;

        if ($purchase->paid > $purchase->grand_total) {
            $previous_supplier_paid = $purchase->grand_total;
        }

        DB::transaction(function () use ($purchase, $previous_supplier_paid) {
            // Balance supplier balance
            $purchase->party()
                ->increment('balance', $purchase->due);

            // Balance cash amount
            if ($purchase->cash_id) {
                $purchase->cash()
                    ->increment('amount', $previous_supplier_paid);

                $purchase->cash_id = null;
                $purchase->save();
            }

            // Balance bank account
            if ($purchase->bank_account_id) {
                $purchase->bankAccount()
                    ->increment('balance', $previous_supplier_paid);

                $purchase->bank_account_id = null;
                $purchase->save();
            }

            // Balance warehouse stock quantity
            foreach ($purchase->details as $old_detail) {
                $old_product = $purchase->warehouse
                    ->products
                    ->where('id', $old_detail->product_id)
                    ->first();

                // check old product exists
                if ($old_product) {
                    // decease quantity from stock
                    $old_product->stock
                        ->decrement('quantity', $old_detail->quantity);
                } else {
                    // add negative quantity on stock
                    $old_detail->warehouse
                        ->products()
                        ->attach($old_detail->product_id, [
                            'quantity' => ($old_detail->quantity * -1),
                            'average_purchase_price' => $old_detail->product->purchase_price
                        ]);
                }
            }

            // delete purchase details
            $purchase->details()
                ->delete();
            // update purchase

            $purchase->delete();
        });

        return redirect()->back()
            ->withSuccess('Purchase deleted successfully');
    }
}
