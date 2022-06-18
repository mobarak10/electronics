<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
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
use App\Models\Party;
use App\Models\Product;
use DateTime;

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
        $this->middleware('auth:admin');
    }

    /**
     *
     */
    public function index() {
        $this->meta['submenu'] = 'daily-report';

        $date = date("Y-m-d");

        $cash = Cash::all();
        $account = BankAccount::all();

        //$amount = Sale::whereDate('created_at', today())->count();

        $sales = Sale::where('date',$date)->get();

        $total_sale_return = SaleReturn::whereRaw('DATE(created_at) = ?',$date)->get()->sum("return_product_price_total");
        $total_purchase = Purchase::whereRaw('DATE(created_at) = ?',$date)->get();
        $damage_stock = DamageStock::get();
        $total_stock = Product::get();
        $due = Sale::selectRaw("due")->get();
        $suppliers = Party::selectRaw('balance')->get();
        $expense = Expenditure::where("date", $date)->get();

        $getWeeklyPurchase = $this->getWeeklyPurchase();
        $getWeeklyExpense  = $this->getWeeklyExpense();
        $getDailySale      = $this->getDailySale();
        $getDailyReturn    = $this->getDailyReturn();

        return view('admin.home', compact('cash','account','sales','total_sale_return','total_purchase','damage_stock','expense', 'total_stock', 'due', 'suppliers', 'getWeeklyPurchase', 'getWeeklyExpense', 'getDailySale', 'getDailyReturn'))
            ->with($this->meta, $this->data);
        // return view('admin.home')->with($this->meta);
    }

    // get lest 7 days purchase details
    public function getWeeklyPurchase() {
        $output = [];

        // select date
        $fromDate = Carbon::yesterday();
        $toDate = Carbon::today()->subDays(7);

        // get from DB
        $data = Purchase::whereBetween('date', [
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

        // select date
        $fromDate = Carbon::yesterday();
        $toDate = Carbon::today()->subDays(7);

        // get from DB
        $data = Expenditure::whereBetween('date', [
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

        // select date
        $fromDate = Carbon::yesterday();
        $toDate = Carbon::today()->subDays(30);

        // get from DB
        $data = Sale::whereBetween('date', [
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

        // select date
        $fromDate = Carbon::yesterday();
        $toDate = Carbon::today()->subDays(30);

        // get from DB
        $data = SaleReturn::whereBetween('date', [
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

}
