<?php

namespace App\Http\Controllers\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Expenditure;
use App\Models\GlAccountHead;
use App\Models\Business;
use App\Models\User\User;

class ExpenditureReportController extends Controller {

    private $meta = [
		'title'   => 'Expenditure Report',
		'menu'    => 'report',
		'submenu' => ''
	];

	public function __construct() {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // $records = Expenditure::all();
        $records = null;
        $businesses = Business::all();
        $glHeads = GlAccountHead::all();

        $from = null;
        $to = null;

        // show result search by date
        if(request()->search) {
            $from = date(request()->from); // start date
            $to   = date(request()->to);   // end date

            // get data
            $record = Expenditure::whereBetween('date', [$from, $to])
                                ->where('business_id', '=', request()->business_id);

//		   if (request()->userId) {
//		   		$record->where('user_id', '=', request()->userId);
//		   }
//
//		   if (request()->sectorId) {
//		   		$record->where('gl_account_head_id', '=', request()->sectorId);
//		   }

		   $records = $record->paginate(15);
        }

        // view
        return view('admin.reports.expenditure.index', compact('records', 'businesses', 'glHeads', 'from', 'to'))->with($this->meta);

    }
}
