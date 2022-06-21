<?php

namespace App\Http\Controllers\User\Report;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerDueManagement;
use App\Models\HireSale;
use App\Models\InstallmentCollection;
use App\Models\Party;
use App\Models\PreOrder;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\SupplierDueManagement;
use App\Models\Transaction;
use Illuminate\Http\Request;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;
use App\Models\Sale;
use App\Models\SaleReturn;

class LedgerReportController extends Controller
{
    private $meta = [
        'title' => 'Ledger Report',
        'menu' => 'ledger-report',
        'submenu' => 'ledger-report'
    ];

    public $data = [];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function supplierLedger()
    {
        $this->data['parties'] = Party::all();
        $party = '';
        $total_debit = 0;
        $total_credit = 0;
        $party_balance = 0;

        if (request()->search) {
            $party = Party::where('id', request()->party_id)->first();
            $party_balance = $party->balance;

            $purchase_query = Purchase::query()
                ->where('party_id', request()->party_id)
                ->selectRaw("*, 'purchase' as 'type'");

            $purchase_return_query = PurchaseReturn::query()
                ->where('party_id', request()->party_id)
                ->withOut('purchaseReturnProducts')
                ->selectRaw("*, 'purchase_return' as 'type'");

            $supplier_due_management_query = SupplierDueManagement::query()
                ->where('party_id', request()->party_id)
                ->selectRaw("*, 'due_manage' as 'type'");


            if (request('from_date')) {
                $purchase_query->whereDate('date', '>=', request('from_date'));
                $purchase_return_query->whereDate('created_at', '>=', request('from_date'));
                $supplier_due_management_query->whereDate('date', '>=', request('from_date'));
            }

            if (request('to_date')) {
                $purchase_query->whereDate('date', '<=', request('to_date'));
                $purchase_return_query->whereDate('created_at', '<=', request('to_date'));
                $supplier_due_management_query->whereDate('date', '<=', request('to_date'));
            }

            $this->data['party_ledgers'] =
                Search::add($purchase_query)
                    ->add($purchase_return_query)
                    ->add($supplier_due_management_query)
                    ->get();

            foreach ($this->data['party_ledgers'] as $ledger) {
                $total_debit += ($ledger->grand_total);
                $total_credit += ($ledger->paid + $ledger->purchase_return_total);

                if ($ledger->type == 'due_manage') {
                    if ($ledger->amount >= 0) {
                        $total_credit += $ledger->amount;
                    } else {
                        $total_debit += $ledger->amount;
                    }
                }
            }
//            return $total_debit;
        }

        return view('user.reports.ledger.supplierLedger', compact('party', 'total_debit', 'party_balance', 'total_credit'))->with($this->meta)->with($this->data);
    }

    public function customerLedger()
    {
        $this->data['parties'] = Customer::all();
        $party = '';
        $total_debit = 0;
        $total_credit = 0;
        $party_balance = 0;

        if (request()->search) {
            $party = Customer::where('id', request()->party_id)->first();
            $party_balance = $party->balance;

            $sale_query = Sale::query()
                ->with('saleDetails')
                ->where('customer_id', request()->party_id)
                ->selectRaw("*, 'sale' as 'type'");

            $sale_return_query = SaleReturn::query()
                ->with('returnProducts')
                ->where('customer_id', request()->party_id)
                ->selectRaw("*, 'sale_return' as 'type'");

            $hire_sale = HireSale::query()
                ->where('customer_id', request()->party_id)
                ->selectRaw("*, 'hire_sale' as 'type'");

            $customer_due_management_query = CustomerDueManagement::query()
                ->where('customer_id', request()->party_id)
                ->selectRaw("*, 'due_manage' as 'type'");

            if (\request('from_date')) {
                $sale_query->whereDate('date', '>=', \request('from_date'));
                $hire_sale->whereDate('date', '>=', \request('from_date'));
                $sale_return_query->whereDate('date', '>=', \request('from_date'));
                $customer_due_management_query->whereDate('date', '>=', \request('from_date'));
            }

            if (\request('to_date')) {
                $sale_query->whereDate('date', '<=', \request('to_date'));
                $hire_sale->whereDate('date', '<=', \request('to_date'));
                $sale_return_query->whereDate('date', '<=', \request('to_date'));
                $customer_due_management_query->whereDate('date', '<=', \request('to_date'));
            }


            $this->data['party_ledgers'] =
                Search::add($sale_query)
                    ->orderBy('date')
                    ->add($sale_return_query)
                    ->orderBy('date')
                    ->add($hire_sale)
                    ->orderBy('date')
                    ->add($customer_due_management_query)
                    ->orderBy('date')
                    ->get();

            foreach ($this->data['party_ledgers'] as $ledger) {
                $total_debit += ($ledger->grand_total + $ledger->return_paid + $ledger->hire_sale_grand_total);
                $total_credit += ($ledger->paid + $ledger->return_grand_total + $ledger->total_pay + $ledger->down_payment);
                if ($ledger->type == 'due_manage') {
                    if ($ledger->amount <= 0) {
                        $total_debit += $ledger->amount;
                    } else {
                        $total_credit += $ledger->amount;
                    }
                }
            }

//            return $total_credit;
        }
        return view('user.reports.ledger.customerLedger', compact('party', 'total_debit', 'party_balance', 'total_credit'))->with($this->meta)->with($this->data);
    }

    /**
     * hire sale ledger
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function hireSaleLedger()
    {
        $this->data['parties'] = Customer::all();
        $party = '';
        $total_debit = 0;
        $total_credit = 0;
        $party_balance = 0;

        if (request()->search) {
            $party = Customer::where('id', request()->party_id)->first();
            $party_balance = $party->balance;

            $hire_sale = HireSale::query()
                ->where('customer_id', request()->party_id)
                ->selectRaw("*, 'hire_sale' as 'type'");

            $installment_collection = InstallmentCollection::query()
                ->where('customer_id', request()->party_id)
                ->selectRaw("*, 'installment_collection' as 'type'");

            if (\request('from_date')) {
                $hire_sale->whereDate('date', '>=', \request('from_date'));
                $installment_collection->whereDate('date', '>=', \request('from_date'));
            }

            if (\request('to_date')) {
                $hire_sale->whereDate('date', '<=', \request('to_date'));
                $installment_collection->whereDate('date', '<=', \request('to_date'));
            }


            $this->data['party_ledgers'] =
                Search::add($hire_sale)
                    ->orderBy('date')
                    ->add($installment_collection)
                    ->orderBy('date')
                    ->get();

            foreach ($this->data['party_ledgers'] as $ledger) {
                $total_debit += ($ledger->hire_sale_grand_total);
                $total_credit += ($ledger->down_payment + $ledger->payment_amount);
            }
        }

        return view('user.reports.ledger.hireSaleLedger', compact(
            'party',
            'total_debit',
            'party_balance',
            'total_credit'))
            ->with($this->meta)
            ->with($this->data);
    }
}
