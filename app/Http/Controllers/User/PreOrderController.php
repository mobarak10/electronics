<?php

namespace App\Http\Controllers\User;

use App\Helpers\SMS;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Cash;
use App\Models\Customer;
use App\Models\PreOrder;
use App\Models\PreOrderDetails;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SmsReport;
use App\Models\Warehouse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PreOrderController extends Controller
{
    private $meta = [
        'title' => 'Pre Order',
        'menu' => 'pre-order',
        'submenu' => '',
        'header' => false
    ];

    public $sender_id, $api_key;
    private $pre_order;

    public function __construct()
    {
        $this->middleware('auth');
        $this->sender_id = env('SMS_SENDER_ID');
        $this->api_key = env('SMS_API_KEY');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|Response|View
     */
    public function index()
    {
        $this->meta['submenu'] = 'list';

        $pre_orders = PreOrder::orderBy('id', 'desc')->paginate(30);

        return view('user.pre-order.index', compact('pre_orders'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->meta['submenu'] = 'add';
        $this->meta['aside'] = false; //hide aside

        $cashes = Cash::all();

        $warehouses = Warehouse::with('products.unit')->get();

        $customers = Customer::select('id', 'name', 'phone', 'type', 'address', 'balance')->get();
        $customer_type = config('coderill.customer_type');

        $bank_accounts = BankAccount::with('bank')
            ->get();
        $lang = __('contents');

        return view('user.pre-order.create', compact('warehouses', 'lang', 'customer_type', 'cashes', 'bank_accounts', 'customers'))
            ->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //        return $request->all();
        DB::transaction(function () use ($request) {
            $pre_order_data = [
                'date' => $request->date,
                'order_no' => 'ORD' . str_pad(PreOrder::max('id') + 1, 8, '0', STR_PAD_LEFT),
                'user_id' => Auth::id(),
                'customer_id' => $request->customer_id,
                'cash_id' => $request->payment['cash_id'] ?? null,
                'bank_account_id' => $request->payment['bank_account_id'] ?? null,
                'payment_type' => $request->payment['method'],
                'subtotal' => $request->payment['subtotal'],
                'discount' => $request->payment['total_discount'],
                'discount_type' => 'flat',
                'labour_cost' => $request->labourCost,
                'transport_cost' => $request->transportCost,
                'paid' => $request->payment['paid'],
                'due' => $request->payment['due'] ?? 0,
                'change' => $request->payment['change'] ?? 0,
                'delivered' => $request->delivered,
                'comment' => $request->comment,
                'warehouse_id' => $request->warehouse_id,
                'business_id' => Auth::user()->business_id,
            ];
            // get customer
            $customer = Customer::findOrFail($request->customer_id);

            // calculate paid amount
            $paid_amount = $request->payment['paid'];
            // if it has change
            if (isset($request->payment['change']) && $request->payment['change'] > 0) {
                $paid_amount = $request->payment['paid'] - $request->payment['change'];
            }

            // update customer balance
            // if customer has due then add the due in customer balance
            if ($request->payment['due'] >= 0) {
                $customer->increment('balance', $request->payment['due']);
            }

            // if it has paid then increment bank or cash balance
            switch ($request->payment['method']) {
                case 'cash':
                    // update cash balance
                    Cash::find($request->payment['cash_id'])->increment('amount', $paid_amount);
                    break;
                case 'bank':
                    // update bank balance
                    BankAccount::find($request->payment['bank_account_id'])->increment('balance', $paid_amount);
                    break;
            }
            // insert pre-order
            $this->pre_order = PreOrder::create($pre_order_data);
            $pre_order = $this->pre_order;

            if ($request->sms) {
                $message = "You have pre-order " . number_format($pre_order->pre_order_grand_total, 2) . " paid " . number_format($pre_order->paid, 2) . " due " . number_format($pre_order->due, 2);
                $message = $message . " " . config('sms.regards');
                $report = SMS::customSmsSend($this->sender_id, $this->api_key, $customer->phone, $message); //send sms
                $data = explode('|', $report); //for decode data

                if (($data[0] ?? '00') == '1101') {
                    $sms_report = new SmsReport();
                    $sms_report->sent_to = $customer->phone;
                    $sms_report->message = $message;
                    $sms_report->total_character = strlen($message);
                    $sms_report->total_sms = 1;
                    $sms_report->status = "success";
                    $sms_report->save();
                }
            }

            foreach ($request->products as $product) {
                $_product = Product::find($product['id']);

                // pre-order details data
                $pre_order_details_data = [
                    'product_id' => $product['id'],
                    'purchase_price' => $product['purchase_price'],
                    'sale_price' => $product['price'],
                    'vat' => 0.00,
                    'discount' => 0.00,
                    'quantity' => $product['quantity'],
                    'quantity_in_unit' => $product['quantity_in_unit'],
                    'delivery_quantity' => $product['delivery_quantity'],
                    'delivery_quantity_in_unit' => $product['first_delivery_quantity_in_unit'],
                    'discount_type' => 'flat',
                    'line_total' => $product['line_total'],
                ];
                // create pre-order details
                $pre_order->preOrderDetails()
                    ->create($pre_order_details_data);

                // if it has delivery quantity then insert into pre-order delivery details
                if ($product['delivery_quantity'] > 0) {
                    // pre-order delivery details data
                    $pre_order_delivery_details_data = [
                        'product_id' => $product['id'],
                        'date' => $request->date,
                        'delivery_quantity' => $product['delivery_quantity'],
                        'delivery_quantity_in_unit' => $product['delivery_quantity_in_unit'],
                    ];

                    // create pre-order delivery details
                    $pre_order->preOrderDeliveryDetails()
                        ->create($pre_order_delivery_details_data);

                    // decrement product quantity
                    $warehouse = $_product->warehouses()
                        ->find($request->warehouse_id);

                    // decrement quantity from warehouse
                    $warehouse->stock->decrement('quantity', $product['delivery_quantity']);
                }
            }
        });

        return response($this->pre_order, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|Response|View
     */
    public function show($id)
    {
        $pre_order = PreOrder::with([
            'preOrderDeliveryDetails',
            'preOrderDeliveryDetails.product' => function ($query) {
                $query->select('id', 'name', 'unit_id');
            },
        ])->findOrFail($id);

        $pre_order['formatted_pre_order_details'] = $this->formattedPreOrderDetails($pre_order);
        return view('user.pre-order.show', compact('pre_order'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $old_pre_order = PreOrder::query()
            ->with([
                'products',
                'preOrderDetails',
                'preOrderDeliveryDetails' => function ($query) {
                    $query->oldest();
                },
                'customer'
            ])
            ->findOrFail($id);

        $this->meta['submenu'] = 'add';
        $this->meta['aside'] = false; //hide aside

        $cashes = Cash::all();

        $warehouses = Warehouse::with('products.unit')->get();

        $customers = Customer::select('id', 'name', 'phone', 'type', 'address', 'balance')->get();
        $customer_type = config('coderill.customer_type');

        $bank_accounts = BankAccount::with('bank')
            ->get();
        $lang = __('contents');

        return view('user.pre-order.edit', compact('warehouses', 'lang', 'customer_type', 'cashes', 'bank_accounts', 'customers', 'old_pre_order'))
            ->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
//        return $request->all();
        $old_pre_order = PreOrder::query()
            ->with('warehouse')
            ->findOrFail($id);

        DB::transaction(function () use ($old_pre_order, $request) {
            // restore customer balance
            $old_pre_order->customer
                ->decrement('balance', $old_pre_order->due);

            $old_paid_amount = $old_pre_order->paid;

            if ($old_pre_order->change > 0) {
                $old_paid_amount -= $old_pre_order->change;
            }

            // if saved money on cash
            // restore cash balance
            if ($old_pre_order->cash_id) {
                $old_pre_order->cash()
                    ->decrement('amount', $old_paid_amount);

                // set pre order cash id null
                $old_pre_order->cash_id = null;
                $old_pre_order->save();
            } elseif ($old_pre_order->bank_account_id) {
                // saved money on bank account

                // restore bank balance
                $old_pre_order->bankAccount()
                    ->decrement('balance', $old_paid_amount);

                // set pre order bank account id null
                $old_pre_order->bank_account_id = null;
                $old_pre_order->save();
            }

            // delete pre order details
            $old_pre_order->preOrderDetails()
                ->delete();

            // restore stock
            foreach ($old_pre_order->preOrderDeliveryDetails as $detail) {
                $old_product_from_warehouse = $old_pre_order->warehouse
                    ->products()
                    ->where('products.id', $detail->product_id)
                    ->first();

                // if product exists in warehouse
                if ($old_product_from_warehouse) {
                    $old_product_from_warehouse->stock
                        ->increment('quantity', $detail->delivery_quantity);
                } else {
                    // currently product not exists in warehouse

                    // add this product on warehouse
                    $old_pre_order->warehouse
                        ->products()
                        ->attach($detail->product_id, [
                            'quantity' => $detail->delivery_quantity,
                            'average_purchase_price' => $detail->product->purchase_price
                        ]);
                }
            }

            // delete pre order delivery details
            $old_pre_order->preOrderDeliveryDetails()
                ->delete();

            // start from store method
            // update pre order

            $pre_order_data = [
                'date' => $request->date,
                // 'order_no' => 'ORD' . str_pad(PreOrder::max('id') + 1, 8, '0', STR_PAD_LEFT),
                'user_id' => Auth::id(),
                'customer_id' => $request->customer_id,
                'cash_id' => $request->payment['cash_id'] ?? null,
                'bank_account_id' => $request->payment['bank_account_id'] ?? null,
                'payment_type' => $request->payment['method'],
                'subtotal' => $request->payment['subtotal'],
                'discount' => $request->payment['total_discount'],
                'discount_type' => 'flat',
                'labour_cost' => $request->labourCost,
                'transport_cost' => $request->transportCost,
                'paid' => $request->payment['paid'],
                'due' => $request->payment['due'] ?? 0,
                'change' => $request->payment['change'] ?? 0,
                'delivered' => $request->delivered,
                'comment' => $request->comment,
                'warehouse_id' => $request->warehouse_id,
                'business_id' => Auth::user()->business_id,
            ];
            // get customer
            $customer = Customer::findOrFail($request->customer_id);

            // calculate paid amount
            $paid_amount = $request->payment['paid'];
            // if it has change
            if (isset($request->payment['change']) && $request->payment['change'] > 0) {
                $paid_amount = $request->payment['paid'] - $request->payment['change'];
            }

            // update customer balance
            // if customer has due then add the due in customer balance
            if ($request->payment['due'] >= 0) {
                $customer->increment('balance', $request->payment['due']);
            }

            // if it has paid then increment bank or cash balance
            switch ($request->payment['method']) {
                case 'cash':
                    // update cash balance
                    Cash::find($request->payment['cash_id'])->increment('amount', $paid_amount);
                    break;
                case 'bank':
                    // update bank balance
                    BankAccount::find($request->payment['bank_account_id'])->increment('balance', $paid_amount);
                    break;
            }
            // update pre-order
            $this->pre_order = tap($old_pre_order)->update($pre_order_data);
            $pre_order = $this->pre_order;

            if ($request->sms) {
                $message = "You have pre-order " . number_format($pre_order->pre_order_grand_total, 2) . " paid " . number_format($pre_order->paid, 2) . " due " . number_format($pre_order->due, 2);
                $message = $message . " " . config('sms.regards');
                $report = SMS::customSmsSend($this->sender_id, $this->api_key, $customer->phone, $message); //send sms
                $data = explode('|', $report); //for decode data

                if (($data[0] ?? '00') == '1101') {
                    $sms_report = new SmsReport();
                    $sms_report->sent_to = $customer->phone;
                    $sms_report->message = $message;
                    $sms_report->total_character = strlen($message);
                    $sms_report->total_sms = 1;
                    $sms_report->status = "success";
                    $sms_report->save();
                }
            }

            foreach ($request->products as $product) {
                $_product = Product::find($product['id']);

                // pre-order details data
                $pre_order_details_data = [
                    'product_id' => $product['id'],
                    'purchase_price' => $product['purchase_price'],
                    'sale_price' => $product['price'],
                    'vat' => 0.00,
                    'discount' => 0.00,
                    'quantity' => $product['quantity'],
                    'quantity_in_unit' => $product['quantity_in_unit'],
                    'delivery_quantity' => $product['delivery_quantity'],
                    'delivery_quantity_in_unit' => $product['first_delivery_quantity_in_unit'],
                    'discount_type' => 'flat',
                    'line_total' => $product['line_total'],
                ];
                // create pre-order details
                $pre_order->preOrderDetails()
                    ->create($pre_order_details_data);

                // if it has delivery quantity then insert into pre-order delivery details
                if ($product['delivery_quantity'] > 0) {
                    // pre-order delivery details data
                    $pre_order_delivery_details_data = [
                        'product_id' => $product['id'],
                        'date' => $request->date,
                        'delivery_quantity' => $product['delivery_quantity'],
                        'delivery_quantity_in_unit' => $product['delivery_quantity_in_unit'],
                    ];

                    // create pre-order delivery details
                    $pre_order->preOrderDeliveryDetails()
                        ->create($pre_order_delivery_details_data);

                    // decrement product quantity
                    $warehouse = $_product->warehouses()
                        ->find($request->warehouse_id);

                    // decrement quantity from warehouse
                    $warehouse->stock->decrement('quantity', $product['delivery_quantity']);
                }
            }
        });

        return response($this->pre_order, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $pre_order = PreOrder::query()
            ->with('warehouse')
            ->findOrFail($id);

        DB::transaction(function () use ($pre_order) {
            // restore customer balance
            $pre_order->customer
                ->decrement('balance', $pre_order->due);

            $old_paid_amount = $pre_order->paid;

            if ($pre_order->change > 0) {
                $old_paid_amount -= $pre_order->change;
            }

            // if saved money on cash
            // restore cash balance
            if ($pre_order->cash_id) {
                $pre_order->cash()
                    ->decrement('amount', $old_paid_amount);

                // set pre order cash id null
                $pre_order->cash_id = null;
                $pre_order->save();
            } elseif ($pre_order->bank_account_id) {
                // saved money on bank account

                // restore bank balance
                $pre_order->bankAccount()
                    ->decrement('balance', $old_paid_amount);

                // set pre order bank account id null
                $pre_order->bank_account_id = null;
                $pre_order->save();
            }

            // delete pre order details
            $pre_order->preOrderDetails()
                ->delete();

            // restore stock
            foreach ($pre_order->preOrderDeliveryDetails as $detail) {
                $old_product_from_warehouse = $pre_order->warehouse
                    ->products()
                    ->where('products.id', $detail->product_id)
                    ->first();

                // if product exists in warehouse
                if ($old_product_from_warehouse) {
                    $old_product_from_warehouse->stock
                        ->increment('quantity', $detail->delivery_quantity);
                } else {
                    // currently product not exists in warehouse

                    // add this product on warehouse
                    $pre_order->warehouse
                        ->products()
                        ->attach($detail->product_id, [
                            'quantity' => $detail->delivery_quantity,
                            'average_purchase_price' => $detail->product->purchase_price
                        ]);
                }
            }

            // delete pre order delivery details
            $pre_order->preOrderDeliveryDetails()
                ->delete();

            // TODO delete pre order
            $pre_order->delete();
        });

        return redirect()->back()
            ->withSuccess('Pre order deleted successfully');
    }

    public function delivery($id)
    {
        $pre_order = PreOrder::with('preOrderDetails.product', 'customer')->findOrFail($id);
        $pre_order['formatted_date'] = $pre_order->date->format('d F Y');
        $warehouses = Warehouse::all();

        return view('user.pre-order.delivery', compact('pre_order', 'warehouses'))
            ->with($this->meta);
    }

    /**
     * pre-order delivery
     * @throws \Throwable
     */
    public function deliveryProcess(Request $request, $id)
    {
        //        return $request->all();
        DB::transaction(function () use ($id, $request) {
            $this->pre_order = PreOrder::findOrFail($id);
            $pre_order = $this->pre_order;
            foreach ($request->order_details as $details) {
                // if it has delivery quantity then insert into pre-order delivery details
                if ($details['new_delivery_quantity'] > 0) {
                    $order_details = PreOrderDetails::findOrFail($details['id']);
                    $_product = Product::findOrFail($details['product']['id']);

                    $order_details->increment('delivery_quantity', $details['new_delivery_quantity']);
                    // pre-order delivery details data
                    $pre_order_delivery_details_data = [
                        'product_id' => $_product->id,
                        'date' => $request->date,
                        'delivery_quantity' => $details['new_delivery_quantity'],
                        'delivery_quantity_in_unit' => $details['delivery_quantity_in_unit'],
                    ];

                    // create pre-order delivery details
                    $pre_order->preOrderDeliveryDetails()
                        ->create($pre_order_delivery_details_data);

                    if ($request->sms) {
                        $message = "You have pre-order " . number_format($pre_order->pre_order_grand_total, 2) . " product receive " . number_format($order_details->delivery_quantity * $order_details->sale_price, 2);
                        $message = $message . " " . config('sms.regards');
                        $report = SMS::customSmsSend($this->sender_id, $this->api_key, $pre_order->customer->phone, $message); //send sms
                        $data = explode('|', $report); //for decode data

                        if (($data[0] ?? '00') == '1101') {
                            $sms_report = new SmsReport();
                            $sms_report->sent_to = $pre_order->customer->phone;
                            $sms_report->message = $message;
                            $sms_report->total_character = strlen($message);
                            $sms_report->total_sms = 1;
                            $sms_report->status = "success";
                            $sms_report->save();
                        }
                    }

                    // decrement product quantity
                    $warehouse = $_product->warehouses()
                        ->find($pre_order->warehouse_id);

                    // decrement quantity from warehouse
                    $warehouse->stock->decrement('quantity', $details['new_delivery_quantity']);
                }
            }
        });

        return \response()->json($this->pre_order, 200);
    }

    public function formattedPreOrderDetails($pre_order)
    {
        return $pre_order->preOrderDeliveryDetails
            ->groupBy('product_id')
            ->map(function ($item) {
                $_item = [];
                $_item['product'] = $item[0]->product;
                $_item['pre_order_id'] = $item[0]['pre_order_id'];
                $_item['product_id'] = $item[0]['product_id'];
                $_item['delivery_quantity_sum'] = $item->sum('delivery_quantity');
                return $_item;
            })
            ->all();
    }
}
