<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Cash;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanInstallmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        $data = $request->validate([
            'loan_id' => 'required|integer|exists:loans,id',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'adjustment' => 'nullable|numeric',
            'note' => 'nullable|string',
        ]);
        // TODO validation for max allowed amount

        $loan = Loan::findOrFail($request->loan_id);

        if ($loan->amount > 0){
            $data['amount'] = -1 * $request->amount;
        }

        $trasactionable = null;

        // identify payment method
        if ($request->payment_method == 'cash') {
            $trasactionable = Cash::find($request['transactionable']['id']);
        } else {
            $trasactionable = BankAccount::find($request['transactionable']['id']);
        }

        DB::transaction(function () use ($data, $request, $trasactionable) {
            $trasactionable->loanInstallments()->create($data);
            if ($request->payment_method == 'cash') {
                $trasactionable->increment('amount', $data['amount']);
            }else{
                $trasactionable->increment('balance', $data['amount']);
            }
        });

        return redirect()->back()->with('success', 'Loan installment added successfully.');
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
