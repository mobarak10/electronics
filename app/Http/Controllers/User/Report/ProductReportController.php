<?php

namespace App\Http\Controllers\User\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Party;
use App\Models\Product;
use App\Helpers\Converter;

class ProductReportController extends Controller
{
    private $meta = [
        'title'   => 'Product',
        'menu'    => 'reports',
        'submenu' => ''
    ];

    public $data = [];

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(){
        $records = [];
        $records['suppliers'] = Party::all();
        $records['products'] = Product::all();

        if (request()->search) {
            $from_date = request()->from_date;
            $to_date   = request()->to_date;

            $purchases = Purchase::with(['details' => function($query){
                $query->where('product_id', request()->product_id);
            }])
                ->whereBetween('date', [$from_date, $to_date])
                ->whereHas('details', function ($query){
                    $query->where('product_id', request()->product_id);
                })->paginate(15);

            $total_purchase_quantity = 0;
            $total_purchase_price = 0;
            $unit_purchase_code = '';
            foreach ($purchases as $purchase) {
                foreach ($purchase->details as $details) {
                    $total_purchase_quantity += $details->purchase_total_quantities;
                    $unit_purchase_code = $details->product->unit_code;
                    $total_purchase_price += $details->line_total;
                }
            }

            if ($unit_purchase_code != null) {
                $total_purchase_quantity_in_unit = Converter::convert($total_purchase_quantity, $unit_purchase_code, 'd');
            }else{
                $total_purchase_quantity_in_unit = 0.00;
            }
//
            $sales = Sale::with(['saleDetails' => function($query){
                $query->where('product_id', request()->product_id);
            }])
                ->whereBetween('created_at', [$from_date." 00:00:00", $to_date." 23:59:59"])
                ->whereHas('saleDetails', function ($query){
                    $query->where('product_id', request()->product_id);
                })->paginate(15);

            $total_sale_quantity = 0;
            $total_sale_price = 0;
            $unit_sale_code = '';
            foreach ($sales as $sale) {
                foreach ($sale->saleDetails as $details) {
                    $total_sale_quantity += $details->total_quantity;
                    $unit_sale_code = $details->product->unit_code;
                    $total_sale_price += $details->line_total;
                }
            }

            // $total_quantity;

            if ($unit_sale_code != null) {
                $total_sale_quantity_in_unit = Converter::convert($total_sale_quantity, $unit_sale_code, 'd');
            }else{
                $total_sale_quantity_in_unit = 0.00;
            }

            return view('user.reports.product.index', compact('records', 'purchases', 'total_purchase_quantity_in_unit', 'total_purchase_price', 'sales', 'total_sale_quantity_in_unit', 'total_sale_price'))->with($this->meta);
        }

        return view('user.reports.product.index', compact('records'))->with($this->meta);
    }
}
