<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\BankAccount;
use App\Models\Bank;

use Auth;

class BankAccountController extends Controller
{

    private $meta = [
        'title' => 'Bank Account',
        'menu' => 'bank',
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
        $banks = Bank::where('business_id', $business_id)->get();
        $bank_account = BankAccount::where('business_id', $business_id)->paginate(15);
        $total_bank_balance = BankAccount::where('business_id', $business_id)->sum('balance');

        return view('user.bank.account.index', compact('banks','bank_account', 'total_bank_balance'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $business_id = Auth::user()->business_id;
        $bank_id = Bank::where('business_id', $business_id)->get();
        return view('user.bank.account.create', compact('bank_id'))->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $bank_account = new BankAccount;
         $bank_account->account_name = $request->name;
         $bank_account->bank_id = $request->bank_id;
         $bank_account->account_number = $request->account_number;
         $bank_account->balance = $request->balance;
         $bank_account->branch = $request->branch;
         $bank_account->type = $request->kind;
         $bank_account->note = $request->note;
         $bank_account->business_id = Auth::user()->business_id;

         if ($bank_account->save()) {
             $request->session()->flash("success", "Account Added Successfully.");
         }
         return redirect(route('bankAccount.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bank = Bank::find($id);
        return view('user.bank.account.show', compact('bank'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $business_id = Auth::user()->business_id;
        $bank_account = BankAccount::where('business_id', $business_id)->find($id);
        $banks        = Bank::where('business_id', $business_id)->get();

        return view('user.bank.account.edit', compact('bank_account', 'banks'))->with($this->meta);
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
        $bank_account = BankAccount::find($id);

        $bank_account->account_name = $request->name;
        $bank_account->bank_id = $request->bank_id;
        $bank_account->account_number = $request->account_number;
        $bank_account->balance = $request->balance;
        $bank_account->branch = $request->branch;
        $bank_account->type = $request->kind;
        $bank_account->note = $request->note;

        if ($bank_account->save()) {
            session()->flash('success', 'Account updated Successfully.');

        }
            return redirect(route('bankAccount.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bank_account = BankAccount::find($id);

        if ($bank_account->delete()) {
            session()->flash('success', 'Account Delete Successfully.');
            return back();
        }
    }

    /**
     *show the transection of this account
    **/
    public function accountDetails(Request $request) {
        $details = BankAccount::find($request->id);
        return response()->json($details, 200);
    }
}
