<?php

namespace App\Http\Controllers\User\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleDetails;
use Auth;

class SalesReportController extends Controller
{
    private $meta = [
        'title'   => 'Sales Report',
        'menu'    => 'reports',
        'submenu' => 'sales-report'
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $records = Sale::query();
        $format = 'm-Y';

        $records = $records->where('business_id', Auth::user()->business_id)
            ->latest()
            ->get()
            ->groupBy(function ($item) use ($format) {
                return $item->created_at->format($format);
            });

        return view('user.reports.sales.monthly', compact('records'))
            ->with($this->meta);
    }

    public function daily($period)
    {
        $format = 'd-m-Y';
        $period = explode('-', request()->period);

        $records = Sale::where('business_id', Auth::user()->business_id)
            ->whereMonth('created_at', '=', $period[0])
            ->whereYear('created_at', '=', $period[1])
            ->latest()
            ->get()
            ->groupBy(function ($item) use ($format) {
                return $item->created_at->format($format);
            });

        return view('user.reports.sales.daily', compact('records'))->with($this->meta);
    }

    public function details($period)
    {
        $records = Sale::where('business_id', Auth::user()->business_id)
            ->whereDay('created_at', '=', $period)
            ->latest()
            ->get();

        return view('user.reports.sales.details', compact('records'))->with($this->meta);
    }


    public function totalSales()
    {
        $date = date('Y-m-d');
        $sale_details = SaleDetails::whereHas('sale', function ($query) use ($date) {
            $query->where('date', [$date]);
        })->paginate(30);
        if (request()->search) {
            $from_date = request()->from_date;
            $to_date   = request()->to_date;

            $sale_details = SaleDetails::whereHas('sale', function ($query) use ($from_date, $to_date) {
                $query->whereBetween('date', [$from_date, $to_date]);
            })->paginate(30);
        }

        return view('user.reports.sales.totalSales', compact('sale_details'))->with($this->meta);
    }
}
