<?php

namespace App\Http\Controllers\User;

use App\Helpers\Slug;
use App\Models\Cash;
use App\Models\User;
use App\Models\BalanceTransfer;
use App\Models\Ledger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

use Auth;

class CashController extends Controller
{
    private $meta = [
        'title' => 'Cash',
        'menu' => 'cash',
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
        $cashes = Cash::where('business_id', $business_id)->orderBy('id', 'desc')->paginate(15);
        $total_cash = Cash::where('business_id', $business_id)->sum('amount');

        return view('user.cash.index', compact('cashes', 'total_cash'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.cash.create')->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request) {
        // dd($request->all());

//        $this->authorize('create', Cash::class);

        // validation
        $request->validate([
            'title' => 'required|max:190',
            'amount' => 'required|numeric',
        ]);

        // insert
        $cash = new Cash();
        $cash->title = $request->title;
        $cash->slug = Slug::instance(Cash::class, 'slug')->createSlug($request->title);
        $cash->amount = $request->amount;
        $cash->business_id = Auth::user()->business_id;

        // view
        if($cash->save()) {
            $request->session()->flash('success', 'New cash information successfully inserted.');
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\user\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $business_id = Auth::user()->business_id;
        $cash = Cash::where('business_id', $business_id)->find($id);
        $ledgers = $cash->ledgers()->latest()->paginate(15);
        return view('user.cash.show', compact('cash', 'ledgers'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\user\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
//        $this->authorize('edit', Cash::class);
        $business_id = Auth::user()->business_id;
        $cash = Cash::where('business_id', $business_id)->find($id);
        return view('user.cash.edit', compact('cash'))->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\user\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cash $cash) {
        // set slug
        $request['slug'] = Str::slug($request->title);

        // validation
        $request->validate([
            'title' => 'required|max:190',
            'amount' => 'required|numeric',
        ]);

        // update
        $cash->title = $request->title;
        $cash->slug = Slug::instance(Cash::class, 'slug')->createSlug($request->title, $cash->id);
        $cash->amount = $request->amount;

        // view
        if($cash->save()) {
            $request->session()->flash('success', 'Cash information successfully updated.');
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\user\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cash $cash) {
        Cash::where('id', $cash->id)->delete();
        return redirect()->back();
    }

    /**
     *
     */
    public function cashDetails(Request $request) {
        $record = Cash::find($request->id);
        return response()->json($record, 200);
    }

    /**
     *
     */
    public function ledgerDetails($id) {
        $ledgers = Ledger::find($id);

        if($ledgers->balanceTransfers->load('ledgers')) {
            return $ledgers->balanceTransfers->load('ledgers');
        } elseif ($ledgers->expenses->load('ledgers')) {
            return $ledgers->expenses->load('ledgers');
        }
    }

    /**
     * All cashes (response)
     * @return \Illuminate\Http\JsonResponse
     */
    public function allCashes() {
        $business_id = Auth::user()->business_id;
        return response()->json(Cash::where('business_id', $business_id)->get(), 200);
    }
}
