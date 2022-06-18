<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Unit;
use Auth;

class UnitController extends Controller
{
    private $meta = [
        'title' => 'Unit',
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
    public function index()
    {
        $business_id = Auth::user()->business_id;
        $units = Unit::where('business_id', $business_id)->paginate(15);
        return view('user.unit.index', compact('units'))->with($this->meta);
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
        $code = str_pad(Unit::max('id') + 1, 4, '0', STR_PAD_LEFT);
        $unit = new Unit;

        $unit->name = $request->name;
        $unit->code = $code;
        $unit->labels = $request->label;
        $unit->relation = $request->relation;
        $unit->description = $request->description;
        $unit->business_id = Auth::user()->business_id;

        if ($unit->save()) {

            session()->flash('success', 'Unit insert Successfully.');
            return redirect(route('unit.index'));
        }
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
        $unit = Unit::find($id);

        return view('user.unit.edit', compact('unit'))->with($this->meta);
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
        $unit = Unit::find($id);

        $unit->name = $request->name;
        $unit->labels = $request->label;
        $unit->relation = $request->relation;
        $unit->description = $request->description;

        if ($unit->save()) {
            session()->flash('success', 'Unit update Successfully.');
            return redirect(route('unit.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unit = Unit::find($id);

        if ($unit->delete()) {
            session()->flash('success', 'Unit delete Successfully.');
            return back();
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|ViewAlias
     */
    public function viewUnitTrashed()
    {
        $trashedUnit = Unit::onlyTrashed()->get();

        return view('user.unit.trashed', compact('trashedUnit'))->with($this->meta);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function forceDelete($id)
    {
        $unit = Unit::onlyTrashed()->findOrFail($id);
        $unit->forceDelete();
        return redirect()->back()->withSuccess('Unit delete successfully');
    }

    public function restore($id)
    {
        $unit = Unit::onlyTrashed()->findOrFail($id);
        $unit->deleted_at = null;
        $unit->save();

        return redirect()->back()->withSuccess('Unit restore successfully');
    }
}
