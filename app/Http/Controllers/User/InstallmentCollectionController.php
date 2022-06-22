<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstallmentCollectionRequest;
use App\Models\BankAccount;
use App\Models\Cash;
use App\Models\Customer;
use App\Models\HireSale;
use App\Models\InstallmentCollection;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

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
     * @return Response
     */
    public function index()
    {
        $this->meta['submenu'] = 'all';
        $installments = InstallmentCollection::orderByDesc('id')->paginate(30);
        return view('user.hire-sale.installment.index', compact('installments'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|Response|View
     */
    public function create()
    {
        $this->meta['submenu'] = 'new';
        $customers = Customer::all();
        $cashes = Cash::all();
        $lang = __('contents');
        $bank_accounts = BankAccount::with('bank')->get();
        return view('user.hire-sale.installment.create', compact(
            'customers',
            'lang',
            'cashes',
            'bank_accounts'))
            ->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(InstallmentCollectionRequest $request)
    {
//        return $request->all();
        DB::transaction(function () use($request) {
            $data = $request->validated();
            $installment = InstallmentCollection::create($data);
            $this->updateCashBankBalance($request);
            $this->storeInstallmentPaymentData($installment, $request);

            $today_total_paid  = ($request->payment_amount + $request->remission + $request->adjustment);
            Customer::findOrFail($request->customer_id)->decrement('balance', $today_total_paid);

            $this->installment = HireSale::findOrFail($request->hire_sale_id);
            $hire_sale = $this->installment;
            $this->updateHireSaleInstallmentStatus($hire_sale, $request);
        });

        return response($this->installment, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|Response|View
     */
    public function edit($id)
    {
        $this->meta['submenu'] = 'new';
        $customers = Customer::all();
        $cashes = Cash::all();
        $lang = __('contents');
        $old_installment = InstallmentCollection::with('installmentPayment')->findOrFail($id);
        $bank_accounts = BankAccount::with('bank')->get();
        return view('user.hire-sale.installment.edit', compact(
            'customers',
            'lang',
            'old_installment',
            'cashes',
            'bank_accounts'))
            ->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(InstallmentCollectionRequest $request, $id)
    {
//        return $request->all();
        DB::transaction(function () use($request, $id) {
            $old_installment = InstallmentCollection::with('installmentPayment')->findOrFail($id);
            $hire_sale = HireSale::findOrFail($old_installment->hire_sale_id);
            $hire_sale->installment_status = false;
            $hire_sale->save();
            if ($old_installment->installmentPayment->payment_method === 'cash'){
                Cash::findOrFail($old_installment->installmentPayment->cash_id)->decrement('amount', $old_installment->payment_amount);
            }else{
                BankAccount::findOrFail($old_installment->installmentPayment->cash_id)->decrement('balance', $old_installment->payment_amount);
            }
            $old_installment->installmentPayment->delete();

            $data = $request->validated();
            $installment = $old_installment->update($data);

            $this->updateCashBankBalance($request);
            $this->storeInstallmentPaymentData($old_installment, $request);

            $today_total_paid  = ($request->payment_amount + $request->remission + $request->adjustment);
            Customer::findOrFail($request->customer_id)->decrement('balance', $today_total_paid);

            $this->installment = HireSale::findOrFail($request->hire_sale_id);
            $hire_sale = $this->installment;
            $this->updateHireSaleInstallmentStatus($hire_sale, $request);
        });

        return response($this->installment, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * increment cash or bank balance for installment
     * @param $request
     * @return void
     */
    public function updateCashBankBalance($request)
    {
        if ($request->where === 'cash'){
            Cash::findOrFail($request->cash_id)->increment('amount', $request->payment_amount);
        }
        elseif ($request->where === 'bank'){
            BankAccount::findOrFail($request->bank_account_id)->increment('amount', $request->payment_amount);
        }
    }

    public function storeInstallmentPaymentData($installment, $request)
    {
        $installment_payment_data = [
            'payment_method' => $request->where,
            'cash_id' => $request->cash_id,
            'bank_account_id' => $request->bank_account_id,
            'check_number' => $request->check_number,
            'bkash_number' => $request->bkash_number,
        ];
        $installment->installmentPayment()->create($installment_payment_data);
    }

    public function updateHireSaleInstallmentStatus($hire_sale, $request)
    {
        $installment_collection = InstallmentCollection::where('hire_sale_id', $request->hire_sale_id)->get();
        $total_paid = $installment_collection->sum('payment_amount');
        $total_remission = $installment_collection->sum('remission');
        $total_adjustment = $installment_collection->sum('adjustment');
        $total_installment_paid = ($total_paid + $total_remission + $total_adjustment);
        if ($hire_sale->due <= $total_installment_paid){
            $hire_sale->installment_status = true;
            $hire_sale->save();
        }
    }
}
