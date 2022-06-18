<?php

namespace App\Http\Controllers\User;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Party;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Auth;

class BrandController extends Controller
{
    private $meta = [
        'title'   => 'Brand',
        'menu'    => 'setting',
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
        $this->meta['submenu'] = 'brand-list';

        $business_id = Auth::user()->business_id;
        $brands     = Brand::where('business_id', $business_id)->paginate(15);

        return view('user.suppliers.brand.index', compact('brands'))
            ->with($this->meta);
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
        $request['slug'] = Str::slug($request->name);

        $request->validate([
            'name'       => 'required|string|max:191',
            'slug'       => 'required|unique:brands',
        ]);

        $request['code'] = 'BRA' . str_pad(Brand::withTrashed()->max('id') + 1, 6, '0',STR_PAD_LEFT);
        $request['business_id'] = Auth::user()->business_id;

        Brand::create($request->only(['code', 'name', 'slug', 'business_id']));

        return redirect()->back()->withSuccess('Brand created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->meta['submenu'] = 'brand-list';

        $business_id = Auth::user()->business_id;
        $brand = Brand::where('business_id', $business_id)->find($id);

        return view('user.suppliers.brand.edit', compact('brand'))
            ->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $request['slug'] = Str::slug($request->name);

        $request->validate([
            'name'       => 'required|string|max:191',
            'slug'       => 'required|unique:brands,slug,' . $brand->id,
            'active'     => 'required|boolean',
        ]);

        $brand->update($request->only(['name', 'slug', 'active']));

        return redirect()->route('brand.index')->withSuccess('Brand updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return redirect()->back()->withSuccess('Brand deleted successfully');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|ViewAlias
     */
    public function viewTrashed()
    {
        $trashedBrands = Brand::onlyTrashed()->get();

        return view('user.suppliers.brand.trashed', compact('trashedBrands'))->with($this->meta);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function forceDelete($id)
    {
        $brand = Brand::onlyTrashed()->findOrFail($id);
        $brand->forceDelete();
        return redirect()->back()->withSuccess('Brand delete successfully');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        $brand = Brand::onlyTrashed()->findOrFail($id);
        $brand->deleted_at = null;
        $brand->save();

        return redirect()->back()->withSuccess('Brand restore successfully');
    }

    /**
     * Toggle Active
     * @param Brand $brand
     * @return mixed
     */
    public function toggleActive(Brand $brand)
    {
        $brand->update(['active' => !$brand->active]);

        return redirect()->back()->withSuccess($brand->name . ' brand successfully ' . ($brand->active === true ? 'activated' : 'deactivated'));
    }

    /*------------------------AJAX Request Methods Start------------------------*/

    /**
     * Get Categories from Brand
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories(Request $request)
    {
        $categories = Brand::active()->where('id', $request->brandId)->first()->categories;
        return response()->json($categories, 200);
    }


    /*-------------------------AJAX Request Methods End------------------------*/

}
