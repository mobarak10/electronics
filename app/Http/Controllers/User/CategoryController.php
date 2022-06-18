<?php

namespace App\Http\Controllers\User;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

use Auth;
use Illuminate\View\View as ViewAlias;

class CategoryController extends Controller
{
    private $meta = [
        'title'   => 'Category',
        'menu'    => 'setting',
        'submenu' => ''
    ];

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $this->meta['submenu'] = 'category-list';
        $business_id = Auth::user()->business_id;
        $categories = Category::where('business_id', $business_id)->paginate(15);

        return view('user.category.index', compact('categories'))->with($this->meta);
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
            'name'        => 'required|string|max:191',
            'slug'        => 'string|unique:categories',
            'description' => 'nullable|string'
        ], [], [
            'slug' => 'name'
        ]);

        $request['code'] = 'CAT' . str_pad(Category::withTrashed()->max('id') + 1, '6', '0', STR_PAD_LEFT);
        $request['business_id'] = Auth::user()->business_id;

        Category::create($request->only(['name', 'slug', 'description', 'code', 'business_id']));

        return redirect()->back()->withSuccess('Category created successfully');
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
    public function edit(Category $category)
    {
        $this->meta['submenu'] = 'category-list';

        return view('user.category.edit', compact('category'))
            ->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request['slug'] = Str::slug($request->name);

        $request->validate([
            'name'        => 'required|string|max:191',
            'slug'        => 'string|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'active'      => 'required|boolean'
        ], [], [
            'slug' => 'name'
        ]);

        $category->update($request->only(['name', 'slug', 'description', 'active']));

        return redirect()->route('category.index')->withSuccess('Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category) {
//        $this->authorize('delete', $category);

        $category->delete();
        return redirect()->back()->withSuccess('Category move to trashed successfully');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|ViewAlias
     */
    public function viewTrashed()
    {
        $trashedCategories = Category::onlyTrashed()->get();

        return view('user.category.trashed', compact('trashedCategories'))->with($this->meta);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();
        return redirect()->back()->withSuccess('Category delete successfully');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->deleted_at = null;
        $category->save();

        return redirect()->back()->withSuccess('Category restore successfully');
    }

    /**
     * Toggle Active
     * @param Category $category
     * @return mixed
     */
    public function toggleActive(Category $category)
    {
        $category->update(['active' => !$category->active]);

        return redirect()->back()->withSuccess($category->name . ' category successfully ' . ($category->active === true ? 'activated' : 'deactivated'));
    }
}
