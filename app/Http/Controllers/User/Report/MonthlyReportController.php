<?php

namespace App\Http\Controllers\User\Report;

use App\Http\Controllers\Controller;
use App\Models\Expenditure;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;

class MonthlyReportController extends Controller
{
    private $meta = [
        'title'   => 'Monthly Report',
        'menu'    => 'report',
        'submenu' => ''
    ];

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(){
        $purchases = '';
        $sales = '';
        $total_expense = '';
        $total_purchases = '';
        $expenses = '';
        $total_sales = '';

        if(request()->search) {

            $total_expense = Expenditure::whereYear('created_at', request()->year)->get();
            $total_purchases = Purchase::whereYear('created_at', request()->year)->get();
            $total_sales = Sale::whereYear('created_at', request()->year)->get();

            $purchases = Purchase::whereYear('created_at', request()->year)
                ->get()
                ->map(function($purchase) {
                    $purchase['month'] = $purchase->created_at->format('F');
                    return $purchase;
                })->groupBy('month')
                ->map(function ($month) {
                    return $month->sum('grand_total');
                });

            $sales = Sale::whereYear('created_at', request()->year)
                ->get()
                ->map(function($sale) {
                    $sale['month'] = $sale->created_at->format('F');
                    return $sale;
                })->groupBy('month')
                ->map(function ($month) {
                    return $month->sum('grand_total');
                });

            $expenses = Expenditure::whereYear('created_at', request()->year)
                ->get()
                ->map(function($expense) {
                    $expense['month'] = $expense->created_at->format('F');
                    return $expense;
                })->groupBy('month')
                ->map(function ($month) {
                    return $month->sum('amount');
                });

        }

        return view('user.reports.monthly_report.index', compact('purchases', 'sales', 'total_purchases', 'total_sales', 'expenses', 'total_expense'))->with($this->meta);
    }
}
