<?php

namespace App\Http\Controllers\User;

use App\Helpers\SMS;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Party;
use App\Models\SmsReport;
use App\Models\Transaction;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransactionController extends Controller
{
    private $meta = [
        'title' => 'Transaction',
        'menu' => 'transaction',
        'submenu' => ''
    ];

    private $transaction;
    public $sender_id, $api_key;

    public function __construct()
    {
        $this->middleware('auth');
        $this->sender_id = env('SMS_SENDER_ID');
        $this->api_key = env('SMS_API_KEY');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $this->meta['submenu'] = 'list';
        $transactions = Transaction::paginate(30);

        return view('user.transaction.index', compact('transactions'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->meta['submenu'] = 'add';

        $suppliers = Party::all();
        $customers = Customer::all();

        return view('user.transaction.create', compact('suppliers', 'customers'))->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $data = [
                'date' => $request->date,
                'transaction_from' => $request->transaction_from,
                'customer_id' => $request->customer_id,
                'party_id' => $request->party_id,
                'amount' => $request->amount,
                'note' => $request->note,
                'user_id' => \Auth::user()->id,
                'business_id' => \Auth::user()->business_id,
            ];

            // insert data into transaction table
            $this->transaction = Transaction::create($data);
            $transaction = $this->transaction;

            $party = Party::findOrFail($request->party_id); // get supplier
            $customer = Customer::findOrFail($request->customer_id); // get customer
            // if transaction from supplier then increment supplier balance & decrement customer balance
            if ($request->transaction_from == 'supplier') {
                $party->increment('balance', $request->amount);
                $customer->decrement('balance', $request->amount);
            } // else decrement supplier balance & increment customer balance
            else {
                $party->decrement('balance', $request->amount);
                $customer->increment('balance', $request->amount);
            }

            if ($request->sms) {
                $transfer_to = $request->transaction_from == 'supplier' ? 'customer' : 'supplier';
                $message = "Balance Transfer from " .
                    $request->transaction_from .
                    ' to ' . $transfer_to .
                    " amount " .
                    number_format($request->amount, 2) .
                    ' customer new balance is ' .
                    number_format($customer->balance, 2) .
                    " supplier new balance is " .
                    number_format($party->balance, 2);

                $message = $message . " " . config('sms.regards');
                $report = SMS::customSmsSend($this->sender_id, $this->api_key, $customer->phone, $message); //send sms
                $data = explode('|', $report); //for decode data

                if (($data[0] ?? '00') == '1101') {
                    $sms_report = new SmsReport();
                    $sms_report->sent_to = $customer->phone;
                    $sms_report->message = $message;
                    $sms_report->total_character = strlen($message);
                    $sms_report->total_sms = 1;
                    $sms_report->status = "success";
                    $sms_report->save();
                }
            }
        });

        return response()->json($this->transaction, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|Response|View
     */
    public function edit($id)
    {
        $this->meta['submenu'] = 'add';

        $transaction = Transaction::findOrFail($id);
        $suppliers = Party::all();
        $customers = Customer::all();

        return view('user.transaction.edit', compact('suppliers', 'customers', 'transaction'))
            ->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     * @throws \Throwable
     */
    public function update(Request $request, $id)
    {
        // balance old transaction

        $old_transaction = Transaction::findOrFail($id);

        DB::transaction(function () use ($old_transaction, $request) {

            $old_party = Party::findOrFail($old_transaction->party_id);
            $old_customer = Customer::findOrFail($old_transaction->customer_id);

            // if transaction from supplier then decrement supplier balance & increment customer balance
            if ($old_transaction->transaction_from == 'supplier') {
                $old_party->decrement('balance', $old_transaction->amount);
                $old_customer->increment('balance', $old_transaction->amount);
            } // else increment supplier balance & decrement customer balance
            else {
                $old_party->increment('balance', $old_transaction->amount);
                $old_customer->decrement('balance', $old_transaction->amount);
            }

            // Insert new transaction

            $data = [
                'date' => $request->date,
                'transaction_from' => $request->transaction_from,
                'customer_id' => $request->customer_id,
                'party_id' => $request->party_id,
                'amount' => $request->amount,
                'note' => $request->note,
                'user_id' => \Auth::user()->id,
                'business_id' => \Auth::user()->business_id,
            ];

            // insert data into transaction table
            $this->transaction = tap($old_transaction)->update($data);

            $party = Party::findOrFail($request->party_id); // get supplier
            $customer = Customer::findOrFail($request->customer_id); // get customer

            // if transaction from supplier then increment supplier balance & decrement customer balance
            if ($request->transaction_from == 'supplier') {
                $party->increment('balance', $request->amount);
                $customer->decrement('balance', $request->amount);
            } // else decrement supplier balance & increment customer balance
            else {
                $party->decrement('balance', $request->amount);
                $customer->increment('balance', $request->amount);
            }

            if ($request->sms) {
                $transfer_to = $request->transaction_from == 'supplier' ? 'customer' : 'supplier';
                $message = "Balance Transfer from " .
                    $request->transaction_from .
                    ' to ' . $transfer_to .
                    " amount " .
                    number_format($request->amount, 2) .
                    ' customer new balance is ' .
                    number_format($customer->balance, 2) .
                    " supplier new balance is " .
                    number_format($party->balance, 2);

                $message = $message . " " . config('sms.regards');
                $report = SMS::customSmsSend($this->sender_id, $this->api_key, $customer->phone, $message); //send sms
                $data = explode('|', $report); //for decode data

                if (($data[0] ?? '00') == '1101') {
                    $sms_report = new SmsReport();
                    $sms_report->sent_to = $customer->phone;
                    $sms_report->message = $message;
                    $sms_report->total_character = strlen($message);
                    $sms_report->total_sms = 1;
                    $sms_report->status = "success";
                    $sms_report->save();
                }
            }
        });

        return response()->json($this->transaction, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $old_transaction = Transaction::findOrFail($id);

        DB::transaction(function () use ($old_transaction) {

            // balance old transaction
            $old_party = Party::findOrFail($old_transaction->party_id);
            $old_customer = Customer::findOrFail($old_transaction->customer_id);

            // if transaction from supplier then decrement supplier balance & increment customer balance
            if ($old_transaction->transaction_from == 'supplier') {
                $old_party->decrement('balance', $old_transaction->amount);
                $old_customer->increment('balance', $old_transaction->amount);
            } // else increment supplier balance & decrement customer balance
            else {
                $old_party->increment('balance', $old_transaction->amount);
                $old_customer->decrement('balance', $old_transaction->amount);
            }

            $old_transaction->delete();
        });

        return redirect()
            ->back()
            ->withSuccess('Transaction deleted successfully');
    }
}
