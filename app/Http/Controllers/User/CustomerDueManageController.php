<?php

namespace App\Http\Controllers\User;

use App\Helpers\SMS;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Brand;
use App\Models\Business;
use App\Models\Cash;
use App\Models\Customer;
use App\Models\CustomerDueManagement;
use App\Models\SmsReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerDueManageController extends Controller
{
    private $meta = [
        'title' => 'Due Management',
        'menu' => 'manage-due',
        'submenu' => ''
    ];

    public $sender_id, $api_key;
    private $due_manage;

    public function __construct()
    {
        $this->middleware('auth');
        $this->sender_id = env('SMS_SENDER_ID');
        $this->api_key = env('SMS_API_KEY');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->meta['submenu'] = 'customer';
        $business_id = Auth::user()->business_id;

        $manage_dues_query = CustomerDueManagement::query()
            ->orderByDesc('id')
            ->where('business_id', $business_id);

        if (request('from_date')) {
            $manage_dues_query->whereDate('date', '>=', request('from_date'));
        }

        if (request('to_date')) {
            $manage_dues_query->whereDate('date', '<=', request('to_date'));
        }

        $manage_dues = $manage_dues_query->paginate(30);

        return view('user.customer-due-management.index', compact('manage_dues'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $business_id = Auth::user()->business_id;
        // read cash, bank and supplier/customer
        $cashes = Cash::where('business_id', $business_id)->get();
        $banks = Bank::with('bankAccounts')->where('business_id', $business_id)->get();
        $customers = Customer::where('business_id', $business_id)->get();
        $lang = __("contents");

        return view('user.customer-due-management.create', compact('cashes', 'lang', 'banks', 'customers'))->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        DB::transaction(function() use($request) {
        $data = $request->validate([
            'customer_id' => 'required',
            'date' => 'required',
            'paid_from' => 'required|string',
            'cash_id' => 'nullable|integer',
            'bank_account_id' => 'nullable|integer',
            'check_issue_date' => 'nullable|date',
            'check_number' => 'nullable',
            'description' => 'nullable',
        ]);

        $customer = Customer::findOrFail($request->customer_id);
        if ($request->payment_type === 'received') {
            $data['amount'] = $request->amount;
            $customer->decrement('balance', $request->amount);
        } else {
            $data['amount'] = (-1 * $request->amount);
            $customer->increment('balance', $request->amount);
        }

        $data['user_id'] = Auth::id();
        $data['current_balance'] = $customer->balance;
        $data['business_id'] = Auth::user()->business_id;

        $this->due_manage = CustomerDueManagement::create($data);
        $due_manage = $this->due_manage;

        if ($request->sms) {
            $payment_type = $request->payment_type == 'received' ? 'paid' : 'receive';
            $balance_status = $customer->balance >= 0 ? 'payable' : ' receivable';
            $message = "You have " . $payment_type . ' ' . number_format($data['amount'], 2) . ' now your balance is ' . number_format($customer->balance, 2) . ' ' . $balance_status;
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

        if ($request->paid_from === 'cash') {
            $cash = Cash::findOrFail($request->cash_id);
            if ($request->payment_type === 'received') {
                $cash->increment('amount', $request->amount);
            } else {
                $cash->decrement('amount', $request->amount);
            }
        } else {
            $bank_account = BankAccount::findOrFail($request->bank_account_id);
            if ($request->payment_type === 'received') {
                $bank_account->increment('amount', $request->amount);
            } else {
                $bank_account->decrement('amount', $request->amount);
            }
        }
//        });

        return response($this->due_manage);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $business_id = Auth::user()->business_id;
        $business = Business::find($business_id);
        $due_manage = CustomerDueManagement::findOrFail($id);

        return view('user.customer-due-management.show', compact('business', 'due_manage'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $business_id = Auth::user()->business_id;
        // read cash, bank and supplier/customer
        $cashes = Cash::where('business_id', $business_id)->get();
        $banks = Bank::with('bankAccounts')->where('business_id', $business_id)->get();
        $customers = Customer::where('business_id', $business_id)->get();
        $due_manage = CustomerDueManagement::findOrFail($id);
        $due_manage['formatted_date'] = $due_manage->date->format('Y-m-d');

        return view('user.customer-due-management.edit', compact(
                'cashes',
                'banks',
                'customers',
                'due_manage')
        )->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $this->due_manage = CustomerDueManagement::findOrFail($id);
            $previous_due_manage = $this->due_manage;
            $data = $request->validate([
                'customer_id' => 'required',
                'date' => 'required',
                'paid_from' => 'required|string',
                'cash_id' => 'nullable|integer',
                'bank_account_id' => 'nullable|integer',
                'check_issue_date' => 'nullable|date',
                'check_number' => 'nullable',
                'description' => 'nullable',
            ]);

            if ($previous_due_manage->paid_from == 'cash') {
                $cash = Cash::findOrFail($previous_due_manage->cash_id);
                if ($previous_due_manage->amount > 0) {
                    $cash->decrement('amount', abs($previous_due_manage->amount));
                } else {
                    $cash->increment('amount', abs($previous_due_manage->amount));
                }
            } else {
                $bank_account = BankAccount::findOrFail($previous_due_manage->bank_account_id);
                if ($previous_due_manage->amount > 0) {
                    $bank_account->decrement('balance', abs($previous_due_manage->amount));
                } else {
                    $bank_account->increment('balance', abs($previous_due_manage->amount));
                }
            }

            $previous_customer = Customer::findOrFail($previous_due_manage->customer_id);
            if ($previous_due_manage->amount > 0) {
                $previous_customer->increment('balance', abs($previous_due_manage->amount));
            } else {
                $previous_customer->decrement('balance', abs($previous_due_manage->amount));
            }

            $customer = Customer::findOrFail($request->customer_id);
            if ($request->payment_type === 'received') {
                $data['amount'] = $request->amount;
                $customer->decrement('balance', $request->amount);
            } else {
                $data['amount'] = (-1 * $request->amount);
                $customer->increment('balance', $request->amount);
            }

            $data['current_balance'] = $customer->balance;
            $data['user_id'] = Auth::id();
            $data['business_id'] = Auth::user()->business_id;

            $previous_due_manage->update($data);

            if ($request->paid_from === 'cash') {
                $cash = Cash::findOrFail($request->cash_id);
                if ($request->payment_type === 'received') {
                    $cash->increment('amount', $request->amount);
                } else {
                    $cash->decrement('amount', $request->amount);
                }
            } else {
                $bank_account = BankAccount::findOrFail($request->bank_account_id);
                if ($request->payment_type === 'received') {
                    $bank_account->increment('balance', $request->amount);
                } else {
                    $bank_account->decrement('balance', $request->amount);
                }
            }
        });

        return response($this->due_manage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $due_manage = CustomerDueManagement::findOrFail($id);

        DB::transaction(function () use ($due_manage) {

            if ($due_manage->paid_from == 'cash') {
                $cash = Cash::findOrFail($due_manage->cash_id);
                if ($due_manage->amount > 0) {
                    $cash->decrement('amount', abs($due_manage->amount));
                } else {
                    $cash->increment('amount', abs($due_manage->amount));
                }
            } else {
                $bank_account = BankAccount::findOrFail($due_manage->bank_account_id);
                if ($due_manage->amount > 0) {
                    $bank_account->decrement('balance', abs($due_manage->amount));
                } else {
                    $bank_account->increment('balance', abs($due_manage->amount));
                }
            }
            $customer = Customer::findOrFail($due_manage->customer_id);

            if ($due_manage->amount > 0) {
                $customer->increment('balance', abs($due_manage->amount));
            } else {
                $customer->decrement('balance', abs($due_manage->amount));
            }

            $due_manage->delete();
        });

        return redirect()
            ->back()
            ->withSuccess('Due record deleted successfully');
    }
}
