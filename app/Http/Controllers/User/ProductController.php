<?php

namespace App\Http\Controllers\User;

use App\Exports\ProductsExport;
use App\Helpers\Converter;
use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

use Auth;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    private $meta = [
        'title'   => 'Product',
        'menu'    => 'product',
        'submenu' => ''
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
        $this->meta['submenu'] = 'product-list';

        $business_id = Auth::user()->business_id;
        $products = Product::where('business_id', $business_id)->paginate(30);
        $warehouses = Warehouse::where('business_id', $business_id)->active()->get();
        $categories = Category::where('business_id', $business_id)->active()->get();
        $brands = Brand::where('business_id', $business_id)->active()->get();

        return view('user.product.index', compact('products', 'warehouses', 'categories', 'brands'))
            ->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->meta['submenu'] = 'product-list';

        $user         = Auth::user()->business_id;
        $units        = Unit::where('business_id', $user)->get();
        $brands       = Brand::where('business_id', $user)->get();
        $categories   = Category::where('business_id', $user)->get();
        $warehouses   = Warehouse::where('business_id', $user)->active()->get();
        $lang = __('contents');

        return view('user.product.create', compact('units','lang', 'warehouses', 'brands', 'categories'))
            ->with($this->meta);
    }

    public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(ProductRequest $request)
    {
        // return $request->all();

        $product_basic_info = $request->validated();

        $product_basic_info['slug'] = Str::slug($request->name);
        $product_basic_info['business_id'] = Auth::user()->business_id;

        $product_basic_info['code'] = str_pad(Product::withTrashed()->max('id') + 1, 6, '0', STR_PAD_LEFT);


        if (!$request->has('barcode') || empty($request['barcode'])) {
            $product_basic_info['barcode'] = $product_basic_info['code'];
        }

        $product = Product::create($product_basic_info);

        $unit_length = count(explode('/', Unit::find($request->unit_id)->relation));
        $average_purchase_price = $request->purchase_price;

        if($request->quantity) {
            $product->warehouses()->sync($this->productQuantities($request->quantity, $unit_length, $product->unit_code, $average_purchase_price));
        }else{
            $warehouses = Warehouse::all();
            $quantities = [];
            foreach ($warehouses as $warehouse) {
                $quantities[$warehouse->id] = [
                    'quantity'   => 0,
                    'average_purchase_price' => $product->purchase_price,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            $product->warehouses()->sync($quantities);
        }

        session()->flash('success', 'Product created successfully');

        return response()->json('Product created successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('user.product.show', compact('product'))
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
        $this->meta['submenu'] = 'product-list';

        $product = Product::with('warehouses')->where('id', $id)->first();

        $units      = Unit::all();
        $warehouses = Warehouse::active()->get();

        $extras               = collect([]);
        $extras['brands']     = Brand::all();
        $extras['categories'] = Category::all();
        $extras['unit']       = Unit::find($product->unit_id);

        $stock = [];

        foreach ($product->warehouses as $warehouse) {
            $stock[$warehouse->id] = Converter::convert($warehouse->stock->quantity, $extras['unit']->code, 'd')['result'];
        }

        $extras['quantity'] = $stock;
        $lang = __('contents');

        return view('user.product.edit', compact('product','lang', 'units', 'warehouses', 'extras'))
            ->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product_basic_info = $request->validated();

        $product_basic_info['slug'] = Str::slug($request->name);

        $unit_length = count(explode('/', Unit::find($request->unit_id)->relation));

        $product->update($product_basic_info);

        $previous_average_purchase_price = $product->stock[0]->average_purchase_price == 0 ? $product->purchase_price : $product->stock[0]->average_purchase_price ?? 0;

        if($request->quantity) {
            $product->warehouses()->sync($this->productQuantities($request->quantity, $unit_length, $product->unit_code, $previous_average_purchase_price));
        }else{
            $warehouses = Warehouse::all();
            $quantities = [];
            foreach ($warehouses as $warehouse) {
                $quantities[$warehouse->id] = [
                    'quantity'   => 0,
                    'average_purchase_price' => $product->purchase_price,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            $product->warehouses()->sync($quantities);
        }

        session()->flash('success', 'Product updated successfully');

        return response()->json(route('product.index'), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->back()->withSuccess('Product deleted successfully');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|ViewAlias
     */
    public function viewTrashed()
    {
        $trashedProducts = Product::onlyTrashed()->get();

        return view('user.product.trashed', compact('trashedProducts'))->with($this->meta);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->forceDelete();
        return redirect()->back()->withSuccess('Product delete successfully');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->deleted_at = null;
        $product->save();

        return redirect()->back()->withSuccess('Product restore successfully');
    }

    public function search(Request $request)
    {
        $this->meta['submenu'] = 'product-list';

        $products = Product::query();

        $products = $this->searchQuery($products, $request)->paginate(30);

        $warehouses = Warehouse::active()->get();
        $categories = Category::active()->get();
        $brands = Brand::active()->get();

        return view('user.product.index', compact('products', 'warehouses', 'categories', 'brands'))
            ->with($this->meta)
            ->with('searched_query', collect($request->all()));
    }


    /**
     * Perform Search
     * @param $products
     * @param $request
     * @return mixed
     */
    private function searchQuery($products, $request)
    {
        // barcode wise search
        if ($request->has('barcode') and !empty($request->barcode)) {
            $products->where('barcode', $request->barcode);
        }

        // category wise search
        if ($request->has('category_id') and !empty($request->category_id)) {
            $products->where('category_id', $request->category_id);
        }

        // brand wise search
        if ($request->has('brand_id') and !empty($request->brand_id)) {
            $products->where('brand_id', $request->brand_id);
        }

        // warehouse wise search
        if ($request->has('warehouse_id') and !empty($request->warehouse_id)) {
            $warehouse_id = $request->warehouse_id;
            $products->whereHas('warehouses', function (Builder $query) use ($warehouse_id) {
                $query->where('warehouse_id', $warehouse_id);
            });
        }

        return $products;
    }


    /**
     * Calculate product quantities
     * @param $quantities
     * @param $unit_length
     * @param $unit_code
     * @return array
     */
    private function productQuantities($quantities, $unit_length, $unit_code, $average_purchase_price, $call_from = 'store/update')
    {
        $product_quantities = [];

        foreach ($quantities as $warehouse_id => $quantity) {
            $quantity = $this->getQuantityToUnit($quantity, $unit_length, $unit_code)['result'];

            if (!$quantity) continue;

            //for store and update method
            if ($call_from === 'store/update') {
                if ($quantities) {
                    $product_quantities[$warehouse_id] = [
                        'quantity'   => $quantity,
                        'average_purchase_price' => $average_purchase_price,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                } else {
                    $product_quantities[$warehouse_id] = [
                        'quantity'   => 0,
                        'average_purchase_price' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            } elseif ($call_from === 'addToCart') {
                $product_quantities[$warehouse_id] = $quantity;
            }
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
    public function getQuantityToUnit($quantity, $unit_length, $unit_code)
    {
        $input = '';
        for ($i = 0; $i < $unit_length; $i++) {
            if (!array_key_exists($i, $quantity) || $quantity[$i] == null) {
                $quantity[$i] = 0;
            }
            $input .= $quantity[$i];

            if ($unit_length - $i > 1) {
                $input .= '/';
            }
        }

        return Converter::convert($input, $unit_code);
    }

    /**
     * Get unit Length
     * @param $unit_code
     * @return int
     */
    public function getUnitLength($unit_code)
    {
        return count(explode('/', Unit::where('code', $unit_code)->first()->relation));
    }

    /*--------AJAX Request Start--------*/

    /**
     * All active products
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function allActiveProducts()
    {
        $products = Product::has('warehouses')->active()->get();
        return response()->json($products, 200);
    }*/


    /**
     * Get Product details
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function productDetails(Request $request)
    {
        $product = Product::with('warehouses')->where('code', $request->code)->first();
        return response()->json($product, 200);
    }*/
    /*---------AJAX Request End--------*/
}
