<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\GlAccount;
use App\Models\GlAccountHead;
use App\Models\Expenditure;
use App\Models\Cash;
use App\Models\Bank;
use App\Models\BankAccount;
use Illuminate\Support\Carbon;
use Auth;

class ExpenditureController extends Controller {

    private $meta = [
        'title'   => 'Expenditure',
        'menu'    => 'expenditure',
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
        // show last 15 days result
        $date = Carbon::now()->subDays(14);
        $splite = explode(' ', $date);
        $first = $splite[0];

        $last = now()->format('Y-m-d');

        $subtotal = Expenditure::where('business_id', $business_id)->whereBetween('date', [$first, $last])->get()->groupBy('date')->map(function ($row){
            return $row->sum('amount');
        });

        // show result search by date
        if(request()->search) {
            $from = date(request()->from); // start date
            $to   = date(request()->to);   // end date

            $subtotal = Expenditure::where('business_id', $business_id)->whereBetween('date', [$from, $to])->get()->groupBy('date')->map(function ($row) {
                return $row->sum('amount');
            });
        }

        // view
        return view('user.expenditure.index', compact('subtotal'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $business_id = Auth::user()->business_id;
        $this->meta['submenu'] = 'create';

        $glAccounts = GlAccount::where('business_id', $business_id)->get();
        $cashes = Cash::where('business_id', $business_id)->get();
        $banks = Bank::where('business_id', $business_id)->get();

        return view('user.expenditure.create', compact('glAccounts', 'cashes', 'banks'))->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response

     */
    public function store(Request $request) {
        // return $request->all();

        // insert expense
        $expenditure = new Expenditure;
        $expenditure->gl_account_id         = $request->glAccount;
        $expenditure->gl_account_head_id    = $request->glAccountHead;
        $expenditure->date                  = $request->date;
        $expenditure->amount                = $request->amount;
        $expenditure->cash_id               = $request->cash['id'];
        $expenditure->bank_id               = $request->bank['id'];
        $expenditure->bank_account_id       = $request->bank['account'];
        $expenditure->note                  = $request->description;
        $expenditure->user_id               = Auth::id(); // Auth::id()
        $expenditure->business_id           = Auth::user()->business_id; // Auth::id()

        // save expense
        if ($expenditure->save()) {
            // update balance
            if($request->where == 'cash') { // for cash
                Cash::where('id', $request->cash['id'])->decrement('amount', $request->amount);

            } else { // for bank account
                $where = [
                    ['id', '=', $request->bank['account']],
                    ['bank_id', '=', $request->bank['id']],
                ];

                BankAccount::where($where)->decrement('balance', $request->amount);
            }

            // set flash data
            session()->flash('success', 'Expenditure added successfully.');
        }

        // return json response
        return $expenditure;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($date) {
        $business_id = Auth::user()->business_id;
        $expenditures = Expenditure::where('date', $date)->get();
        return view('user.expenditure.show', compact('expenditures', 'date'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $business_id = Auth::user()->business_id;
        $glAccounts = GlAccount::where('business_id', $business_id)->get();
        $expenditure = Expenditure::where('business_id', $business_id)->find($id);

        return view('user.expenditure.edit', compact('expenditure', 'glAccounts'))->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $expenditure = Expenditure::find($id);
        $expenditure->date = $request->date;
        $expenditure->gl_account_head_id = $request->gl_account_head_id;
        $expenditure->amount = $request->amount;
        $expenditure->note = $request->description;

        $previousAmount = $expenditure->getOriginal('amount');

        // update amount
        if ($request->amount > $previousAmount) {
            $amount = $request->amount - $previousAmount;

            if($expenditure->cash_id != null) { // for cash
                Cash::where('id', $expenditure->cash_id)->decrement('amount', $amount);
            } else { // for bank
                BankAccount::where('id', $expenditure->bank_account_id)->decrement('balance', $amount);
            }
        } elseif($request->amount < $previousAmount) {
            $amount = $previousAmount - $request->amount;

            if($expenditure->cash_id != null) { // for cash
                Cash::where('id', $expenditure->cash_id)->increment('amount', $amount);
            } else { // for bank
                BankAccount::where('id', $expenditure->bank_account_id)->increment('balance', $amount);
            }
        }

        // save
        if ($expenditure->save()) {
            session()->flash('success', 'Expenditure update successfully.');
        }

        // view
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $expenditure = Expenditure::find($id);
        $amount = $expenditure->getOriginal('amount');

        // deleta and update
        if ($expenditure->delete()) {
            if($expenditure->cash_id != null) { // update cash
                Cash::where('id', $expenditure->cash_id)->increment('amount', $amount);
            } else { // update bank
                BankAccount::where('id', $expenditure->bank_account_id)->increment('balance', $amount);
            }

            // set flash data
            session()->flash('success', 'Expenditure delete successfully.');
        }

        // view
        return back();
    }
}
