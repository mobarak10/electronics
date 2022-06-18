<?php
namespace App\Http\Controllers\User;

use App\Helpers\Converter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductionIn;
use App\Helpers\QuantityHelper;
use App\Models\Unit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductionInController extends Controller
{
    use QuantityHelper;

    private $meta = [
        'title'   => 'Production In',
        'menu'    => 'production',
        'submenu' => ''
    ];

    private $productionIn_cart;

    public function __construct()
    {
        $this->middleware('auth');

        //add purchase cart
        $this->productionIn_cart = app('purchase');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|Response|View
     */
    public function index()
    {
        $production_in = ProductionIn::all();

        return view('user.production.production-in.index', compact('production_in'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|Response|View
     */
    public function create()
    {
        $this->meta['submenu'] = 'add';
        $this->meta['aside'] = false; // hide aside

        return view('user.production.production-in.create')
            ->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'voucher_no'  => 'required|string',
            'date'        => 'required|date',
        ]);

        $raw_purchase = [];
        $raw_purchase['voucher_no'] = $request->voucher_no;
        $raw_purchase['date'] = $request->date;

        $raw_purchase['note'] = $request->note;
        $raw_purchase['business_id'] = Auth::user()->business_id;
        $raw_purchase['user_id'] = Auth::user()->id;

        //insert into purchase table
         $productionIn = ProductionIn::create($raw_purchase);

        //purchase details
        foreach ($this->productionIn_cart->getContent() as $item){
            $product = Product::find($item->id);

            //set quantity for products
            foreach ($item->attributes->meta['request']['quantities'] as $warehouse_id => $quantity){
                $current_warehouse = $product->warehouses->where('id', $warehouse_id)->first();
                $present_quantity = $current_warehouse->stock->quantity;
                $_quantity = Converter::convert($this->formattedSingleQuantity($quantity, $product->unit->unit_length), $product->unit_code)['result'];
                $productionIn_quantities = [
                    'product_id' => $item->id,
                    'warehouse_id' => $warehouse_id,
                    'quantity' => $_quantity,
                ];

                if ($present_quantity >= $_quantity) { // if quantity greater or equal

                    $productionIn_quantities = [
                        'product_id' => $item->id,
                        'warehouse_id' => $warehouse_id,
                        'quantity' => $_quantity,
                    ];

                    //add purchase quantity for purchase details
                    $productionIn->details()->create($productionIn_quantities);

                    //quantity after sell
                    $current_quantity = $present_quantity - $_quantity;

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

        //clear cart contents
        $this->setClearCartContents();
        //clear session data
        $this->clearSessionData(['date', 'voucher_no', 'note']);

        return response()->json($productionIn, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|Response|View
     */
    public function show($id)
    {
        //get the current purchase with details
        $production_in = ProductionIn::with('details')->where('id', $id)->first();

        return view('user.production.production-in.show', compact('production_in'))
            ->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Add to cart
     * @param Request $request
     * @return JsonResponse
     */
    public function addToCart(Request $request)
    {
        $product = $this->cartFormat($request);

        $product_verify = Product::with(['warehouses', 'unit'])->active()->where('id', $request->product_id)->first();
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

        //get unit length
        $unit_length = $this->getUnitLength($product_verify->unit_code);

        //total quantity warehouse wise
        $quantities = $this->productQuantities($request->quantities, $unit_length, $product_verify->unit_code);

        //check for quantity exists
        $errors = $this->quantityVerify($quantities, $product_verify);

        //if has error then return error
        if (count($errors['errors'])) {
            return response()->json($errors, 422);
        }

        //if already added product then remove
        if($this->productionIn_cart->get($request->product_id)){
            $this->productionIn_cart->remove($request->product_id);
        }

        $this->productionIn_cart->add($product);

        //set session data
        $this->setSessionData($request->only(
            ['date', 'voucher_no', 'note']
        ));

        //get session data
        $response = $this->getAllSessionData();

        return response()->json($response, 200);
    }

    /**
     * Remove item form cart
     * @param Request $request
     * @return JsonResponse
     */
    public function removeCartItem(Request $request)
    {
        $this->productionIn_cart->remove($request->product_id);

        $response = $this->getAllSessionData();

        return response()->json($response, 200);
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
            'price' => 0,
            'quantity' => $total_formatted_quantities,
            'attributes' => [
                'meta' => [
                    'product' => $product,
                    'request' => $request->all(),
                    'total_quantity' =>  $this->getQuantityDisplayFormat($total_formatted_quantities, $product->unit_code)
                ]
            ]
        ];
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

    /**
     * @param $quantity
     * @param $unit_code
     * @return array
     */
    private function getQuantityDisplayFormat($quantity, $unit_code){

        return Converter::convert($quantity, $unit_code, 'd');
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
     * Clear cart content with session data
     * @return JsonResponse
     */
    public function clearCartContents()
    {
        $this->setClearCartContents();

        $this->clearSessionData(['date', 'voucher_no', 'note']);

        $response = $this->getAllSessionData();

        return response()->json($response, 200);
    }

    /**
     * Clear cart contents
     */
    private function setClearCartContents()
    {
        $this->productionIn_cart->clear();
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
                'voucher_no' => session()->get('voucher_no', null),
                'note' => session()->get('note', null)
            ],
            'purchase_items' => $this->getCartContentsWithPrice(),
        ];
    }

    /**
     * Get Cart items with price
     * @return Collection
     */
    private function getCartContentsWithPrice()
    {
        $items = $this->productionIn_cart->getContent();

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
     * Get unit Length
     * @param $unit_code
     * @return int
     */
    private function getUnitLength($unit_code)
    {
        return count(explode('/', Unit::where('code', $unit_code)->first()->relation));
    }
}
