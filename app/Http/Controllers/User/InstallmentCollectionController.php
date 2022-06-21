<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Cash;
use App\Models\Customer;
use App\Models\HireSale;
use App\Models\InstallmentCollection;
use App\Models\Party;
use App\InstallmentCollectionPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstallmentCollectionController extends Controller
{
    private $meta = [
        'title' => 'Installment',
        'menu' => 'installment-collection',
        'submenu' => ''
    ];
    private $installment;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->meta['submenu'] = 'all';
        $installments = InstallmentCollection::paginate(30);
        return view('user.hire-sale.installment.index', compact('installments'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->meta['submenu'] = 'new';
        $customers = Customer::all();
        $cashes = Cash::all();
        $bank_accounts = BankAccount::with('bank')->get();
        return view('user.hire-sale.installment.create', compact('customers', 'cashes', 'bank_accounts'))->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        return $request->all();
        DB::transaction(function () use($request) {
            $data = $request->validate([
                'hire_sale_id' => 'required|integer',
                'party_id' => 'required|integer',
                'payment_amount' => 'required',
                'remission' => 'nullable',
                'adjustment' => 'nullable',
                'paid_by' => 'nullable',
            ]);
            $this->installment = InstallmentCollection::create($data);
            $installment = $this->installment;

            if ($request->where === 'cash'){
                Cash::findOrFail($request->cash_id)->increment('amount', $request->payment_amount);
            }
            elseif ($request->where === 'bank'){
                BankAccount::findOrFail($request->bank_account_id)->increment('amount', $request->payment_amount);
            }

            $installment_payment = new InstallmentCollectionPayment;

            $installment_payment->installment_id = $installment->id;
            $installment_payment->payment_method = $request->where;
            $installment_payment->cash_id = $request->cash_id;
            $installment_payment->bank_account_id = $request->bank_account_id;
            $installment_payment->check_number = $request->check_number;
            $installment_payment->bkash_number = $request->bkash_number;

            $installment_payment->save();

            $installment_collection = InstallmentCollection::where('hire_sale_id', $request->hire_sale_id)->get();

            $total_paid = $installment_collection->sum('payment_amount');
            $total_remission = $installment_collection->sum('remission');
            $total_adjustment = $installment_collection->sum('adjustment');

            $total_installment_paid = ($total_paid + $total_remission + $total_adjustment);

            Party::findOrFail($request->party_id)->increment('balance', $total_installment_paid);

            $hire_sale = HireSale::findOrFail($request->hire_sale_id);

            if ($hire_sale->due <= $total_installment_paid){
                $hire_sale->installment_status = true;
                $hire_sale->save();
            }
        });

        return response($this->installment, 200);
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
