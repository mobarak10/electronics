<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Business;
use App\Models\Warehouse;
use App\Models\GlAccountHead;

class BusinessController extends Controller
{
        private $meta = [
        'title' => 'Business',
        'menu' => 'business',
        'submenu' => ''
    ];

    public function __construct() {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $businesses = Business::all();
        return view('admin.business.index', compact('businesses'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.business.create')->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->validate([
                'name' => 'required|string',
            'thumbnail' => 'nullable|string',
            'description' => 'nullable|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users,email',
        ]);


        $business = Business::create($data);

        // flash data
        session()->flash('success', 'Business added successfully.');

        // view
        return redirect(route('admin.business.index'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $business = Business::find($id);
        return view('admin.business.show', compact('business'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $business = Business::find($id);

        return view('admin.business.edit', compact('business'))->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $business = Business::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string',
            'thumbnail' => 'nullable|string',
            'description' => 'nullable|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users,email',
        ]);


        $business->update($data);

        // flash data
        session()->flash('success', 'Business Updated successfully.');

        // view
        return redirect(route('admin.business.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $business = Business::find($id);

        if ($business->delete()) {
            session()->flash('success', 'business delete Successfully.');
            return back();
        }
    }

    /**
     *
     */
    public function warehouse(Request $request) {
        $warehouses = Warehouse::where('business_id', $request->id)->get();
        return response()->json($warehouses, 200);
    }

    /**
     *
     */
    public function expenditure(Request $request) {
        $expenditures = GlAccountHead::where('business_id', $request->id)->get();
        return response()->json($expenditures, 200);
    }
}
