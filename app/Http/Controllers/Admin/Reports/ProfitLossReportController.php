<?php

namespace App\Http\Controllers\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\DueManage;
use App\Models\Purchase;
use App\Models\Expenditure;
use App\Models\DamageStock;
use App\Models\PurchaseReturn;

class ProfitLossReportController extends Controller {
    private $meta = [
        'title'   => 'Profit Loss',
        'menu'    => 'report',
        'submenu' => '',

    ];

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $businesses = Business::all();
        $total_sale                = $this->totalSaleAmount();
        $total_sale_return         = $this->totalSaleReturnAmount();
        $total_purchase            = $this->totalPurchaseAmount();
        $total_purchase_return     = $this->totalPurchaseReturnAmount();
        $total_due_paid            = $this->totalDuePaidAmount();
        $total_due_receive         = $this->totalDueReceiveAmount();
        $total_damage_amount       = $this->totalDamageProductAmount();
        $total_expense_amount      = $this->totalExpenseAmount();

        $total_income  = ($total_sale) + ($total_due_receive) + ($total_purchase_return);
        $total_expense = ($total_purchase) + ($total_sale_return) + ($total_due_paid) + ($total_damage_amount) + ($total_expense_amount);

        // return $total_income;

        
        return view('admin.reports.profit_loss.index', compact('total_income', 'businesses', 'total_expense'))->with($this->meta);
    }

    /**
     * total sale price without due
    **/
    public function totalSaleAmount(){
        // sum all tendered amount for selected business
        $tendered = Sale::where('business_id', request()->business_id)->sum('tendered');

        // sum all change amount for selected business
        $change = Sale::where('business_id', request()->business_id)->sum('change');

        $total_sale = ($tendered - $change);
        return $total_sale;
    }

    /**
     * total sale sale return price
    **/
    public function totalSaleReturnAmount(){
        // sum all tendered amount for selected business
        $sale_return = SaleReturn::where('business_id', request()->business_id)->get();

        $total_return = 0;
        foreach ($sale_return as $return) {
            $total_return += $return->return_product_price_total;
        }

        return $total_return;
    }

    /**
     * total purchase price
    **/
    public function totalPurchaseAmount(){
        return Purchase::where('business_id', request()->business_id)->sum('paid');
    }

    /**
     * total purchase return price
    **/
    public function totalPurchaseReturnAmount(){
        $purchase_return = PurchaseReturn::where('business_id', request()->business_id)->get();

        $total_return = 0;
        foreach ($purchase_return as $return) {
            $total_return += $return->purchase_return_total;
        }

        return $total_return;
    }

    /**
     *  total due receive ammount
    **/
    public function totalDueReceiveAmount(){
        // sum all due receive amount for selected business
        $total_due_receive = DueManage::where('business_id', request()->business_id)
                            ->where('payment_type', '=', 'received')
                            ->sum('amount');
        return $total_due_receive;
    }

    /**
     *  total due paid ammount
    **/
    public function totalDuePaidAmount(){
        // sum all due receive amount for selected business
        $total_due_paid = DueManage::where('business_id', request()->business_id)
                          ->where('payment_type', '=', 'paid')
                          ->sum('amount');
        return $total_due_paid;
    }

    /**
     * Total damage product price
    **/
    public function totalDamageProductAmount(){
        // sum of all damage product price
        $damages = DamageStock::where('business_id', request()->business_id)->get();

        $total_damage = 0;
        foreach ($damages as $damage) {
            $total_damage += ($damage->quantity) * ($damage->product->purchase_price);
        }
        return $total_damage;
    }

    /**
     * sum of the total expense amount
    **/
    public function totalExpenseAmount(){
        $expenses = Expenditure::where('business_id', request()->business_id)->get();

        $total_expense = 0;
        foreach ($expenses as $expense) {
            $total_expense += $expense->amount;
        }


        
        return $total_expense;
    }
    
}
