<?php

namespace App\Http\Controllers\User;

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
use App\Models\PurchaseReturn;
use Auth;

class CashBookController extends Controller {
    private $meta = [
        'title'   => 'Cash Book',
        'menu'    => 'report',
        'submenu' => ''
    ];

    public $data = [];

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
    	$this->meta['submenu'] = 'daily-report';

        if (request()->search) {

        	$date = request()->date;
	        $business_id = Auth::user()->business_id;

	        $sales = Sale::where('business_id', $business_id)->selectRaw("*, (subtotal + ((subtotal * vat)/100)) - CASE WHEN discount_type='flat' THEN discount ELSE (subtotal * discount)/100 END as grand_total")->whereDate('created_at',$date)->get();

	        $total_sale_return = SaleReturn::where('business_id', $business_id)->whereDate('created_at',$date)->get()->sum("return_product_price_total");

	        $total_purchase = Purchase::where('business_id', $business_id)->whereDate('created_at',$date)->get();

	        $total_purchase_return = PurchaseReturn::where('business_id', $business_id)->whereDate('created_at',$date)->get()->sum("purchase_return_total");

	        $damage_stock = DamageStock::where('business_id', $business_id)->whereDate('created_at',$date)->get();

	        // $total_stock = Product::where('business_id', $business_id)->get();

	        $due_paid = Duemanage::where('business_id', $business_id)->where("payment_type","paid")->where("date",$date)->get();

	        $due_receive = Duemanage::where('business_id', $business_id)->where("payment_type","received")->where("date",$date)->get();

	        $expense = Expenditure::where('business_id', $business_id)->where("date",$date)->get();
        }
        else{
        	$date = date("Y-m-d");
	        $business_id = Auth::user()->business_id;

	       $sales = Sale::where('business_id', $business_id)->selectRaw("*, (subtotal + ((subtotal * vat)/100)) - CASE WHEN discount_type='flat' THEN discount ELSE (subtotal * discount)/100 END as grand_total")->whereDate('created_at',$date)->get();

	        $total_sale_return = SaleReturn::where('business_id', $business_id)->whereDate('created_at',$date)->get()->sum("return_product_price_total");

	        $total_purchase = Purchase::where('business_id', $business_id)->whereDate('created_at',$date)->get();

	        $total_purchase_return = PurchaseReturn::where('business_id', $business_id)->whereDate('created_at',$date)->get()->sum("purchase_return_total");

	        $damage_stock = DamageStock::where('business_id', $business_id)->whereDate('created_at',$date)->get();

	        // $total_stock = Product::where('business_id', $business_id)->get();

	        $due_paid = Duemanage::where('business_id', $business_id)->where("payment_type","paid")->where("date",$date)->get();

	        $due_receive = Duemanage::where('business_id', $business_id)->where("payment_type","received")->where("date",$date)->get();

	        $expense = Expenditure::where('business_id', $business_id)->where("date",$date)->get();
        }
        return view('user.cash-book.index',compact('cash','account','sales','total_sale_return','total_purchase','damage_stock','due_paid','due_receive','expense', 'total_stock', 'total_purchase_return'))->with($this->meta, $this->data);
    }
}
