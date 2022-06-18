<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cash;
use App\Models\Bank;
use App\Models\Ledger;
use App\Models\BankAccount;
use App\Models\BalanceTransfer;
use Auth;

class BalanceTransferController extends Controller
{

    private $meta = [
        'title' => 'Balance Transfer',
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
        $transactions = BalanceTransfer::where('business_id', $business_id)->orderBy('id', 'desc')->paginate(15);
        return view('user.bank.transfer.index', compact('transactions'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $business_id = Auth::user()->business_id;
        $cashes = Cash::where('business_id', $business_id)->get();
        $banks = Bank::where('business_id', $business_id)->get();

        return view('user.bank.transfer.create', compact('cashes', 'banks'))->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $creditLedger = null;
        $debitLedger = null;

        // validation
        $data = $request->validate([
            'transfer_date' => 'required',
            'transfer_from' => 'required',
            'transfer_from_id' => 'required',
            'transfer_to' => 'required',
            'transfer_to_id' => 'required',
            'cheque_no' => '',
            'cheque_issue_date' => '',
            'amount' => 'required',
            'note' => ''
        ]);

        $data['operator'] = Auth::id();
        $data['business_id'] = Auth::user()->business_id;
        $data['code'] = 'TNS' . now()->timestamp . rand(10, 99);
        $balanceTransfer = BalanceTransfer::create($data);

        // mass assign
        if($balanceTransfer) {
            $transferCredit = [
                'date' => $request->transfer_date,
                'description' => '',
                'debit' => 0.00,
                'credit' => $request->amount,
                'balance' => 0.00
            ];

            $transferDebit = [
                'date' => $request->transfer_date,
                'description' => '',
                'debit' => $request->amount,
                'credit' => 0.00,
                'balance' => 0.00
            ];

            // cash to cash/bank
            if($request->transfer_from == 'cash') {
                // get cash data
                $fromCash = Cash::find($request->transfer_from_id);

                // balance
                $transferCredit['balance'] = $fromCash->amount - $request->amount;

                // update cash
                $fromCash->amount -= $request->amount;
                $fromCash->save();

                if($request->transfer_to == 'cash') {
                    $toCash = Cash::find($request->transfer_to_id);

                    // credit
                    $transferCredit['description'] = 'Balance transfer from Cash:' . $fromCash->title . ' to Cash:' . $toCash->title;

                    // debit
                    $transferDebit['description'] = 'Balance receive from Cash:' . $fromCash->title . ' to Cash:' . $toCash->title;
                    $transferDebit['balance'] = $toCash->amount + $request->amount;

                    // insert ledger
                    $creditLedger = Ledger::create($transferCredit); // credit
                    $debitLedger = Ledger::create($transferDebit); // debit

                    // attach
                    $fromCash->ledgers()->attach([$creditLedger->id]);
                    $toCash->ledgers()->attach([$debitLedger->id]);
                    $balanceTransfer->ledgers()->attach([$debitLedger->id, $creditLedger->id]);

                    // update cash
                    $toCash->amount += $request->amount;
                    $toCash->save();

                } elseif ($request->transfer_to == 'bank') {
                    // $toBankAccount = BankAccount::find($request->transfer_to_id);
                    $toBankAccount = BankAccount::find($request->transfer_to_account);

                    // credit
                    $transferCredit['description'] = 'Balance transfer from Cash:' . $fromCash->title . ' to Bank:' . $toBankAccount->account_name . '(' . $toBankAccount->account_number . ')';

                    // debit
                    $transferDebit['description'] = 'Balance receive from Cash:' . $fromCash->title . ' to Bank:' . $toBankAccount->account_name . '(' . $toBankAccount->account_number . ')';
                    $transferDebit['balance'] = $toBankAccount->balance + $request->amount;

                    // insert ledger
                    $creditLedger = Ledger::create($transferCredit); // credit
                    $debitLedger = Ledger::create($transferDebit); // debit

                    // attach
                    $fromCash->ledgers()->attach([$creditLedger->id]);
                    $toBankAccount->ledgers()->attach([$debitLedger->id]);
                    $balanceTransfer->ledgers()->attach([$debitLedger->id, $creditLedger->id]);

                    // update bank account
                    $toBankAccount->balance += $request->amount;
                    $toBankAccount->save();
                }

            } elseif($request->transfer_from == 'bank') { // bank to cash/bank
                // get bank-account data
                // $fromBankAccount = BankAccount::find($request->transfer_from_id);
                $fromBankAccount = BankAccount::find($request->transfer_from_account);

                // balance
                $transferCredit['balance'] = $fromBankAccount->balance - $request->amount;

                // update bank account
                $fromBankAccount->balance -= $request->amount;
                $fromBankAccount->save();

                if($request->transfer_to == 'cash') {
                    $toCash = Cash::find($request->transfer_to_id);

                    // credit
                    $transferCredit['description'] = 'Balance transfer from Bank:' . $fromBankAccount->account_name . '(' . $fromBankAccount->account_number . ')' . ' to Cash:' . $toCash->title;

                    // debit
                    $transferDebit['description'] = 'Balance receive from Bank:' . $fromBankAccount->account_name . '(' . $fromBankAccount->account_number . ')' . ' to Cash:' . $toCash->title;
                    $transferDebit['balance'] = $toCash->amount + $request->amount;

                    // insert ledger
                    $creditLedger = Ledger::create($transferCredit); // credit
                    $debitLedger = Ledger::create($transferDebit); // debit

                    // attach
                    $fromBankAccount->ledgers()->attach([$creditLedger->id]);
                    $toCash->ledgers()->attach([$debitLedger->id]);
                    $balanceTransfer->ledgers()->attach([$debitLedger->id, $creditLedger->id]);

                    // update cash
                    $toCash->amount += $request->amount;
                    $toCash->save();

                } elseif ($request->transfer_to == 'bank') {
                    // $toBankAccount = BankAccount::find($request->transfer_to_id);
                    $toBankAccount = BankAccount::find($request->transfer_to_account);

                    // credit
                    $transferCredit['description'] = 'Balance transfer from Bank:' . $fromBankAccount->account_name . '(' . $fromBankAccount->account_number . ')' . ' to Bank:' . $toBankAccount->account_name . '(' . $toBankAccount->account_number . ')';

                    // debit
                    $transferDebit['description'] = 'Balance receive from Bank:' . $fromBankAccount->account_name . '(' . $fromBankAccount->account_number . ')' . ' to Bank:' . $toBankAccount->account_name . '(' . $toBankAccount->account_number . ')';
                    // $transferDebit['balance'] = $toCash->amount + $request->amount;
                    $transferDebit['balance'] = $toBankAccount->balance + $request->amount;

                    // insert ledger
                    $creditLedger = Ledger::create($transferCredit); // credit
                    $debitLedger = Ledger::create($transferDebit); // debit

                    // attach
                    $fromBankAccount->ledgers()->attach([$creditLedger->id]);
                    $toBankAccount->ledgers()->attach([$debitLedger->id]);
                    $balanceTransfer->ledgers()->attach([$debitLedger->id, $creditLedger->id]);

                    // update bank account
                    $toBankAccount->balance += $request->amount;
                    $toBankAccount->save();
                }
            }

            // response
            return response()->json($request->all(), 200);
        }
    }

    private function updateCash() {
        # code...
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
        //
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
}
