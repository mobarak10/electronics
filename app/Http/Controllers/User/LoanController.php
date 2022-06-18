<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Cash;
use App\Models\Loan;
use App\Models\LoanAccount;
use App\Rules\LoanAmountLessOrEqualToTransactionableBalanceRule;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LoanController extends Controller
{
    private $meta = [
        'title'   => 'Loan',
        'menu'    => 'loan',
        'submenu' => '',
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
        $this->meta['submenu'] = 'list';

        $cashes = Cash::query()
            ->orderBy('title')
            ->get();

        $banks = Bank::query()
            ->with('bankAccounts')
            ->get();

        $loans = Loan::query()
            ->with('transactionable')
            ->withCount('loanInstallments')
            ->addLoanAccountName()
            ->addAdjustment()
            ->addPaid()
            ->addDue()
            ->addPaymentMethod()
            ->latest('date')
            ->get();

        $loanAccounts = LoanAccount::query()
            ->orderBy('name')
            ->get();

        return view('user.loan.index', compact('cashes', 'banks', 'loanAccounts', 'loans'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cashes = Cash::query()
            ->orderBy('title')
            ->get();

        $banks = Bank::query()
            ->with('bankAccounts')
            ->get();
        $loanAccounts = LoanAccount::query()
            ->orderBy('name')
            ->get();

        return view('user.loan.create', compact('cashes', 'banks', 'loanAccounts'))->with($this->meta);
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

        $data = $request->validate(
            [
                'loan_account_id' => 'required|integer|exists:App\Models\LoanAccount,id',
                'date' => 'required|date',
                'expired_date' => 'required|date',
                'note' => 'nullable|string',
                'transactionable.id' => 'required|integer|' . ($request->payment_method == 'cash') ? 'exists:cashes,id' : 'exists:bank_accounts,id',
                'amount' => ['required', 'numeric'],
            ]
        );

        if ($request->loan_type == 'give'){
            $data['amount'] = -1 * $request->amount;
        }

        $trasactionable = null;

        // identify payment method
        if ($request->payment_method == 'cash') {
            $trasactionable = Cash::find($request['transactionable']['id']);
            $trasactionable->loans()->create($data);
            $trasactionable->increment('amount', $request->amount);
        } else {
            $trasactionable = BankAccount::find($request['transactionable']['id']);
            $trasactionable->loans()->create($data);
            $trasactionable->increment('balance', $request->amount);
        }

        return redirect()->back()->with('success', 'Loan created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cashes = Cash::query()
            ->orderBy('title')
            ->get();

        $banks = Bank::query()
            ->with('bankAccounts')
            ->get();

        $loan = Loan::query()
            ->with([
                'loanInstallments' => function (HasMany $query) {
                    $query->select('*')
                        ->addPaymentMethod()
                        ->latest('date');
                },
                'loanInstallments.transactionable',
                'loanAccount',
            ])
            ->addLoanAccountName()
            ->addAdjustment()
            ->addPaid()
            ->addDue()
            ->addPaymentMethod()
            ->findOrFail($id);

        // return $loan;
        return view('user.loan.show', compact('loan', 'cashes', 'banks'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loan = Loan::withCount('loanInstallments')->findOrFail($id);
        $cashes = Cash::query()
            ->orderBy('title')
            ->get();

        $banks = Bank::query()
            ->with('bankAccounts')
            ->get();
        $loanAccounts = LoanAccount::query()
            ->orderBy('name')
            ->get();

        return view('user.loan.edit', compact('cashes', 'banks', 'loan', 'loanAccounts'))->with($this->meta);
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
        $data = $request->validate(
            [
                'loan_account_id' => 'required|integer|exists:App\Models\LoanAccount,id',
                'date' => 'required|date',
                'expired_date' => 'required|date',
                'note' => 'nullable|string',
                'transactionable.id' => 'required|integer|' . ($request->payment_method == 'cash') ? 'exists:cashes,id' : 'exists:bank_accounts,id',
                'amount' => ['required', 'numeric'],
            ]
        );

        if ($request->loan_type == 'give'){
            $data['amount'] = -1 * $request->amount;
        }

        $loan = Loan::query()
            ->select('*')
            ->with('transactionable')
            ->addPaymentMethod()
            ->find($id);


        // check payment method is updated or not
        if ($loan->payment_method == $request->payment_method) {
            // payment method is same
            // get the different
            $diff = $data['amount'] - $loan->amount;

            // query safer
            DB::transaction(function () use ($request, $diff, $loan, $data) {
                // adjust the balance
                if ($request->payment_method == 'cash'){
                    $loan->transactionable->decrement('amount', $diff);
                }else{
                    $loan->transactionable->decrement('balance', $diff);
                }
                // update loan
                $loan->update($data);
            });
        } else {
            // payment method is not same

            // identify payment method
            if ($request->payment_method == 'cash') {
                $trasactionable = Cash::find($request['transactionable']['id']);
            } else {
                $trasactionable = BankAccount::find($request['transactionable']['id']);
            }

            // query safer
            DB::transaction(function () use ($loan, $trasactionable, $request, $data) {
                // add previous amount in previous payment method
                if ($request->payment_method == 'cash'){
                    $loan->transactionable->decrement('amount', $loan->amount);
                }else{
                    $loan->transactionable->decrement('balance', $loan->amount);
                }

                // update payment method in loan
                $loan->update($data + [
                        'transactionable_type' => $trasactionable->getMorphClass(),
                        'transactionable_id' => $trasactionable->id,
                    ]);

                // decrement amount from current transactionable
                $trasactionable->increment('balance', $data['amount']);
            });
        }

        return redirect()
            ->back()
            ->withSuccess('Loan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loan = Loan::query()
            ->with('transactionable')
            ->find($id);

        if ($loan->loanInstallments()->count()) {
            return redirect()->back()->with('errors', 'Failed to delete loan. Loan account has some installments.');
        }

        // restore balance
        if ($loan->transactionable_type == 'App\Models\Cash') {
            $loan->transactionable->decrement('amount', $loan->amount);
        }else{
            $loan->transactionable->decrement('balance', $loan->amount);
        }

        $loan->delete();

        return redirect()->back()->with('success', 'Loan deleted successfully.');
    }
}
