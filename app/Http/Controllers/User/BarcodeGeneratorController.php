<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use Auth;

class BarcodeGeneratorController extends Controller {

    private $meta = [
        'title' => 'Barcode',
        'menu' => 'barcode',
        'submenu' => ''
    ];

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $this->meta['barcode'] = 'generator';

        $product = null;
        $business_id = Auth::user()->business_id;

        if(request()->barcode != null) {
            $product = Product::where('business_id', $business_id)->where('barcode', request()->barcode)->first();

            if($product) {
                $product->quantity = request()->quantity;
            }
        }

        return view('user.barcode.index', compact('product'))->with($this->meta);
    }

    public function single() {
        $this->meta['barcode'] = 'single_generator';

        $product = null;
        $business_id = Auth::user()->business_id;

        if(request()->barcode != null) {
            $product = Product::where('business_id', $business_id)->where('barcode', request()->barcode)->first();
        }

        return view('user.barcode.single', compact('product'))->with($this->meta);
    }

    public function invoice() {
        $this->meta['barcode'] = 'invoice_generator';

        $purchase = null;
        $business_id = Auth::user()->business_id;

        if(request()->voucher_no != null) {
            $purchase = Purchase::with('details')->where('voucher_no', request()->voucher_no)->first();
        }

        return view('user.barcode.invoice-print', compact('purchase'))->with($this->meta);
    }

    public function singlePrint(Request $request){
        $product = null;
        $business_id = Auth::user()->business_id;

        if($request->barcode != null) {
            $product = Product::where('business_id', $business_id)
                ->where('barcode', $request->barcode)
                ->first();
        }

        return view('user.barcode.single-print', compact('product'));
    }

}
