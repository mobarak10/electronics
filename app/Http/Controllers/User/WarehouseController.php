<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Warehouse;
use App\Models\User\User;

use Auth;

class WarehouseController extends Controller
{

    private $meta = [
        'title' => 'Warehouse',
        'menu' => 'setting',
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

        $business_id = Auth::user()->business_id;
        $warehouses = Warehouse::where('business_id', $business_id)->orderBy('id', 'asc')->paginate(15);
        return view('user.warehouse.index', compact('warehouses'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $employees = User::all();
        return view('user.warehouse.create', compact('employees'))->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $code = 'WAH' . str_pad(Warehouse::max('id') + 1, 4, '0', STR_PAD_LEFT);

        $warehouse = new Warehouse;
        $warehouse->code = $code;
        $warehouse->title = $request->title;
        $warehouse->address = $request->address;
        $warehouse->user_id = $request->user_id;
        $warehouse->description = $request->description;
        $warehouse->business_id = Auth::user()->business_id;

        if($warehouse->save()) {
            $request->session()->flash("success", "Warehouse Added Successfully");
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Warehouse $warehouse){
        return view('user.warehouse.show', compact('warehouse'))->with($this->meta);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $warehouse = Warehouse::find($id);
        $employees = User::all();
        return view('user.warehouse.edit', compact('warehouse', 'employees'))->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $warehouse = Warehouse::find($id);

        $warehouse->title = $request->title;
        $warehouse->address = $request->address;
        $warehouse->user_id = $request->user_id;
        $warehouse->description = $request->description;
        $warehouse->status = $request->status;

        if($warehouse->save()) {
            $request->session()->flash("success", "Warehouse Updated Successfully");
        }

        return redirect()->route('warehouse.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $warehouse = Warehouse::find($id);

        if ($warehouse->delete()) {

            session()->flash('success', 'Warehouse delete Successfully');
            return back();
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|ViewAlias
     */
    public function viewTrashed()
    {
        $trashedWarehouses = Warehouse::onlyTrashed()->get();

        return view('user.warehouse.trashed', compact('trashedWarehouses'))->with($this->meta);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function forceDelete($id)
    {
        $warehouse = Warehouse::onlyTrashed()->findOrFail($id);
        $warehouse->forceDelete();
        return redirect()->back()->withSuccess('Warehouse delete successfully');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        $warehouse = Warehouse::onlyTrashed()->findOrFail($id);
        $warehouse->deleted_at = null;
        $warehouse->save();

        return redirect()->back()->withSuccess('Warehouse restore successfully');
    }

    // status change
    public function changeStatus($id){
        $warehouse = Warehouse::find($id);
        $warehouse->status = ($warehouse->status) ? 0 : 1;
        $warehouse->save();

        return redirect()->back()->withSuccess($warehouse->name . ' successfully ' . ($warehouse->status == true ? 'activated' : 'deactivated'));
    }


    /*----AJAX Request Methods Start----*/

    /**
     * All active warehouses
     * @return \Illuminate\Http\JsonResponse
     */
    public function allActiveWarehouses()
    {
        $business_id = Auth::user()->business_id;
        return response()->json(Warehouse::where('business_id', $business_id)->active()->get(), 200);
    }
    /*----AJAX Request Methods End----*/
}
