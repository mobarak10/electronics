<?php

namespace App\Http\Controllers\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Purchase;
use App\Models\Party;
use App\Models\Business;
use App\Models\User\User;

class PurchaseReportController extends Controller {
    private $meta = [
        'title'   => 'Purchase Report',
        'menu'    => 'report',
        'submenu' => ''
    ];

    public function __construct() {
        $this->middleware('auth:admin');
    }

    public function purchaseReport(){

        $records = null;

        $from = null;
        $to = null;
        $businesses = Business::all();

        // show result search by date
        if(request()->search) {
            $from = date(request()->from); // start date
            $to   = date(request()->to);   // end date
            $businesses = Business::all();

            // get data
            $record = Purchase::where('business_id', request()->businessId)->whereBetween('created_at', [$from . " 00:00:00",
                $to . " 23:59:59"]);

//            if (request()->userId) {
//                $record->where('user_id', '=', request()->userId);
//            }

            $records = $record->paginate(15);
        }

        // view
        return view('admin.reports.purchase.index', compact('records','businesses', 'from', 'to'))->with($this->meta);
    }

}
