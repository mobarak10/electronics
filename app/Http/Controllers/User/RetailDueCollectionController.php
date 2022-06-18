<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class RetailDueCollectionController extends Controller
{
    private $due_manage;
    private $meta = [
        'title'   => 'Due Management',
        'menu'    => 'manage-due',
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
        $this->meta['submenu'] = 'retail';
        $sales = Sale::where('sale_type', '=', 'retail')->where('due', '>', 0)->paginate(30);

        return view('user.retail-due-collection.index', compact('sales'))->with($this->meta);
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
        //
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
        //
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
        $sale = Sale::findOrFail($id);

        $sale->increment('paid', $request->paid);
        $sale->increment('discount', $request->discount);

        $sale->promise_date = $request->promise_date;
        $due = $sale->grand_total - $sale->paid;
        $sale->due = $due;

        $sale->save();

        $retail_due_collection_data = $request->validate([
            'date' => 'required|date',
            'paid' => 'required|numeric',
            'discount' => 'nullable|numeric',
        ]);

        $sale->retailDueCollection()->create($retail_due_collection_data);

        return redirect()->route('retailDueCollection.index')->withSuccess('Due received successfully!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * create retail due management
     *
     * @param [type] $id
     * @return void
     */
    public function createRetailDue($id)
    {
        $sale = Sale::findOrFail($id);

        return view('user.retail-due-collection.create', compact('sale'))->with($this->meta);
    }
}
