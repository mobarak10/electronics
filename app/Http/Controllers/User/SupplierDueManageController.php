<?php

namespace App\Http\Controllers\User;

use App\Helpers\SMS;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Business;
use App\Models\Cash;
use App\Models\CustomerDueManagement;
use App\Models\Party;
use App\Models\SmsReport;
use App\Models\SupplierDueManagement;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierDueManageController extends Controller
{
    private $due_manage;
    private $meta = [
        'title' => 'Due Management',
        'menu' => 'manage-due',
        'submenu' => ''
    ];

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->meta['submenu'] = 'supplier';
        $business_id = Auth::user()->business_id;
        $manage_dues_query = SupplierDueManagement::query()
            ->orderByDesc('id')
            ->where('business_id', $business_id);

        if (\request('from_date')) {
            $manage_dues_query->whereDate('date', '>=', \request('from_date'));
        }

        if (\request('to_date')) {
            $manage_dues_query->whereDate('date', '<=', \request('to_date'));
        }

        $manage_dues = $manage_dues_query->paginate(30);

        return view('user.supplier-due-management.index', compact('manage_dues'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $business_id = Auth::user()->business_id;

        $lang = __('contents');
        // read cash, bank and supplier/customer
        $cashes = Cash::where('business_id', $business_id)->get();
        $banks = Bank::with('bankAccounts')->where('business_id', $business_id)->get();
        $suppliers = Party::where('business_id', $business_id)->get();

        return view('user.supplier-due-management.create', compact('cashes', 'lang', 'banks', 'suppliers'))->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validate([
                'party_id' => 'required',
                'date' => 'required',
                'paid_from' => 'required|string',
                'cash_id' => 'nullable|integer',
                'bank_account_id' => 'nullable|integer',
                'check_issue_date' => 'nullable|date',
                'check_number' => 'nullable',
                'description' => 'nullable',
            ]);

            $supplier = Party::findOrFail($request->party_id);

            if ($request->payment_type === 'received') {
                $data['amount'] = $request->amount;
                $supplier->decrement('balance', $request->amount);
            } else {
                $data['amount'] = (-1 * $request->amount);
                $supplier->increment('balance', $request->amount);
            }

            $data['current_balance'] = $supplier->balance;
            $data['user_id'] = Auth::id();
            $data['business_id'] = Auth::user()->business_id;

            $this->due_manage = SupplierDueManagement::create($data);

            if ($request->sms) {
                $payment_type = $request->payment_type == 'received' ? 'paid' : 'receive';
                $balance_status = $supplier->balance >= 0 ? 'payable' : ' receivable';
                $message = "You have " . $payment_type . ' ' . number_format($data['amount'], 2) . ' now your balance is ' . number_format($supplier->balance, 2) . ' ' . $balance_status;
                $message = $message . " " . config('sms.regards');
                $report = SMS::customSmsSend($this->sender_id, $this->api_key, $supplier->phone, $message); //send sms
                $data = explode('|', $report); //for decode data

                if (($data[0] ?? '00') == '1101') {
                    $sms_report = new SmsReport();
                    $sms_report->sent_to = $supplier->phone;
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
                    $bank_account->increment('balance', $request->amount);
                } else {
                    $bank_account->decrement('balance', $request->amount);
                }
            }
        });

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
        $business_id = \Illuminate\Support\Facades\Auth::user()->business_id;
        $business = Business::find($business_id);
        $due_manage = SupplierDueManagement::findOrFail($id);

        return view('user.supplier-due-management.show', compact('business', 'due_manage'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $business_id = \Illuminate\Support\Facades\Auth::user()->business_id;
        // read cash, bank and supplier/customer
        $cashes = Cash::where('business_id', $business_id)->get();
        $banks = Bank::with('bankAccounts')->where('business_id', $business_id)->get();
        $suppliers = Party::where('business_id', $business_id)->get();
        $due_manage = SupplierDueManagement::findOrFail($id);
        $due_manage['formatted_date'] = $due_manage->date->format('Y-m-d');

        return view('user.supplier-due-management.edit', compact(
                'cashes',
                'banks',
                'suppliers',
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
            $this->due_manage = SupplierDueManagement::findOrFail($id);
            $previous_due_manage = $this->due_manage;
            $data = $request->validate([
                'party_id' => 'required',
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

            $previous_supplier = Party::findOrFail($previous_due_manage->party_id);
            if ($previous_due_manage->amount > 0) {
                $previous_supplier->increment('balance', abs($previous_due_manage->amount));
            } else {
                $previous_supplier->decrement('balance', abs($previous_due_manage->amount));
            }

            $supplier = Party::findOrFail($request->party_id);
            if ($request->payment_type === 'received') {
                $data['amount'] = $request->amount;
                $supplier->decrement('balance', $request->amount);
            } else {
                $data['amount'] = (-1 * $request->amount);
                $supplier->increment('balance', $request->amount);
            }

            $data['current_balance'] = $supplier->balance;
            $data['user_id'] = \Illuminate\Support\Facades\Auth::id();
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
        $due_manage = SupplierDueManagement::findOrFail($id);

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

            $supplier = Party::findOrFail($due_manage->party_id);

            if ($due_manage->amount > 0) {
                $supplier->increment('balance', abs($due_manage->amount));
            } else {
                $supplier->decrement('balance', abs($due_manage->amount));
            }

            $due_manage->delete();
        });


        return redirect()
            ->back()
            ->withSuccess('Due record deleted successfully');
    }
}
