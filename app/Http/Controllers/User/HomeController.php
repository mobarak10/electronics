<?php
namespace App\Http\Controllers\User;

use App\Models\HireSaleInstallment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Cash;
use App\Models\BankAccount;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\Purchase;
use App\Models\DamageStock;
use App\Models\DueManage;
use App\Models\Expenditure;
use App\Models\LoanAccount;
use App\Models\Party;
use DateTime;
use Auth;

class HomeController extends Controller
{
    private $meta = [
        'title' => 'Dashboard',
        'menu' => 'dashboard',
        'submenu' => ''
    ];

    public $data = [];

    /**
     *
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     *
     */
    public function index() {
        $business_id = Auth::user()->business_id;
        $this->meta['submenu'] = 'daily-report';

        $date = date("Y-m-d");

        $this->data['cash'] = Cash::where('business_id', $business_id)->get();
        $this->data['account'] = BankAccount::where('business_id', $business_id)->get();

        $this->data['installments'] = HireSaleInstallment::with(['hireSale' => function($query) {
            $query->where('installment_status', 0);
        }])->where('installment_date', $date)
            ->whereHas('hireSale', function($query) {
                $query->where('installment_status', 0);
            })
            ->get();

//        $this->data['sales'] = Sale::where('business_id', $business_id)->selectRaw("*, (subtotal + ((subtotal * vat)/100)) - CASE WHEN discount_type='flat' THEN discount ELSE (subtotal * discount)/100 END as grand_total")->whereRaw('DATE(created_at) = ?',$date)->get();
        $this->data['sales'] = Sale::where('business_id', $business_id)->where('date',$date)->get();

        $this->data['loan_accounts'] = LoanAccount::query()
            ->withCount('loans')
            ->addTotalLoan()
            ->addTotalPaid()
            ->addTotalAdjustment()
            ->addTotalDue()
            ->orderBy('name')
            ->get();

        $this->data['total_sale_return'] = SaleReturn::whereRaw('DATE(created_at) = ?',$date)->get()->sum("return_product_price_total");
        $this->data['total_purchase'] = Purchase::where('business_id', $business_id)->whereRaw('DATE(created_at) = ?',$date)->get();
        $this->data['damage_stock'] = DamageStock::where('business_id', $business_id)->get();
        $this->data['total_stock'] = Product::where('business_id', $business_id)->get();
        $this->data['customer_balance'] = Customer::all();
        $this->data['suppliers'] = Party::where('business_id', $business_id)->selectRaw('balance')->get();
        // $this->data['due_paid'] = Duemanage::where('business_id', $business_id)->where("payment_type","paid")->where("date",$date)->get();
        // $this->data['due_receive'] = Duemanage::where('business_id', $business_id)->where("payment_type","received")->where("date",$date)->get();
        $this->data['expense'] = Expenditure::where('business_id', $business_id)->where("date", $date)->get();

        $this->data['getWeeklyPurchase'] = $this->getWeeklyPurchase();
        $this->data['getWeeklyExpense']  = $this->getWeeklyExpense();
        $this->data['getDailySale']      = $this->getDailySale();
        $this->data['getDailyReturn']    = $this->getDailyReturn();

        return view('user.home')->with($this->meta)->with($this->data);
    }

    // get lest 7 days purchase details
    public function getWeeklyPurchase() {
        $output = [];

        $business_id = Auth::user()->business_id;
        // select date
        $fromDate = Carbon::yesterday();
        $toDate = Carbon::today()->subDays(7);

        // get from DB
        $data = Purchase::where('business_id', $business_id)->whereBetween('date', [
            $toDate->format('Y-m-d') . " 00:00:00",
            $fromDate->format('Y-m-d') . " 23:59:59"
        ])
            ->get()
            ->groupBy(function($row) {
                return Carbon::parse($row->created_at)->format('D');
            })
            ->map(function ($row) {
                return $row->sum('grand_total');
            });

        $output['data'] = array_values($data->toArray());
        $output['labels'] = array_keys($data->toArray());

        return $output;
    }

    public function getWeeklyExpense(){
        $output = [];

        $business_id = Auth::user()->business_id;
        // select date
        $fromDate = Carbon::yesterday();
        $toDate = Carbon::today()->subDays(7);

        // get from DB
        $data = Expenditure::where('business_id', $business_id)->whereBetween('date', [
            $toDate->format('Y-m-d') . " 00:00:00",
            $fromDate->format('Y-m-d') . " 23:59:59"
        ])->get()
            ->groupBy(function($row) {
                return Carbon::parse($row->created_at)->format('D');
            })
            ->map(function ($row) {
                return $row->sum('amount');
            });

        $output['data'] = array_values($data->toArray());
        $output['labels'] = array_keys($data->toArray());

        return $output;
    }

    public function getDailySale(){
        $output = [];

        $business_id = Auth::user()->business_id;
        // select date
        $fromDate = Carbon::yesterday();
        $toDate = Carbon::today()->subDays(30);

        // get from DB
        $data = Sale::where('business_id', $business_id)->whereBetween('date', [
            $toDate->format('Y-m-d') . " 00:00:00",
            $fromDate->format('Y-m-d') . " 23:59:59"
        ])->get()
            ->groupBy(function($row) {
                return Carbon::parse($row->created_at)->format('d');
            })
            ->map(function ($row) {
                return $row->sum('grand_total');
            });

        $output['data'] = array_values($data->toArray());
        $output['labels'] = array_keys($data->toArray());

        return $output;
    }

    public function getDailyReturn(){
        $output = [];

        $business_id = Auth::user()->business_id;
        // select date
        $fromDate = Carbon::yesterday();
        $toDate = Carbon::today()->subDays(30);

        // get from DB
        $data = SaleReturn::where('business_id', $business_id)->whereBetween('date', [
            $toDate->format('Y-m-d') . " 00:00:00",
            $fromDate->format('Y-m-d') . " 23:59:59"
        ])->get()
            ->groupBy(function($row) {
                return Carbon::parse($row->created_at)->format('d');
            })
            ->map(function ($row) {
                return $row->sum('return_product_price_total');
            });

        $output['data'] = array_values($data->toArray());
        $output['labels'] = array_keys($data->toArray());

        return $output;
    }

    public function generateToken() {
        return view('user.generate-token')->with($this->meta);
    }

}
