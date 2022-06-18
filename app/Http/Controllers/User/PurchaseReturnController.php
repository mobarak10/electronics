<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Business;
use App\Models\Cash;
use App\Models\Party;
use App\Models\Product;
use App\Models\PurchaseReturn;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseReturnController extends Controller
{
    protected $sale_return;

    private $meta = [
        'title' => 'Purchase Return',
        'menu' => 'purchase-return',
        'submenu' => '',
        'header' => false
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

        $purchase_return_query = PurchaseReturn::query();

        if (request('from_date')) {
            $purchase_return_query->whereDate('date', '>=', request('from_date'));
        }

        if (request('to_date')) {
            $purchase_return_query->whereDate('date', '<=', request('to_date'));
        }

        if (request('phone')) {
            $purchase_return_query->whereHas('party', function ($query) {
                $query->where('phone', 'like', '%' . request()->get('phone') . '%');
            });
        }

        $purchase_returns = $purchase_return_query->paginate(30);

        return view('user.purchase-return.index', compact('purchase_returns'))
            ->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $this->meta['submenu'] = 'add';
        $this->meta['aside'] = false; //hide aside

        $cashes = Cash::all();

        $warehouses = Warehouse::query()
            ->with('products.unit')
            ->get();

        $parties = Party::query()
            ->select('id', 'name', 'phone', 'address', 'balance')
            ->get();

        $bank_accounts = BankAccount::query()
            ->with('bank')
            ->get();
            $lang = __('contents');

        return view('user.purchase-return.create', compact('lang', 'warehouses', 'cashes', 'bank_accounts', 'parties'))
            ->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        return $request->all();

        // TODO Create purchase return
        $data = $request->validate([
            'date' => 'required|date',
            'party_id' => 'required|int|exists:\App\Models\Party,id',
            'warehouse_id' => 'required|int|exists:\App\Models\Warehouse,id',
            'subtotal' => 'required|numeric',
            'products' => 'required|array',
            'products.*.product_id' => 'required|int|exists:\App\Models\Product,id',
            'products.*.return_price' => 'required|numeric',
            'products.*.quantity' => 'required|numeric',
            'products.*.line_total' => 'required|numeric',
            'products.*.quantity_in_unit' => 'required|array',
        ]);

        $data['return_no'] = 'RTRN' . str_pad(PurchaseReturn::max('id') + 1, 8, '0', STR_PAD_LEFT);
        $data['user_id'] = auth()->id();
        $data['business_id'] = auth()->user()->business_id;

        $purchase_return = null;


        DB::transaction(function () use ($data, $request, &$purchase_return) {
            // create purchase return
            $purchase_return = PurchaseReturn::create($data);

            // create purchase return products
            $return_products = $purchase_return->purchaseReturnProducts()
                ->createMany($data['products']);

            // TODO Decrement stock
            $warehouse = Warehouse::findorFail($request->warehouse_id);

            $return_products->each(function ($return_product) use ($warehouse) {
                $product = $warehouse->products()
                    ->where('products.id', $return_product->product_id)
                    ->first();

                // if product exists on warehouse
                if ($product) {
                    $product->stock
                        ->decrement('quantity', $return_product->quantity);
                } else {
                    // if product not exists on warehouse

                    // find the product
                    $_product = Product::find($return_product->product_id);

                    // attach into stock
                    $warehouse->products()
                        ->attach($return_product->product_id, [
                            'quantity' => ($return_product->quantity * -1),
                            'average_purchase_price' => $_product->purchase_price,
                        ]);
                }
            });

            // Increment party balance
            $purchase_return->party()
                ->increment('balance', $purchase_return->subtotal);
        });

        return $purchase_return;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show($id)
    {
        $purchase_return = PurchaseReturn::query()
            ->with(['party', 'purchaseReturnProducts.product'])
            ->findorFail($id);
        $business = Business::findOrFail(auth()->user()->business_id);

        return view('user.purchase-return.show', compact('purchase_return', 'business'))
            ->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
