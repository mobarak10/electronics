<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LoanAccount;
use Illuminate\Http\Request;

class LoanAccountController extends Controller
{
    private $meta = [
        'title'   => 'Loan Account',
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
        $this->meta['submenu'] = 'account';

        $loanAccounts = LoanAccount::query()
            ->withCount('loans')
            ->addTotalLoan()
            ->addTotalPaid()
            ->addTotalAdjustment()
            ->addTotalDue()
            ->orderBy('name')
            ->get();


        return view('user.loan-account.index', compact('loanAccounts'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'nullable|string'
        ]);

        LoanAccount::create($request->only('name', 'phone', 'address'));

        return redirect()->back()->withSuccess('Loan account created successfully.');
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
        $account = LoanAccount::findOrFail($id);

        return view('user.loan-account.edit',compact('account'))->with($this->meta);
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
        $loanAccount = LoanAccount::findOrFail($id);
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'nullable|string'
        ]);

        $loanAccount->update($request->only('name', 'phone', 'address'));

        return redirect()->route('loanAccount.index')->withSuccess('Loan account created successfully.');
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
