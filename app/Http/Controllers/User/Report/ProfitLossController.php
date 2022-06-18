<?php

namespace App\Http\Controllers\User\Report;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleReturn;
use Auth;

class ProfitLossController extends Controller {
    private $meta = [
        'title'   => 'Profit Loss',
        'menu'    => 'reports',
        'submenu' => '',
    ];

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(){
        $this->meta['submenu'] = 'profit-and-loss';
        $total_sale                                 = $this->totalSaleAmount();
        $total_discount                             = $this->totalDiscountAmount();
        $total_grand_total                          = $this->totalGrandTotalAmount();
        $return_product_price_total                 = $this->totalReturnProductPriceAmount();
        $return_product_purchase_price_total        = $this->totalReturnProductPurchasePriceAmount();
        $total_purchase_price                       = $this->totalSaleProductPurchasePrice();

        return view('user.reports.profit_loss.index', compact(
            'total_sale',
            'total_discount',
            'total_grand_total',
            'return_product_price_total',
            'total_purchase_price',
            'return_product_purchase_price_total'
        ))
            ->with($this->meta);
    }

    /**
     * return Sale for query date
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function saleQuery()
    {
        $from_date    = request()->from_date . ' 00:00:00';
        $to_date      = request()->to_date  . ' 23:59:00';
        $business_id = Auth::user()->business_id;

        return Sale::with('saleDetails')
            ->where('business_id', $business_id)
            ->whereBetween('date', [$from_date, $to_date])
            ->get();
    }

    /**
     * total sale price without due
     **/
    public function totalSaleAmount(){
        $sales = $this->saleQuery();

        return $sales->sum('subtotal');
    }

    /**
     * get total discount amount
     * @return mixed
     */
    public function totalDiscountAmount()
    {
        $sales = $this->saleQuery();

        return $sales->sum('total_discount');
    }

    /**
     * get total grant total amount
     * @return mixed
     */
    public function totalGrandTotalAmount()
    {
        $sales = $this->saleQuery();

        return $sales->sum('grand_total');
    }

    public function returnQuery()
    {
        $from_date    = request()->from_date . ' 00:00:00';
        $to_date      = request()->to_date  . ' 23:59:00';
        $business_id = Auth::user()->business_id;

        return  SaleReturn::where('business_id', $business_id)
            ->whereBetween('date', [$from_date, $to_date])
            ->get();
    }

    /**
     * return total return product price total
     * @return mixed
     */
    public function totalReturnProductPriceAmount()
    {
        return $this->returnQuery()->sum('return_grand_total');
    }

    /**
     * get return product purchase price total
     * @return void
     */
    public function totalReturnProductPurchasePriceAmount()
    {
        return $this->returnQuery()->sum('return_product_purchase_price_total');
    }

    /**
     * @return float|int
     * total sale product purchase price
     */
    public function totalSaleProductPurchasePrice()
    {
        $sales = $this->saleQuery();

//        return $sales->sale_product_purchase_price;
        $total_product_purchase_price = 0;

        foreach ($sales as $sale){
            $total_product_purchase_price += $sale->saleDetails->sum('sale_product_purchase_price_total');
        }

        return $total_product_purchase_price;

    }

}
