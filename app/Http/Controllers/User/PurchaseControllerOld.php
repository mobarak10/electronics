<?php

namespace App\Http\Controllers\User;

use App\Helpers\Converter;
use App\Helpers\QuantityHelper;
use App\Models\BankAccount;
use App\Models\Cash;
use App\Models\Party;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Log;

class PurchaseControllerOld extends Controller
{
    use QuantityHelper;

    private $meta = [
        'title'   => 'Purchase',
        'menu'    => 'purchase',
        'submenu' => '',

    ];

    private $purchase_cart;

    public function __construct()
    {
        $this->middleware('auth');

        //add purchase cart
        $this->purchase_cart = app('purchase');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $this->meta['submenu'] = 'all';

        $business_id = Auth::user()->business_id;
        $purchases   = Purchase::where('business_id', $business_id)->latest()->paginate(50);
        $parties     = Party::where('business_id', $business_id)->get();

        if (request()->search) {
            $purchases = Purchase::query(); // create purchase instance
            $where = [['business_id', '=', $business_id]]; // set business-id conditions
            $date = []; // set date

            foreach(request()->condition as $input => $value) {
                if($value != null) {
                    $where[] = [$input, '=', $value];
                }
            }

            // set query
            $purchases = $purchases->where($where);

            // set date
            if(request()->date['from'] != null) {
                $date[] = date(request()->date['from'] . ' 00:00:00');
            }

            if(request()->date['to'] != null) {
                $date[] = date(request()->date['to'] . ' 23:59:00');
            } else {
                if(request()->date['from'] != null) {
                    $date[] = date('Y-m-d') . ' 23:59:00';
                }
            }

            if(count($date) > 0) {
                $purchases = $purchases->whereBetween('created_at', $date);
            }

            // get data
            $purchases = $purchases->paginate(50);
        }

        return view('user.purchase-old.index', compact('purchases', 'parties'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->meta['submenu'] = 'add';
        $this->meta['aside'] = false; // hide aside

        return view('user.purchase-old.create')
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
        // get single format quantity for adjustment price
        $total_quantity_for_adjustment = 0;
        foreach ($this->purchase_cart->getContent() as $item){
            $product = Product::find($item->id);
            if ($product->unit->unit_length > 1){
                $total_unit_quantity = array_product(explode('/', $product->unit->relation));
                $total_quantity_for_adjustment += $item->quantity / $total_unit_quantity;
            }elseif ($product->unit->unit_length === 1){
                $total_quantity_for_adjustment += $item->quantity;
            }
        }


        $adjustment = 0;
        if ($request->adjustment > 0){
            $adjustment = $request->adjustment;
        }

        // amount that add for adjustment
        $purchase_price_for_add = $adjustment / $total_quantity_for_adjustment;

        /**
         * Database Actions
         * ----------------
         * Decrement Cash/Bank Account Balance
         * Insert Purchase
         * Update Supplier Balance
         * Insert Purchase Details
         * Update or Insert New Stock
         * Insert Purchase Quantity
         */

        // validate data
        $request->validate([
            'supplier_id' => 'required|integer',
            'voucher_no'  => 'nullable|string',
            'date'        => 'required|date',
        ]);

        $raw_purchase = [];
        $raw_purchase['party_id'] = $request->supplier_id;
        if ($request->voucher_no) {
            $raw_purchase['voucher_no'] = $request->voucher_no;
        }else{
            $raw_purchase['voucher_no'] = 'VN' . str_pad(Purchase::max('id') + 1, 4, '0', STR_PAD_LEFT);
        }
        $raw_purchase['date'] = $request->date;
        $raw_purchase['subtotal'] = $this->purchase_cart->getSubTotal();

        //if has adjustment
        if($request->adjustment){
            $raw_purchase['adjustment'] = $request->adjustment;
        }

        $raw_purchase['discount_type'] = 'flat';
        $raw_purchase['note'] = $request->note;
        $raw_purchase['business_id'] = Auth::user()->business_id;

        $grand_total = ($this->purchase_cart->getSubTotal() + $request->adjustment);

        //paid amount
        $amount = $request->payment['amount'];

        //has amount
        if($amount){
            //check payment method
            if($request->payment['selectedMethod'] === 'cash'){ //if method cash
                $cash = Cash::find($request->payment['selectedCash']);
                $party = Party::find($request->supplier_id);
                $raw_purchase['cash_id'] = $cash->id;

                // if amount is greater than cash balance
                if($amount > $cash->amount){
                    return response()->json('Insufficient amount', 422);
                }
                //decrease cash
                $cash->decrement('amount', $amount);

                // party debit ledger for cash paid
                $party_debit_ledger = [
                    'date' => now()->format('Y-m-d'),
                    'description' => 'Product Purchase on cash '.$cash->title.'-'.$raw_purchase['voucher_no'],
                    'debit' => $amount,
                    'voucher' => $raw_purchase['voucher_no'],
                    'balance' => $party->balance + $amount,
                ];
                $party->ledgers()->create($party_debit_ledger);

                // party credit ledger for cash paid
                $party_credit_ledger = [
                    'date' => now()->format('Y-m-d'),
                    'description' => 'Pay Bill for Product Purchase on cash '.$cash->title.'-'.$raw_purchase['voucher_no'],
                    'credit' => $grand_total,
                    'voucher' => $raw_purchase['voucher_no'],
                    'balance' => $party_debit_ledger['balance'] - $grand_total,
                ];
                $party->ledgers()->create($party_credit_ledger);

            }
            // method: bank payment
            else {
                $bank_account = BankAccount::find($request->payment['selectedBankAccount']);
                $raw_purchase['bank_account_id'] = $bank_account->id;
                $party = Party::find($request->supplier_id);

                // if amount is greater than bank account balance
                if($amount > $bank_account->balance){
                    return response()->json('Insufficient amount', 422);
                }
                //decrease bank account
                $bank_account->decrement('balance', $amount);

                // party debit ledger for bank payment
                $party_debit_ledger = [
                    'date' => now()->format('Y-m-d'),
                    'description' => 'Product Purchase on bank -'.$bank_account->account_number.'-'.$raw_purchase['voucher_no'],
                    'debit' => $amount,
                    'voucher' => $raw_purchase['voucher_no'],
                    'balance' => $party->balance + $amount,
                ];
                $party->ledgers()->create($party_debit_ledger);

                // party credit ledger for bank payment
                $party_credit_ledger = [
                    'date' => now()->format('Y-m-d'),
                    'description' => 'Pay Bill for Product Purchase on cash -'.$bank_account->account_number.'-'.$raw_purchase['voucher_no'],
                    'credit' => $grand_total,
                    'voucher' => $raw_purchase['voucher_no'],
                    'balance' => $party_debit_ledger['balance'] - $grand_total,
                ];
                $party->ledgers()->create($party_credit_ledger);
            }
            //insert amount in purchase array
            $raw_purchase['paid'] = $amount;
        }else{
            $party = Party::findOrFail($request->supplier_id);
            // party credit ledger for due purchase
            $party_credit_ledger = [
                'date' => now()->format('Y-m-d'),
                'description' => 'Product Purchase on due - '.$raw_purchase['voucher_no'],
                'credit' => $grand_total,
                'voucher' => $raw_purchase['voucher_no'],
                'balance' => $party->balance - $grand_total,
            ];
            $party->ledgers()->create($party_credit_ledger);
        }

        //insert into purchase table
        $purchase = Purchase::create($raw_purchase);

        //update supplier balance
        Party::find($request->supplier_id)->decrement('balance', ($grand_total - $amount));

        //purchase details
        foreach ($this->purchase_cart->getContent() as $item){
            $product = Product::find($item->id);

            // data that insert into purchase details
            $raw_details = [
                'product_id' => $item->id,
                'purchase_price' => $item->price,
                'line_total' => $item->getPriceSum()
            ];
            // create purchase details
            $details = $purchase->details()->create($raw_details);

            // if unit length greater than 1,
            // then purchase price for add is multiply by total unit length
            $purchase_price = ($item->price > 0) ? $item->price : $product->purchase_price;
            if ($product->unit->unit_length > 1){
                $total_unit_quantity = array_product(explode('/', $product->unit->relation));
                $purchase_price_for_add_for_unit_length_multiple = $purchase_price_for_add / $total_unit_quantity;

                //update product purchase_price, retail_price, wholesale_price, purchase_price_with_cost
                $product->update([
                    'purchase_price' => $purchase_price,
                    'purchase_price_with_cost' => ($purchase_price + $purchase_price_for_add_for_unit_length_multiple),
                    'retail_price' => $item->attributes['meta']['request']['product_retail_price'],
                    'dealer_price' => $item->attributes['meta']['request']['product_dealer_price']
                ]);
            }else{

                //update product purchase_price, retail_price, wholesale_price, purchase_price_with_cost
                $product->update([
                    'purchase_price' => $purchase_price,
                    'purchase_price_with_cost' => ($purchase_price + $purchase_price_for_add),
                    'retail_price' => $item->attributes['meta']['request']['product_retail_price'],
                    'dealer_price' => $item->attributes['meta']['request']['product_dealer_price']
                ]);
            }

            //set quantity for products
            foreach ($item->attributes->meta['request']['quantities'] as $warehouse_id => $quantity){
                $_quantity = Converter::convert($this->formattedSingleQuantity($quantity, $product->unit->unit_length), $product->unit_code)['result'];
                $purchase_quantities = [
                    'product_id' => $item->id,
                    'quantity' => $_quantity,
                    'free_quantity' => 0
                ];

                //select warehouse
                $_warehouse = $product->warehouses->where('id', $warehouse_id)->first();
                $cost_price_product = Product::find($item->id);
                if($_warehouse){
                    //get exists quantity
                    $previous_quantity = $_warehouse->stock->quantity;

                    // get total quantity
                    $total_quantity = $_warehouse->stock->quantity + $_quantity;

                    // get percentage of previous quantity
                    $percentage_of_previous_quantity = ($previous_quantity * 100) / $total_quantity;

                    // get percentage of present quantity
                    $percentage_of_present_quantity = ($_quantity * 100) / $total_quantity;

                    // get previous stock percentage price
                    $previous_average_purchase_price = $percentage_of_previous_quantity * ($_warehouse->stock->average_purchase_price / 100);

                    // get present stock percentage price
                    $present_average_purchase_price = $percentage_of_present_quantity * ($cost_price_product->purchase_price_with_cost / 100);

                    // total quantity purchase price
                    $total_price = $previous_average_purchase_price + $present_average_purchase_price;

                    //update stocks
                    $product->warehouses()->updateExistingPivot($warehouse_id, [
                        'quantity' => $previous_quantity + $_quantity,
                        'average_purchase_price' => $total_price,
                    ]);

                }
                // no previous warehouse exists
                else{
                    //add new stock in for products
                    $product->warehouses()->attach([
                        $warehouse_id =>  [
                            'quantity' => $_quantity,
                            'average_purchase_price' => $item->price,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    ]);
                }

                //if exists warehouse
                //add purchase quantity for purchase details
                $details->quantities()->attach($warehouse_id, $purchase_quantities);
            }

        }

        //clear cart contents
        $this->setClearCartContents();
        //clear session data
        $this->clearSessionData(['date', 'note', 'supplier_id']);

        return response()->json($purchase, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get the current purchase with details
        $purchase = Purchase::with('details.quantities')->where('id', $id)->first();

        return view('user.purchase-old.show', compact('purchase'))
            ->with($this->meta);
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

    /**
     * Add to cart
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function addToCart(Request $request)
    {
        $product = $this->cartFormat($request);

        /**
         * Check validation
         * Possibilities: quantity 0
         */
        if(!$product){
            $errors['errors'] = [
                'quantities' => ['Quantity can\'t be empty'], // quantity must be greater than 1
            ];

            return response()->json($errors, 422);
        }

        //if already added product then remove
        if($this->purchase_cart->get($request->product_id)){
            $this->purchase_cart->remove($request->product_id);
        }

        $this->purchase_cart->add($product);

        //set session data
        $this->setSessionData($request->only(
            ['date', 'note', 'supplier_id']
        ));

        //get session data
        $response = $this->getAllSessionData();

        return response()->json($response, 200);
    }

    /**
     * Get cart contents
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCartContents()
    {
        //get session data
        $response = $this->getAllSessionData();

        return response()->json($response, 200);
    }

    /**
     * Clear cart content with session data
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCartContents()
    {
        $this->setClearCartContents();

        $this->clearSessionData(['date', 'note', 'supplier_id']);

        $response = $this->getAllSessionData();

        return response()->json($response, 200);
    }

    /**
     * Remove item form cart
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeCartItem(Request $request)
    {
        $this->purchase_cart->remove($request->product_id);

        $response = $this->getAllSessionData();

        return response()->json($response, 200);
    }



    public function returnPurchase(Purchase $purchase)
    {
        $this->meta['aside'] = false; // hide aside

        return view('user.purchase-old.return', compact('purchase'))->with($this->meta);
    }

    public function returnPurchaseProceed(Request $request)
    {
        return response()->json($request->all(), 200);
    }



    /*-----AJAX Request Method Start-----*/

    public function returnProceed(Request $request)
    {
        $request->validate([
            'purchase_id' => 'required|integer',
            'adjustment' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $purchase = Purchase::find($request->purchase_id);

        $errors = $this->validateQuantity($purchase, $request->quantities);

        if (count($errors)){
            return response()->json($errors, 422);
        }

        $_purchase_return = [
            'purchase_id' => $request->purchase_id,
            'user_id' => Auth::id(), //TODO auth id
            'adjustment' => $request->adjustment ?? 0,
            'note' => $request->note,
            'business_id' => Auth::user()->business_id,
        ];

        // insert into purchase return
        $purchase_return = PurchaseReturn::create($_purchase_return);

        $return_subtotal = 0;

        foreach ($request->quantities as $product_id => $quantities){
            $_return_product = [
                'product_id' => $product_id,
                'return_price' => $purchase->details->where('product_id', $product_id)->first()->purchase_price
            ];
            // insert into purchase return product
            $purchase_return_product = $purchase_return->purchaseReturnProducts()->create($_return_product);

            //current sale details
            $current_purchase_details = $purchase->details->where('product_id', $product_id)->first();

            $current_product = Product::find($product_id);

            //init product wise total quantity
            $product_total_quantity = 0;

            foreach ($quantities as $warehouse_id => $quantity){
                $formatted_quantity = $this->formattedSingleQuantity($quantity, count($current_purchase_details->product->product_unit_labels));
                $warehouse_wise_quantity_units = Converter::convert($formatted_quantity, $current_purchase_details->product->unit_code, 'u');
                $warehouse_wise_quantity = $warehouse_wise_quantity_units['result'];

                //add price to subtotal
                $product_total_quantity += $warehouse_wise_quantity;

                // insert into purchase_return_quantities table
                $purchase_return_product->quantities()->attach($warehouse_id, [
                    'purchase_id' => $purchase->id,
                    'product_id' => $product_id,
                    'quantity' => $warehouse_wise_quantity,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                //update product stock

                $product_quantity_in_current_warehouse_stock = $current_product->warehouses->where('id', $warehouse_id)->first()->stock->quantity;

                //$test[] = $product_quantity_in_current_warehouse_stock;

                if($warehouse_wise_quantity === $product_quantity_in_current_warehouse_stock){
                    //all quantity
                    $current_product->warehouses()->detach($warehouse_id);
                }else{
                    //
                    $current_product->warehouses->where('id', $warehouse_id)->first()->stock->decrement('quantity', $warehouse_wise_quantity);
                }
            }

            $return_subtotal +=  $product_total_quantity * $current_purchase_details->purchase_price;
        }

        //todo update supplier balance
        $total = $return_subtotal + $request->adjustment;
        // Update Party Balance
        $purchase->party->increment('balance', $total);

        $party_debit_ledger = [
            'date' => now()->format('Y-m-d'),
            'description' => 'Product return by increment party balance - '.$purchase->voucher_no,
            'debit' => $total ?? 0.00,
            'voucher' => $purchase->voucher_no,
            'balance' => $purchase->party->balance,
        ];
        $purchase->party->ledgers()->create($party_debit_ledger);

        return response()->json($purchase_return, 200);
    }

    public function calculatePurchaseReturnLineTotal(Request $request)
    {

        //return response($request->all(), 200);

        $purchase_details = Purchase::find($request->purchase_id)->details;


        $purchase = Purchase::find($request->purchase_id);
        $errors = $this->validateQuantity($purchase, $request->quantities);

        if (count($errors)) {
            return response()->json($errors, 422);
        }

        $response = [];
        foreach ($request->quantities as $product_id => $quantities){
            $purchase_product = $purchase_details->where('product_id', $product_id)->first();

            $purchase_price = $purchase_product->purchase_price;

            $total_quantity = 0;

            foreach ($quantities as $warehouse_id => $quantity){
                $formatted_quantity = $this->formattedSingleQuantity($quantity, count($purchase_product->product->product_unit_labels));
                $warehouse_wise_quantity_units = Converter::convert($formatted_quantity, $purchase_product->product->unit_code, 'u');
                $warehouse_wise_quantity = $warehouse_wise_quantity_units['result'];
                //add warehouse wise quantity into total quantity
                $total_quantity += $warehouse_wise_quantity;
            }

            // init line total
            $response[$product_id] = $total_quantity * $purchase_price;

        }

        return response()->json($response, 200);
    }

    /*-----AJAX Request Method End-----*/



    /*-----Helper Method Start----*/


    /**
     * Get Cart items with price
     * @return \Illuminate\Support\Collection
     */
    private function getCartContentsWithPrice()
    {
        $items = $this->purchase_cart->getContent();
//        $items = $this->purchase_cart->getContent()->sortBy(function ($item){
//            Log::debug($item);
//            return $item['quantity'];
//        });

        foreach ($items as $id => $item){
            $items[$id]['attributes']['price'] = [
                'priceSum' => $item->getPriceSum(),
                'priceWithConditions' => $item->getPriceWithConditions(),
                'priceSumWithConditions' => $item->getPriceSumWithConditions(),
            ];
        }

        return $items;
    }

    /**
     * Clear cart contents
     */
    private function setClearCartContents()
    {
        $this->purchase_cart->clear();
    }

    /**
     * Set session data
     * @param $data
     */
    private function setSessionData($data)
    {
        foreach ($data as $key => $datum) {
            session([$key => $datum]);
        }
    }

    /**
     * Clear session data
     * @param $data
     */
    private function clearSessionData($data)
    {
        session()->forget($data);
    }

    /**
     * get All session data
     * @return array
     */
    private function getAllSessionData()
    {
        return [
            'input' => [
                'date' => session()->get('date', null),
                'supplier_id' => session()->get('supplier_id', null),
                'note' => session()->get('note', null)
            ],
            'purchase_items' => $this->getCartContentsWithPrice(),
            'purchase_items_prices' => $this->purchaseItemsPrices()
        ];
    }

    private function purchaseItemsPrices()
    {
        return [
            'subtotal' => $this->purchase_cart->getSubTotal(), // get cart sub total
            'total' => $this->purchase_cart->getTotal(), // the new total in which conditions are already applied
        ];
    }

    /**
     * Format Request to Cart Format
     * @param Request $request
     * @return array|bool
     */
    private function cartFormat(Request $request)
    {
        $product = Product::with('warehouses')->where('id', $request->product_id)->first();

        $total_formatted_quantities = $this->totalFormattedQuantities($request->quantities, $product->unit_code);

        if($total_formatted_quantities <= 0){
            return false;
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $request->product_purchase_price,
            'quantity' => $total_formatted_quantities,
            'attributes' => [
                'meta' => [
                    'product' => $product,
                    'request' => $request->all(),
                    'total_quantity' =>  $this->getQuantityDisplayFormat($total_formatted_quantities, $product->unit_code),
                    'created_at' => now()->timestamp,
                ]
            ]
        ];
    }

    private function getQuantityDisplayFormat($quantity, $unit_code){

        return Converter::convert($quantity, $unit_code, 'd');
    }

    /**
     * Get total formatted quantities
     * @param $quantities
     * @param $unit_code
     * @return int
     */
    private function totalFormattedQuantities($quantities, $unit_code)
    {
        $_quantity = 0;
        $unit = Unit::where('code', $unit_code)->first();
        $unit_length = count(explode('/', $unit->relation));

        foreach ($quantities as $quantity){
            $_quantity += Converter::convert($this->formattedSingleQuantity($quantity, $unit_length), $unit->code, 'u')['result'];
        }

        return $_quantity;
    }


    /*-----Helper Method End----*/
    private function validateQuantity($_purchase, $_quantities)
    {
        $errors = [];

        foreach ($_quantities as $product_id => $quantities) {

            $current_purchase_product = $_purchase->details->where('product_id', $product_id)->first();


            $warehouses = $current_purchase_product->quantities;

            foreach ($quantities as $warehouse_id => $quantity) {


                $available_in_stock = $current_purchase_product->product->warehouses->where('id', $warehouse_id)->first()->stock->quantity;

                $formatted_quantity = $this->formattedSingleQuantity($quantity, count($current_purchase_product->product->product_unit_labels));
                $warehouse_wise_quantity_units = Converter::convert($formatted_quantity, $current_purchase_product->product->unit_code, 'u');

                $available_quantity = $warehouses->where('id', $warehouse_id)->first()->purchase_product_rest_quantity;

                if ($warehouse_wise_quantity_units['result'] > $available_quantity){
                    $errors[$_purchase->id][$product_id][$warehouse_id] = 'Insufficient return quantity';
                }

                if ($warehouse_wise_quantity_units['result'] > $available_in_stock){
                    $errors[$_purchase->id][$product_id][$warehouse_id] = 'Not enough in stock';
                }

            }
        }
        return $errors;
    }
}
