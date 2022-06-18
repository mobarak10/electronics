<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Cash;
use App\Models\IncomeRecord;
use App\Models\IncomeSector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeRecordController extends Controller
{
    private $meta = [
        'title' => 'Income Record',
        'menu' => 'income',
        'submenu' => ''
    ];

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
        $business_id = Auth::user()->business_id;
        $income_records = IncomeRecord::where('business_id', $business_id)->paginate(25);
        return view('user.income-report.income.index', compact('income_records'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $business_id = Auth::user()->business_id;
        $this->meta['submenu'] = 'create';

        $income_sectors = IncomeSector::where('business_id', $business_id)->get();
        $cashes = Cash::where('business_id', $business_id)->get();
        $banks = Bank::where('business_id', $business_id)->get();

        return view('user.income-report.income.create', compact('income_sectors', 'cashes', 'banks'))->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return response($request->all(), 200);
        $data =  $request->validate([
            'date' => 'required|date',
            'amount' => 'required',
            'income_sector_id' => 'required|integer',
            'cash_id' => 'nullable',
            'income_by' => 'nullable',
            'bank_id' => 'nullable',
            'bank_account_id' => 'nullable',
            'description' => 'nullable',
        ]);

        $data['user_id'] = Auth::user()->id;
        $data['business_id'] = Auth::user()->business_id;

        if ($request->where === 'cash'){
            $cash = Cash::findOrFail($request->cash_id);
            $cash->increment('amount', $request->amount);
        }else{
            $bank_account = BankAccount::findOrFail($request->bank_account_id);
            $bank_account->increment('balance', $request->amount);
        }

        $store = IncomeRecord::create($data);
        return response($store, 200);
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
        $business_id = Auth::user()->business_id;
        $this->meta['submenu'] = 'create';

        $income = IncomeRecord::with('cash', 'bankAccount')->findOrFail($id);
        $income['format_date'] = $income->date->format('Y-m-d');
        $income_sectors = IncomeSector::where('business_id', $business_id)->get();
        $cashes = Cash::where('business_id', $business_id)->get();
        $banks = Bank::where('business_id', $business_id)->get();

        return view('user.income-report.income.edit', compact('income', 'income_sectors', 'cashes', 'banks'))->with($this->meta);
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
        // return $request->all();
        $income = IncomeRecord::findOrFail($id);
        $data =  $request->validate([
            'date' => 'required|date',
            'amount' => 'required',
            'income_sector_id' => 'required|integer',
            'cash_id' => 'nullable',
            'income_by' => 'nullable',
            'bank_id' => 'nullable',
            'bank_account_id' => 'nullable',
            'description' => 'nullable',
        ]);

        $data['user_id'] = Auth::user()->id;
        $data['business_id'] = Auth::user()->business_id;

        if($income->cash_id) {
            Cash::findOrFail($income->cash_id)->decrement('amount', $income->amount);
        }else{
            BankAccount::findOrFail($income->bank_account_id)->decrement('balance', $income->amount);
        }

        if ($request->where === 'cash'){
            $cash = Cash::findOrFail($request->cash_id);
            $cash->increment('amount', $request->amount);
        }else{
            $bank_account = BankAccount::findOrFail($request->bank_account_id);
            $bank_account->increment('balance', $request->amount);
        }

        $income->update($data);
        return response($income, 200);
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
