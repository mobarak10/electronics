<?php

namespace App\Http\Controllers\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Cash;
use App\Models\BankAccount;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\Purchase;
use App\Models\DamageStock;
use App\Models\Stock;
use App\Models\DueManage;
use App\Models\Expenditure;
use App\Models\Business;
use App\Models\Product;

class DailyReportController extends Controller
{
    private $meta = [
        'title'   => 'Daily Report',
        'menu'    => 'report',
        'submenu' => ''
    ];

    public $data = [];

    public function __construct() {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $this->meta['submenu'] = 'daily-report';

        $date = date("Y-m-d");
        $businesses = Business::all();

        $cash = Cash::where('business_id', request()->business_id)->get();
        $account = BankAccount::where('business_id', request()->business_id)->get();
        $sales = Sale::where('business_id', request()->business_id)->whereRaw('DATE(created_at) = ?',$date)->get();
        $total_sale_return = SaleReturn::where('business_id', request()->business_id)->whereRaw('DATE(created_at) = ?',$date)->get()->sum("return_product_price_total");
        $total_purchase = Purchase::where('business_id', request()->business_id)->whereRaw('DATE(created_at) = ?',$date)->get();
        $damage_stock = DamageStock::where('business_id', request()->business_id)->get();
        $total_stock = Product::where('business_id', request()->business_id)->get();
        $due_paid = Duemanage::where('business_id', request()->business_id)->where("payment_type","paid")->where("date",$date)->get();
        $due_receive = Duemanage::where('business_id', request()->business_id)->where("payment_type","received")->where("date",$date)->get();
        $expense = Expenditure::where('business_id', request()->business_id)->where("date",$date)->get();

        return view('admin.reports.daily_report.daily_report',compact('cash','account','sales','total_sale_return','total_purchase','damage_stock','due_paid','due_receive','expense', 'total_stock', 'businesses'))->with($this->meta, $this->data);
    }

}


