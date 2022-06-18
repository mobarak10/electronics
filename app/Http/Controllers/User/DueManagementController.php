<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Party;
use App\Models\Cash;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\DueManage;
use Auth;

class DueManagementController extends Controller {
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
    public function index($type){
        $this->meta['submenu'] = 'customer';
        $business_id = Auth::user()->business_id;
        $holder = $type;
        $manage_dues = DueManage::where('business_id', $business_id)->paginate(15);
        return view('user.due-management.index', compact('manage_dues', 'holder'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type) {
        $this->meta['submenu'] = $type;

        $business_id = Auth::user()->business_id;
        // read cash, bank and supplier/customer
        $cashes = Cash::where('business_id', $business_id)->get();
        $banks = Bank::where('business_id', $business_id)->get();
        $holders = Party::where('business_id', $business_id)->where('genus', $type)->get();

        return view('user.due-management.create', compact('cashes', 'banks', 'holders'))->with($this->meta)->with('holder_genus', $type);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response

     */
    public function store(Request $request) {

        // return $request;
        $due_manage = new DueManage;
        $due_manage->party_id        = $request->holder['id'];
        $due_manage->date             = $request->date;
        $due_manage->amount           = $request->amount;
        $due_manage->payment_type     = $request->type;
        $due_manage->cash_id          = $request->cash['id'];
        $due_manage->bank_id          = $request->bank['id'];
        $due_manage->bank_account_id  = $request->bank['account'];
        $due_manage->check_issue_date = $request->bank['date'];
        $due_manage->check_number     = $request->bank['check'];
        $due_manage->description      = $request->description;
        $due_manage->user_id          = Auth::id();
        $due_manage->business_id      = Auth::user()->business_id;

        // $due_manage->save();

        if ($due_manage->save()) {
            // if holder is suplier
            if ($request->holder_type == "supplier") {
                // if amount is payable
                if ($request->type == "paid") {
                    // update holder balance
                    Party::where('id', $request->holder['id'])->increment('balance', $request->amount);
                    $party = Party::find($request->holder['id']);
                    // update cash balance
                    if ($request->where == "cash") {
                        Cash::where('id', $request->cash['id'])->decrement('amount', $request->amount);
                        $cash = Cash::find($request->cash['id']);

                        $party_ledger = [
                            'date' => now()->format('Y-m-d'),
                            'description' => 'Paid amount by cash - '. $cash->title,
                            'debit' => $request->amount,
                            'balance' => $party->balance,
                        ];
                        $party->ledgers()->create($party_ledger);
                    }
                    // update bank balance
                    elseif ($request->where == "bank") {
                        $where = [
                            ['id', $request->bank['account']],
                            ['bank_id', $request->bank['id']],
                        ];
                        BankAccount::where($where)->decrement('balance', $request->amount);
                        $bank_account = BankAccount::where($where)->first();
                        $bank = Bank::find($request->bank['id']);

                        $party_ledger = [
                            'date' => now()->format('Y-m-d'),
                            'description' => 'Paid amount by bank '.$bank->name.' account number '.$bank_account->account_number.' '. $due_manage->check_number,
                            'debit' => $request->amount,
                            'balance' => $party->balance,
                        ];
                        $party->ledgers()->create($party_ledger);
                    }
                }
                elseif($request->type == "received") {
                    // update holder balance
                    Party::where('id', $request->holder['id'])->decrement('balance', $request->amount);
                    $party = Party::find($request->holder['id']);

                    // update cash balance
                    if ($request->where == "cash") {
                        Cash::where('id', $request->cash['id'])->increment('amount', $request->amount);
                        $cash = Cash::find($request->cash['id']);

                        $party_credit_ledger = [
                            'date' => now()->format('Y-m-d'),
                            'description' => 'Receive amount by cash on - '.$cash->title,
                            'credit' => $request->amount,
                            'balance' => $party->balance,
                        ];
                        $party->ledgers()->create($party_credit_ledger);
                    }
                    // update bank balance
                    elseif ($request->where == "bank") {
                        $where = [
                            ['id', $request->bank['account']],
                            ['bank_id', $request->bank['id']],
                        ];
                        BankAccount::where($where)->increment('balance', $request->amount);
                        $bank_account = BankAccount::where($where)->first();
                        $bank = Bank::find($request->bank['id']);

                        $party_credit_ledger = [
                            'date' => now()->format('Y-m-d'),
                            'description' => 'Receive amount by bank - '.$bank->name.' account number - '.$bank_account->account_number.' - '. $due_manage->check_number,
                            'credit' => $request->amount,
                            'balance' => $party->balance,
                        ];
                        $party->ledgers()->create($party_credit_ledger);
                    }
                }
            }
            // if holder id customer
            elseif($request->holder_type == "customer"){
                // if amount is payable
                if ($request->type == "paid") {
                    // update holder balance
                    Party::where('id', $request->holder['id'])->decrement('balance', $request->amount);
                    $customer = Party::find($request->holder['id']);

                    $customer_balance = $customer->balance;
                    if ($customer_balance <= 0){
                        $total_balance = abs($customer_balance);
                    }else{
                        $total_balance = -1 * $customer_balance;
                    }

                    // update cash balance
                    if ($request->where == "cash") {
                        Cash::where('id', $request->cash['id'])->decrement('amount', $request->amount);
                        $cash = Cash::find($request->cash['id']);

                        $customer_ledger = [
                            'date' => now()->format('Y-m-d'),
                            'description' => 'Paid amount by cash from '.$cash->title,
                            'debit' => $request->amount,
                            'balance' => $total_balance,
                        ];
                        $customer->ledgers()->create($customer_ledger);
                    }
                    // update bank balance
                    elseif ($request->where == "bank") {
                        $where = [
                            ['id', '=', $request->bank['account']],
                            ['bank_id', '=', $request->bank['id']],
                        ];
                        BankAccount::where($where)->decrement('balance', $request->amount);
                        $bank_account = BankAccount::where($where)->first();
                        $bank = Bank::find($request->bank['id']);

                        $customer_ledger = [
                            'date' => now()->format('Y-m-d'),
                            'description' => 'Paid amount by bank '.$bank->name.' account number '.$bank_account->account_number.' '. $due_manage->check_number,
                            'debit' => $request->amount,
                            'balance' => $total_balance,
                        ];
                        $customer->ledgers()->create($customer_ledger);
                    }
                }
                elseif($request->type == "received") {
                    // update holder balance
                    Party::where('id', $request->holder['id'])->increment('balance', $request->amount);
                    $customer = Party::find($request->holder['id']);

                    $customer_balance = $customer->balance;
                    if ($customer_balance <= 0){
                        $total_balance = abs($customer_balance);
                    }else{
                        $total_balance = -1 * $customer_balance;
                    }

                    // update cash balance
                    if ($request->where == "cash") {
                        Cash::where('id', $request->cash['id'])->increment('amount', $request->amount);
                        $cash = Cash::find($request->cash['id']);

                        $customer_ledger = [
                            'date' => now()->format('Y-m-d'),
                            'description' => 'Receive amount by cash on - '.$cash->title,
                            'credit' => $request->amount,
                            'balance' => $total_balance,
                        ];
                        $customer->ledgers()->create($customer_ledger);
                    }
                    // update bank balance
                    elseif ($request->where == "bank") {
                        $where = [
                            ['id', '=', $request->bank['account']],
                            ['bank_id', '=', $request->bank['id']],
                        ];
                        BankAccount::where($where)->increment('balance', $request->amount);
                        $bank_account = BankAccount::where($where)->first();
                        $bank = Bank::find($request->bank['id']);

                        $customer_ledger = [
                            'date' => now()->format('Y-m-d'),
                            'description' => 'Receive amount by bank - '.$bank->name.' account number - '.$bank_account->account_number.' - '. $due_manage->check_number,
                            'credit' => $request->amount,
                            'balance' => $total_balance,
                        ];
                        $customer->ledgers()->create($customer_ledger);
                    }
                }
            }
        }
        session()->flash('success', 'Due manage successfully');

    }

}

