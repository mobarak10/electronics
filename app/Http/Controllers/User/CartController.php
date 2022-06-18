<?php

namespace App\Http\Controllers\User;

use App\Helpers\Converter;
use App\Helpers\QuantityHelper;
use App\Models\Cash;
use App\Models\Party;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class CartController extends Controller
{
    use QuantityHelper;

    private $regular_vat = [
        'target' => 'total',
        'name' => 'Vat (0%)', //todo valid vat name
        'value' => '0%', //todo valid vat value
        'type' => 'vat'
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Add to Cart - POS
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request)
    {
        // return request()->all();

        $business_id = Auth::user()->business_id;
        $request->validate([
            'product_id'    => 'required|integer',
            'quantities'    => 'required|array',
            'price_type'    => 'required|string',
            'note'          => 'nullable|string',
            'discount'      => 'nullable|numeric',
            'discount_type' => 'required|string'
        ]);

        //get the product with warehouse and unit relation
        $product = Product::where('business_id', $business_id)->with(['warehouses', 'unit'])->active()->where('id', $request->product_id)->first();

        //If item exists then remove item
        if ($this->getCartItem($product->id)) {
            $this->removeCartItem($product->id);
        }

        //get unit length
        $unit_length = $this->getUnitLength($product->unit_code);

        //total quantity warehouse wise
        $quantities = $this->productQuantities($request->quantities, $unit_length, $product->unit_code);

        //check for quantity exists
        $errors = $this->quantityVerify($quantities, $product);

        //if has error then return error
        if (count($errors['errors'])) {
            return response()->json($errors, 422);
        }

        //sum of all warehouses quantity
        $quantity_total = array_sum($quantities);

        // get the price of product by price type [retail or wholesale]
        $price = $this->getSingleProductPrice($request->price_type, $product, $request->sale);


        $selected_quantity = [];

        foreach ($quantities as $warehouse_id => $quantity) {
            $selected_quantity[$warehouse_id] = Converter::convert($quantity, $product->unit_code, 'd')['result'];
        }

        //conditions
        if ($request->has('discount') and !empty($request->discount)) {
            $discount_type = '';
            if ($request->discount_type == 'flat') {
            } elseif ($request->discount_type == 'percentage') {
                $discount_type = '%';
            }

            $condition_discount = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'Discount ' . $request->discount . $discount_type ?? '',
                'type' => 'sale',
                'value' => '-' . $request->discount . $discount_type ?? '',
            ));
        }

        $attributes = [
            'selected_quantity' => $selected_quantity,
            //'current_item_price_total' => $price * $quantity_total,
            //'original_price' => $this->getSingleProductPrice($request->price_type, $product),
            'price_type' => $request->price_type,
            'sale' => [
                'price' => $request->sale['price'],
            ],
            'note' => $request->note,
            'discount' => [
                'amount' => ($request->discount) ? $request->discount : null,
                'type' => $request->discount_type
            ],
            'meta' => [
                'quantities' => $quantities,
                'units' => Converter::convert($quantity_total, $product->unit_code, 'd'),
                'product' => $product
            ]
        ];

        $item = [
            'id'         => $product->id,
            'name'       => $product->name,
            'price'      => $price,
            'quantity'   => $quantity_total,
            'attributes' => $attributes,
            'conditions' => $condition_discount ?? []
        ];

        //set item in cart
        $this->setInCart($item);

        return response()->json('Product added successfully', 200);
    }

    /**
     * Proceed Payment
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function proceedPayment(Request $request)
    {
        // validate
        $request->validate([
            'payment_type' => 'required|in:bank,cash',
            'payment_info' => 'required|array',
            'adjust_to_customer_balance' => 'required|boolean',
            'salesman_id' => 'required|integer',
            'delivered' => 'required|boolean'
        ]);


        $cart_items = $this->getCartContent();
        $regular_vat = $this->getCartCondition($this->regular_vat['name']); // regular vat condition
        $discount = $this->getCartCondition('Sell Discount'); // discount condition
        $grand_total = $this->getCartTotal(); // grand total

        $sale = [
            'invoice_no'                 => 'INV' . str_pad(Sale::max('id') + 1, 8, '0', STR_PAD_LEFT),
            'party_id'                   => $request->customer_id,
            'cash_id'                    => $request->cash_id,
            'payment_type'               => $request->payment_type,
            'subtotal'                   => $this->getCartSubTotal(),
            'vat'                        => (!empty($regular_vat)) ? abs((int) $regular_vat->getValue()) : 0,
            'discount'                   => (!empty($discount)) ? abs((int) $discount->getValue()) : 0,
            'discount_type'              => (!empty($discount)) ? ((strpos($discount->getValue(), '%') === false) ? 'flat' : 'percentage') : 'flat',
            'tendered'                   => $request->tendered,
            'user_id'                    => Auth::id(),
            'business_id'                => Auth::user()->business_id,
            'adjust_to_customer_balance' => $request->adjust_to_customer_balance,
            'salesman_id'                => $request->salesman_id,
            'delivered'                  => $request->delivered
        ];

        //cash increment
        $increment = 0;
        //check value for due/change

        $result = $sale['tendered'] - $grand_total;

        // customer
        $customer = Party::find($request->customer_id);

        $total_balance = $customer->balance - $grand_total;
        if ($total_balance <= 0){
            $total_debit_balance = abs($total_balance);
        }else{
            $total_debit_balance = -1 * $total_balance;
        }

        // if has change
        if ($result > 0) {
            $sale['due'] = 0;

            if ($request->adjust_to_customer_balance) {
                // insert customer ledger
                // return response($total_debit_balance, 200);
                $customer_ledger_debit = [
                    'date' => now()->format('Y-m-d'),
                    'description' => 'Product Sell Invoice Number ' . $sale['invoice_no'],
                    'debit' => $grand_total,
                    'balance' => $total_debit_balance,
                ];
                $customer->ledgers()->create($customer_ledger_debit);

                $customer_ledger_credit = [
                    'date' => now()->format('Y-m-d'),
                    'description' => 'Receive Cash Invoice Number ' . $sale['invoice_no'],
                    'credit' => $sale['tendered'],
                    'balance' => $customer_ledger_debit['balance'] - $sale['tendered'],
                ];
                $customer->ledgers()->create($customer_ledger_credit);

                // adjust change into customer balance
                $sale['change'] = 0;
                $customer->increment('balance', $result);
            } else {
                $sale['change'] = $result;
                // return response($total_debit_balance, 200);
                $customer_ledger_debit = [
                    'date' => now()->format('Y-m-d'),
                    'description' => 'Product Sell Invoice Number ' . $sale['invoice_no'],
                    'debit' => $grand_total,
                    'balance' => $total_debit_balance,
                ];
                $customer->ledgers()->create($customer_ledger_debit);

                $customer_ledger_credit = [
                    'date' => now()->format('Y-m-d'),
                    'description' => 'Receive Cash Invoice Number ' . $sale['invoice_no'],
                    'credit' => $grand_total,
                    'balance' => $customer_ledger_debit['balance'] - $grand_total,
                ];
                $customer->ledgers()->create($customer_ledger_credit);
            }

            $increment = $grand_total; //set increment

        } else if ($result < 0) {
            $sale['due'] = abs($result);
            $sale['change'] = 0;

            $increment = $sale['tendered']; //set increment

            // insert customer ledger
            // return response($total_debit_balance, 200);
            $customer_ledger_debit = [
                'date' => now()->format('Y-m-d'),
                'description' => 'Product Sell Invoice Number ' . $sale['invoice_no'],
                'debit' => $grand_total,
                'balance' => $total_debit_balance,
            ];
            $customer->ledgers()->create($customer_ledger_debit);

            if ($sale['tendered'] > 0){
                $customer_ledger_credit = [
                    'date' => now()->format('Y-m-d'),
                    'description' => 'Receive Cash Invoice Number ' . $sale['invoice_no'],
                    'credit' => $sale['tendered'],
                    'balance' => $total_debit_balance - $sale['tendered'],
                ];
                $customer->ledgers()->create($customer_ledger_credit);
            }

            //due for customer
            $customer->decrement('balance', $sale['due']);
        } else {
            $sale['due'] = 0;
            $sale['change'] = 0;

            $increment = $grand_total; //set increment
            // insert customer ledger
            $customer_ledger_debit = [
                'date' => now()->format('Y-m-d'),
                'description' => 'Product Sell Invoice Number '. $sale['invoice_no'],
                'debit' => $grand_total,
                'balance' => $total_debit_balance,
            ];

            $customer_ledger_credit = [
                'date' => now()->format('Y-m-d'),
                'description' => 'Receive Cash Invoice Number '. $sale['invoice_no'],
                'credit' => $sale['tendered'],
                'balance' => $total_debit_balance - $sale['tendered'],
            ];
            $customer->ledgers()->create($customer_ledger_debit);
            $customer->ledgers()->create($customer_ledger_credit);
        }

        // store customer balance into sale
        $sale['customer_balance'] = $customer->balance;

        //Save into Sales table
        $sale = Sale::create($sale);

        // save payment info
        $sale->salePayment()
            ->create($request->payment_info);


        // payment update
        switch ($sale->payment_type){
            case 'cash':
                //cash update
                $cash = Cash::find($sale->salePayment->cash_id);

                $cash->increment('amount', $increment);

                $cash_ledger = [
                    'date' => now()->format('Y-m-d'),
                    'description' => 'Product sell',
                    'debit' => $increment,
                    'balance' => $cash->amount,
                ];

                //cash ledger
                $cash->ledgers()->create($cash_ledger);
                break;

            case 'bank':
                // bank account balance increment
                $sale->salePayment->bankAccount->increment('balance', $increment);
                $sale->push();

                break;
        }

        foreach ($cart_items as $product_id => $item) {
            $sale_details = [
                'product_id'     => $item->id,
                'purchase_price' => $item->attributes->meta['product']->purchase_price,
                'sale_price'     => $item->price,
                'sale_type'      => $item->attributes->price_type,
                'discount'       => $item->attributes->discount['amount'] ?? 0,
                'discount_type'  => $item->attributes->discount['type'] ?? 'flat',
                'line_total'     => $item->getPriceSum(), //line total
            ];
            //Save in sale details
            $sale_details = $sale->saleDetails()->create($sale_details);

            $quantities = $item->attributes->meta['quantities'];

            $product = Product::find($item->id); // get specific product

            foreach ($quantities as $warehouse_id => $quantity) { // for every quantity

                $current_warehouse = $product->warehouses->where('id', $warehouse_id)->first();
                $present_quantity = $current_warehouse->stock->quantity;
                if ($present_quantity >= $quantity) { // if quantity greater or equal
                    $sale_details_warehouse = [];

                    $sale_details_warehouse['product_id'] = $product->id;
                    $sale_details_warehouse['sale_id'] = $sale->id; //sell id
                    $sale_details_warehouse['quantity'] = $quantity;
                    $sale_details_warehouse['created_at'] = now();
                    $sale_details_warehouse['updated_at'] = now();
                    //save quantity in sale_details_warehouses table
                    $sale_details->quantities()->attach($warehouse_id, $sale_details_warehouse);

                    //quantity after sell
                    $current_quantity = $present_quantity - $quantity;

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
        }

        //clear cart
        $this->clearCart();
        //clear condition
        $this->clearCartConditions();

        return response()->json($sale, 200);
    }

    /**
     * Get Cart items
     * @return \Illuminate\Http\JsonResponse
     */
    public function cartItems()
    {
        $cart_items = $this->getCartContent();
        foreach ($cart_items as $id => $item) {

            $cart_items[$id]['attributes']['price'] = [
                'priceSum' => $item->getPriceSum(),
                'priceWithConditions' => $item->getPriceWithConditions(),
                'priceSumWithConditions' => $item->getPriceSumWithConditions(),
            ];
        }
        return response()->json($cart_items, 200);
    }

    /**
     * Remove specific item
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeItem(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $this->removeCartItem($request->id);

        return response()->json($this->getCartContent(), 200);
    }

    /**
     * Get sub total
     * @return \Illuminate\Http\JsonResponse
     */
    public function subTotal()
    {
        return response()->json($this->getCartSubTotal(), 200);
    }

    /**
     * Get the calculated value of regular vat in subtotal
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateValueOfSubtotalForRegularVat()
    {
        $condition = \Cart::getCondition($this->regular_vat['name']);

        $response = [
            'name' => $condition->getName(),
            'amount' => $condition->getCalculatedValue($this->getCartSubTotal()),
        ];

        return response()->json($response, 200);
    }


    /**
     * Get Cart total
     * @return \Illuminate\Http\JsonResponse
     * @throws \Darryldecode\Cart\Exceptions\InvalidConditionException
     */
    public function total()
    {
        $this->applyConditionInCart($this->regular_vat['target'], $this->regular_vat['name'], $this->regular_vat['value'], $this->regular_vat['type']);
        return response()->json($this->getCartTotal(), 200);
    }

    /**
     * Apply discount
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'discount' => 'required|array',
            'discount.amount' => 'required',
            'discount.type' => 'required|string'
        ]);

        $value = (abs($request->discount['amount']) > 0) ? '-' . abs($request->discount['amount']) : '0';

        if ($request->discount['type'] == 'percentage') {
            $value .= '%';
        }

        $this->applyConditionInCart('total', 'Sell Discount', $value, 'discount');
        return response()->json($this->getCartTotal(), 200);
    }

    public function appliedDiscount()
    {
        $condition = \Cart::getCondition('Sell Discount');
        $response = array(
            'type' => (empty($condition)) ? 'flat' : ((strpos($condition->getValue(), '%') === false) ? 'flat' : 'percentage'),
            'amount' => (empty($condition)) ? null : abs($condition->getValue())
        );
        return response()->json($response, 200);
    }
    /**
     * Clear cart items
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCartItems(Request $request)
    {
        $this->clearCart();
        $this->clearCartConditions();
        return response()->json('Cart cleared', 200);
    }

    /*---------------Helper Methods-----------------*/

    /**
     * Get unit Length
     * @param $unit_code
     * @return int
     */
    private function getUnitLength($unit_code)
    {
        return count(explode('/', Unit::where('code', $unit_code)->first()->relation));
    }


    /**
     * Get Quantity to unit
     * @param $quantity
     * @param $unit_length
     * @param $unit_code
     * @return mixed
     */
    private function getQuantityToUnit($quantity, $unit_length, $unit_code)
    {
        return Converter::convert($this->formattedSingleQuantity($quantity, $unit_length), $unit_code);
    }

    /**
     * Calculate product quantities
     * @param $quantities
     * @param $unit_length
     * @param $unit_code
     * @return array
     */
    private function productQuantities($quantities, $unit_length, $unit_code)
    {
        $product_quantities = [];

        foreach ($quantities as $warehouse_id => $quantity) {
            $quantity = $this->getQuantityToUnit($quantity, $unit_length, $unit_code)['result'];

            if (!$quantity) continue;

            $product_quantities[$warehouse_id] = $quantity;
        }

        return $product_quantities;
    }

    /**
     * Set in \Cart
     * @param $item
     * @return mixed
     */
    private function setInCart($item)
    {
        return \Cart::add($item);
    }

    /**
     * Get Cart contents
     * @return mixed
     */
    public function getCartContent()
    {
        return \Cart::getContent();
    }


    /**
     * Get Cart Single Item
     * @return mixed
     */
    public function getCartItem($item_id)
    {
        return \Cart::get($item_id);
    }

    /**
     * Get Cart Sub Total
     * @return mixed
     */
    public function getCartSubTotal()
    {
        return \Cart::getSubTotal();
    }

    /**
     * Get Cart Total
     * @return mixed
     */
    public function getCartTotal()
    {
        return \Cart::getTotal();
    }

    /**
     * Remove specific item from cart
     * @param $id
     */
    public function removeCartItem($id)
    {
        \Cart::remove($id);
    }

    /**
     * Clear cart
     */
    private function clearCart()
    {
        \Cart::clear();
    }

    /**
     * Clear Cart Conditions
     */
    private function clearCartConditions()
    {
        \Cart::clearCartConditions();
    }

    /**
     * Get sigle product price
     * @param $price_type
     * @param $product
     * @return |null
     */
    private function getSingleProductPrice($price_type, $product, $sale)
    {
        $price = null;

        if ($price_type === 'wholesale') { // wholesale price
            $price = $product->wholesale_price;
        } else if ($price_type === 'retail') { // retail price
            $price = $product->retail_price;
        }

        // return $price;
        return $sale['price'];
    }

    /**
     * Quantity Verify
     * @param $quantities
     * @param $product
     * @return array
     */
    private function quantityVerify($quantities, $product)
    {
        //quantity verify
        $errors = [
            'errors' => []
        ];

        if (empty($quantities)) {
            $errors['errors']['quantities'] = ['Quantity must be greater then 1'];
        }

        foreach ($quantities as $warehouse_id => $quantity) {

            if ($quantity <= 0) {
                $errors['errors'][$warehouse_id] = ['Quantity must be greater then 1'];
                continue;
            }

            if ($quantity > $product->warehouses->where('id', $warehouse_id)->first()->stock->quantity) {
                $errors['errors'][$warehouse_id] = ['Insufficient quantity'];
            }
        }

        return $errors;
    }


    /**
     * Apply condition in cart
     * @param string $target
     * @param string $name
     * @param string $value
     * @param string $type
     * @param array $attributes
     * @throws \Darryldecode\Cart\Exceptions\InvalidConditionException
     */
    private function applyConditionInCart(string $target, string $name, string $value, string $type = 'vat', array $attributes = [])
    {
        // add condition to only apply on totals, not in subtotal
        $condition = new \Darryldecode\Cart\CartCondition(array(
            'name' => $name,
            'type' => $type,
            'target' => $target, // this condition will be applied to cart's total when getTotal() is called.
            'value' => $value,
            'order' => 1, // the order of calculation of cart base conditions. The bigger the later to be applied.
            'attributes' => $attributes
        ));

        \Cart::condition($condition);
    }

    /**
     * Get Cart Condition
     * @param $name
     * @return
     */
    private function getCartCondition(string $name)
    {
        return \Cart::getCondition($name);
    }
}
