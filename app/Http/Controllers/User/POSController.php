<?php

namespace App\Http\Controllers\User;

use App\Exports\InvoicesExport;
use App\Helpers\Converter;
use App\Helpers\QuantityHelper;
use App\Models\Cash;
use App\Models\User\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Party;
use App\Models\SaleReturn;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Brand;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class POSController extends Controller
{
    use QuantityHelper;

    private $meta = [
        'title' => 'POS',
        'menu' => 'pos',
        'submenu' => '',
        'header' => false
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->meta['submenu'] = 'list';

        $business_id = Auth::user()->business_id;
        $sales = Sale::where('business_id', $business_id)->orderBy('id', 'desc')->paginate(100);
        $employees = User::all();
        $date = null;

        if (request()->search) {
            $sales = Sale::query(); // create sale instance
            $where[] = ['business_id', '=', $business_id]; // set business-id conditions
            $date = []; // set date

            // pluck all the employees using phone number
            $party = Party::where('phone', request()->condition['phone'])->get()->pluck('id');

            foreach (request()->condition as $input => $value) {
                if ($value != null) {
                    if ($input == 'phone') {
                        if (count($party) > 0) {
                            $where[] = ['party_id', '=', $party];
                        } else {
                            $where[] = ['party_id', '=', null];
                        }
                    } else {
                        $where[] = [$input, '=', $value];
                    }
                }
            }

//            return $where;

            // set query
            $sales = $sales->where($where);

            // set date
            if (request()->date['from'] != null) {
                $date[] = date(request()->date['from'] . ' 00:00:00');
            }

            if (request()->date['to'] != null) {
                $date[] = date(request()->date['to'] . ' 23:59:00');
            } else {
                if (request()->date['from'] != null) {
                    $date[] = date('Y-m-d') . ' 23:59:00';
                }
            }

            if (count($date) > 0) {
                $sales = $sales->whereBetween('created_at', $date);
            }

            // get data
            $sales = $sales->paginate(50);
        }

        return view('user.pos.index', compact('sales', 'employees'))->with($this->meta);
    }

    public function create()
    {
        $this->meta['submenu'] = 'add';
        $this->meta['aside'] = false; //hide aside

        $cashes = Cash::all();
        $warehouses = Warehouse::with('products.unit')->get();
        $customers = Customer::select('id', 'name', 'phone', 'type', 'address', 'balance')->get();
        $bank_accounts = BankAccount::with('bank')
            ->get();
        $customer_type = config('coderill.customer_type');
        $lang = __('contents');


        return view('user.pos.create', compact(
                'warehouses',
                'cashes',
                'bank_accounts',
                'customers',
                'lang',
                'customer_type')
        )->with($this->meta);
    }

    public function show($id)
    {
        $sale = Sale::with('saleDetails.quantities')->where('id', $id)->first();

        $calculated_amount = [];
        $calculated_amount['vat'] = ($sale->subtotal * $sale->vat) / 100; //vat
        $calculated_amount['total'] = $sale->subtotal + $calculated_amount['vat']; // total with vat
        $calculated_amount['discount'] = ($sale->discount_type === 'percentage') ? ($calculated_amount['total'] * $sale->discount) / 100 : $sale->discount; // calculate discount
        $calculated_amount['grand_total'] = ($sale->subtotal + $calculated_amount['vat']) - $calculated_amount['discount']; //calculate grand total
        $calculated_amount['paid'] = ($sale->change > 0) ? abs($sale->tendered - $sale->change) : abs($calculated_amount['grand_total'] - $sale->due); // calculate paid amount

        return view('user.pos.show', compact('sale', 'calculated_amount'))
            ->with($this->meta);
    }

    public function destroy($id)
    {
//        return             $sale = Sale::with('saleDetails', 'salePayment')->findOrFail($id);
        DB::transaction(function () use ($id) {
            $sale = Sale::with('saleDetails', 'salePayment')->findOrFail($id);

            $removed_sale_details = $sale->saleDetails->pluck('id');

            // remove all sale details & update stock
            $this->removePreviousItemAndUpdateQuantity($removed_sale_details, $sale);

            // delete sale payment data
            $sale->salePayment()->where('sale_id', $sale->id)->delete();

            $previous_paid = ($sale->paid - $sale->change);

            if ($sale->payment_type === 'cash') {
                $sale->salePayment->cash()->decrement('amount', $previous_paid);
            } else {
                $sale->salePayment->bankAccount()->decrement('balance', $previous_paid);
            }

            // increment customer balance
            $sale->customer()->increment('balance', $sale->due);

            $sale->delete();
        });

        return redirect()->route('pos.index')->withSuccess('Sale deleted successfully');
    }

    /**
     * remove previous sale details and add sale quantity in sale warehouse
     *
     * @param [type] $removed_sale_details
     * @param [type] $sale
     * @param [type] $request
     * @return void
     */
    public function removePreviousItemAndUpdateQuantity($removed_sale_details, $sale)
    {
        if ($removed_sale_details) {
            foreach ($removed_sale_details as $item) {
                // get deleted product
                $details = $sale->saleDetails->where('id', $item)->first();
                // quantity that should be add in warehouse
                $_quantity = $details->quantity;
                // get product
                $product = Product::findOrFail($details->product_id);
                // get warehouse
                $_warehouse = $product->warehouses->where('id', $sale->warehouse_id)->first();
                if ($_warehouse) {
                    //get exists quantity
                    $previous_quantity = $_warehouse->stock->quantity;
                    //update stocks
                    $product->warehouses()->updateExistingPivot($sale->warehouse_id, [
                        'quantity' => $previous_quantity + $_quantity,
                    ]);
                } else { // no previous warehouse exists
                    //add new stock in for products
                    $product->warehouses()->attach([
                        $sale->warehouse_id => [
                            'quantity' => $_quantity,
                            'average_purchase_price' => $product->purchase_price,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    ]);
                }
                // deleted that product which in no available in new updated sale
                $sale->saleDetails->find($item)->delete();
            }
        }
    }


    /**
     * Checkout view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkout()
    {
        return view('user.pos.checkout')
            ->with($this->meta);
    }

    /*--------Helper Method Start-------*/

    /**
     * Return Product total quantity
     * @param $quantities
     * @param $unit_length
     * @param $unit_code
     * @return int
     */
    private function totalProductQuantity($quantities, $unit_length, $unit_code)
    {
        $total_return_quantity = 0;
        foreach ($quantities as $warehouse_id => $quantity) {
            //get return unit
            $return_units = Converter::convert($this->formattedSingleQuantity($quantity, $unit_length), $unit_code);
            //sum total return quantity
            $total_return_quantity += $return_units['result'];
        }
        return $total_return_quantity;
    }

    /**
     * Get Original Sale Details Quantity
     * @param $details
     * @return array
     */
    private function originalSaleDetailsQuantity($details)
    {
        $original_sale = [];

        foreach ($details as $detail) {
            //total sale quantity
            $total_sale_quantity = 0;
            //for every warehouse's quantity
            foreach ($detail->quantities as $quantities) {
                $total_sale_quantity += $quantities->quantity->quantity;
            }

            //add product sale quantity with product id
            $original_sale[$detail->product_id] = $total_sale_quantity;
        }

        return $original_sale;
    }
    /*--------Helper Method End-------*/

    /*--------AJAX Request Start--------*/

    /**
     * All active products
     * @return \Illuminate\Http\JsonResponse
     */
    public function allActiveProducts()
    {
        $user = Auth::user()->business_id;
        $products = Product::where('business_id', $user)->has('warehouses')->with(['warehouses', 'unit'])->active()->get();
        return response()->json($products, 200);
    }

    public function filterWiseProducts(Request $request)
    {

        $business_id = auth()->user()->business_id;

        $products_query = Product::where('business_id', $business_id)
            ->has('warehouses')->with(['warehouses', 'unit'])
            ->active();

        $products_query = $this->productFilters($products_query, $request);

        $products = $products_query->get();

        //return response($request->all());
        return response($products);
    }

    /**
     * Product Filters
     */
    private function productFilters($products, $request)
    {
        foreach ($request->filters as $key => $value) {
            if ($key == 'categoryId' and $value) {
                $products = $products->where('category_id', $value);
                continue;
            }

            if ($key === 'productName' and $value) {
                $products = $products->where('name', 'LIKE', '%' . $value . '%');
                continue;
            }
        }

        return $products;
    }


    /**
     * All products
     * @return \Illuminate\Http\JsonResponse
     */
    public function allProducts()
    {
        $user = Auth::user()->business_id;
        $products = Product::where('business_id', $user)->with(['warehouses', 'unit'])->active()->get();

        return response()->json($products, 200);
    }


    /**
     * Get Product details
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function productDetails(Request $request)
    {

        $productQuery = Product::with(['warehouses', 'unit']);

        if ($request->has('code')) {
            $productQuery->where('code', $request->code);
        } else if ($request->has('barcode')) {
            $productQuery->where('barcode', $request->barcode);
        }

        $product = $productQuery->firstOrFail();

        return response()->json($product, 200);
    }


    /**
     * All active categories
     * @return \Illuminate\Http\JsonResponse
     */
    public function allActiveCategories()
    {
        $business_id = Auth::user()->business_id;
        return response()->json(Category::where('business_id', $business_id)->active()->get(), 200);
    }

    /**
     * Validate quantities
     * @param $sale
     * @param $_quantities
     * @return array
     */
    private function validateQuantity($sale, $_quantities)
    {
        $errors = [];

        foreach ($_quantities as $product_id => $quantities) {
            $current_sale_product = $sale->saleDetails->where('product_id', $product_id)->first();

            $warehouses = $current_sale_product->quantities;

            foreach ($quantities as $warehouse_id => $quantity) {
                $formatted_quantity = $this->formattedSingleQuantity($quantity, count($current_sale_product->product->product_unit_labels));
                $warehouse_wise_quantity_units = Converter::convert($formatted_quantity, $current_sale_product->product->unit_code, 'u');
                $available_quantity = $warehouses->where('id', $warehouse_id)->first()->sale_product_rest_quantity;

                if ($warehouse_wise_quantity_units['result'] > $available_quantity) {
                    $errors[$sale->id][$product_id][$warehouse_id] = 'Insufficient quantity';
                }
            }
        }
        return $errors;
    }

    public function deliver(Sale $sale)
    {

        $sale->update([
            'delivered' => !$sale->delivered
        ]);

        return redirect()->back()->withSuccess('Status changed successfully');
    }

    /**
     * Salesmen list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function salesmen()
    {
        $salesman = User::with('media')->get();

        return response()->json($salesman);
    }

    /**
     * export excel
     * @return mixed
     *
     */
    public function export()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }
}
