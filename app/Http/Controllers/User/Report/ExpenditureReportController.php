<?php

namespace App\Http\Controllers\User\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Expenditure;
use Auth;

class ExpenditureReportController extends Controller
{
    private $meta = [
        'title'   => 'Expenditure Report',
        'menu'    => 'reports',
        'submenu' => 'expenditure-report'
    ];

    public function __construct() {
        $this->middleware('auth');
    }

    public function index (){

    	$records = Expenditure::query();
        $format = 'm-Y';

        $records = $records->where('business_id', Auth::user()->business_id)
            ->latest()
            ->get()
            ->groupBy(function($item) use ($format) {
                return $item->created_at->format($format);
            });

        return view('user.reports.expenditure.monthly', compact('records'))
            ->with($this->meta);

    }

    public function daily($period) {
        $format = 'd-m-Y';
        $period = explode('-', request()->period);

        $records = Expenditure::where('business_id', Auth::user()->business_id)
            ->whereMonth('created_at', '=', $period[0])
            ->whereYear('created_at', '=', $period[1])
            ->latest()
            ->get()
            ->groupBy(function($item) use ($format) {
                return $item->created_at->format($format);
            });

        return view('user.reports.expenditure.daily', compact('records'))->with($this->meta);
    }

    public function details($period) {
        $records = Expenditure::where('business_id', Auth::user()->business_id)
            ->whereDay('created_at', '=', $period)
            ->latest()
            ->get();

        return view('user.reports.expenditure.details', compact('records'))->with($this->meta);
    }

}
