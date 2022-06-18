<?php

namespace App\Http\Controllers\User\Report;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class InvoiceReportController extends Controller
{
    private $meta = [
        'title'   => 'Invoice Wise Report',
        'menu'    => 'reports',
        'submenu' => 'invoice-report'
    ];

    public function __construct() {
        $this->middleware('auth');
    }
    public function index()
    {
        $sales = '';

        if(request()->search){
            $sales = Sale::with('saleDetails')->whereBetween('date', [request()->from_date, request()->to_date])->get();
        }
        return view('user.reports.invoice-report.index', compact('sales'))->with($this->meta);
    }
}
