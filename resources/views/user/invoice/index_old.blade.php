@extends('layouts.user')

@section('title', $title)

@push('style')
<link href="{{ asset('public/css/invoice.css') }}" rel="stylesheet">
<link href="{{ asset('public/fonts/font.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">

    <!-- Print btn -->
    <div class="print pb-3">
        <div class="btn-group">
            <button class="btn" onclick="console.log(window.print())">
                <i class="fa fa-print"></i>
            </button>

            <a class="btn btn-success" href="{{ route('pos.index') }}" title="Back to POS.">
                <i class="fa fa-chevron-left" aria-hidden="true"></i>
                &nbsp; Back
            </a>
        </div>
    </div>
    <!-- End of the Print btn -->

    <div class="row">
        <!-- Invoice -->
        <div class="invoice print-none">
            <!-- Invoice header -->
            <div class="invoice-header">
                <div class="row align-items-center justify-content-between">
                    <div class="col-4">
                        <div class="logo">
                            <img src="{{ asset($business->media->real_path ?? '') }}" class="img-fluid">
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="text">
                            <strong class="text-white">{{ $business->name ?? '' }}</strong>
                            <span>Phone: {{ $business->phone ?? ''}} </span>
                            <span>Address: {{ $business->address ?? '' }}</span>
                            <span>Email: {{ $business->email ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of the invoice header -->

            <!-- Client details -->
            <div class="client-details">
                <div class="row">
                    <div class="col-3">
                        <div class="single">
                            <div class="title">Billed to</div>
                            <span>{{ $sale->customer->name }}</span>
                            <span>{{ $sale->customer->address }}</span>
                        </div>
                        <div class="single">
                            <div class="title">Paid By</div>
                            <span>{{ ucfirst($sale->payment_type) }}</span>
                        </div>
                    </div>

                    <div class="col-4 pl-4">
                        <div class="single">
                            <div class="title">Invoice number</div>
                            <span>{{ $sale->invoice_no }}</span>
                        </div>

                        <div class="single">
                            <div class="title">Date of issue</div>
                            <span>{{ $sale->date }}</span>
                        </div>
                    </div>

                    <div class="col-5">
                        <div class="single text-right">
                            <div class="title">Invoice total</div>
                            <div class="total">BDT
                                <span>{{ number_format($calculated_amount['grand_total'], 2) }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of the client details -->

            <!-- Description -->
            <div class="description">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Line total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($sale->saleDetails as $details)
                        <tr>
                            <td>
                                <span>{{ $details->product->name }}</span>
                                {{--<p>your product details will go here</p>--}}
                            </td>
                            <td class="text-right">{{ number_format($details->sale_price, 2) }}</td>
                            <td class="text-right">
                                {{ $details->total_quantity_in_format['display'] }}
                            </td>
                            <td class="text-right">{{ number_format($details->line_total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End of the description -->

            <!-- Terms and total -->
            <div class="terms-and-total">
                <div class="row align-items-center">
                    <div class="col-12">
                        <!-- Total -->
                        <div class="total text-right">
                            <div class="single">
                                Subtotal <span>{{ number_format($sale->subtotal, 2) }}</span>
                            </div>
                            {{-- <div class="single">
                                VAT({{ $sale->vat }}%)
                            <span>{{ number_format($calculated_amount['vat'], 2) }}</span>
                        </div> --}}
                        <div class="single">
                            Total <span>{{ number_format($calculated_amount['total'], 2) }}</span>
                        </div>
                        <div class="single">
                            Discount <span>{{ number_format($calculated_amount['discount'], 2) }}</span>
                        </div>
                        <div class="single">
                            Grand Total <span>{{ number_format($calculated_amount['grand_total'], 2) }}</span>
                        </div>
                        <div class="single">
                            Previous Balance
                            <span>{{ number_format($calculated_amount['previous_balance'], 2) }}</span>
                        </div>
                        <div class="single">
                            Paid <span>{{ number_format($calculated_amount['paid'], 2) }}</span>
                        </div>
                        <div class="single">
                            Current Balance <span> {{ number_format($sale->customer_balance, 2) }} </span>
                        </div>
                    </div>
                    <!-- End of the total -->

{{--                    <div class="text-center">--}}
{{--                        <tr>--}}
{{--                            <td>Remark:</td>--}}
{{--                            <td>{{ $sale->comment }}</td>--}}
{{--                        </tr>--}}
{{--                    </div>--}}

                </div>
            </div>
        </div>
        <!-- End of the terms and total -->
        <!-- Description -->
        @forelse($sale->saleReturns as $sale_return)
        <div class="description">
            <table class="table">
                <caption style="caption-side: top;" class="text-center">Return
                    ({{ $sale_return->created_at->format('d F, Y h:m:i A') }})
                </caption>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Quantity</th>
                        <th class="text-right">Line total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale_return->returnProducts as $return_product)
                    <tr>
                        <td>
                            <span>{{ $return_product->product->name }}</span>
                            {{--<p>your product details will go here</p>--}}
                        </td>
                        <td class="text-right">{{ $return_product->return_price }}</td>
                        <td class="text-right">{{ $return_product->total_return_quantity_in_unit['display'] }}</td>
                        <td class="text-right">{{ number_format($return_product->return_product_price_total, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="terms-and-total">
            <div class="col-12">
                <!-- Total -->
                <div class="total text-right">
                    <div class="single">
                        Subtotal
                        <span>{{ number_format($sale_return->return_product_price_subtotal, 2) }}</span>
                    </div>
                    <div class="single">
                        Adjustment <span>{{ number_format($sale_return->adjustment, 2) }}</span>
                    </div>
                    <div class="single">
                        Total <span>{{ number_format($sale_return->return_product_price_total, 2) }}</span>
                    </div>
                    @if($sale_return->adjust_to_customer_balance)
                    <div class="single">
                        (Adjusted to customer balance by {{ $sale->operator->name }})<br>
                        <small class="text-dark">Customer balance
                            was {{ number_format($sale_return->customer_balance, 2) }}</small>
                    </div>
                    @else
                    <div class="single">
                        Paid
                        <span>{{ number_format($sale_return->return_product_price_total, 2) }}</span>
                    </div>
                    @endif
                </div>
                <!-- End of the total -->

            </div>
        </div>
        @empty
        @endforelse

        <!-- Footer -->
        <div class="footer">
            <div class="row align-items-center">
                <div class="col-6">
                    <p>Thank you for your business</p>
                </div>
                <div class="col-6">
                    <div class="signature">
                        Authorized sign
                    </div>
                </div>
            </div>
        </div>
        <!-- End of the footer -->

    </div>
    <!-- End of the invoice -->

    <!-- Bill paper -->
    <div class="bill-paper">
        <div class="brand mb-1">
            {{ $business->name ?? 'M/S. Pritom Sanitary & Door Gallery'}}
        </div>
        <div class="details">
            <ul>
                <li>Address: {{ $business->address ?? '' }}</li>
                <li>Phone: {{ $business->phone ?? '' }}</li>
            </ul>
        </div>
        <div class="d-flex justify-content-between py-1" style="border-bottom:1px dashed #000;">
            <div>INVOICE NO: # {{ $sale->invoice_no }}</div>
            <div>DATE: {{ $sale->created_at->format('d/m/y') }}</div>
        </div>
        <div class="details text-center">
            <ul>
                <li>Name: {{ $sale->customer->name }}</li>
                @if($sale->customer->phone)
                <li>Mobile: {{ $sale->customer->phone }}</li>
                @endif($sale->customer->phone)
            </ul>
        </div>
        <div class="memo mb-1">
            <table class="table table-borderless mb-1">
                <thead>
                    <tr style="border-bottom:1px dashed #000;">
                        <th>Name</th>
                        <th>Qty</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Line total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->saleDetails as $details)
                    <div>
                        <tr>
                            <td colspan="4">{{ $details->product->name }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right">{{ $details->total_quantity_in_format['display'] }}
                            </td>
                            <td class="text-right">{{ $details->sale_price }}</td>
                            <td class="text-right">{{ number_format($details->line_total, 2) }}</td>
                        </tr>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="total-summary pb-1">
            <div class="single">
                Subtotal : <span>{{ number_format($sale->subtotal, 2) }}</span>
            </div>
            <div class="single">
                Taxes : <span>{{ number_format($calculated_amount['vat'], 2) }}</span>
            </div>
            <div class="single">
                Total Discount : <span>{{ number_format($calculated_amount['discount'], 2) }}</span>
            </div>
            <div class="single">
                Grand Total <span>{{ number_format($calculated_amount['grand_total'], 2) }}</span>
            </div>
            <div class="single">
                Previous Balance
                <span>{{ number_format($calculated_amount['previous_balance'], 2) }}</span>
            </div>

            <div class="single">
                Paid <span>{{ number_format($calculated_amount['paid'], 2) }}</span>
            </div>
            <div class="single">
                Current Balance <span> {{ number_format($sale->customer_balance, 2) }} </span>
            </div>
        </div>
        @forelse($sale->saleReturns as $sale_return)
        <div class="memo mb-1">
            <table class="table table-borderless mb-1">
                <thead>
                    <tr style="border-bottom:1px dashed #000;">
                        <th>Name</th>
                        <th>Qty</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Line total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale_return->returnProducts as $return_product)
                    <div>
                        <tr>
                            <td colspan="4">{{ $return_product->product->name }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right">
                                {{ $return_product->total_return_quantity_in_unit['display'] }}</td>
                            <td class="text-right">{{ $return_product->return_price }}</td>
                            <td class="text-right">
                                {{ number_format($return_product->return_product_price_total, 2) }}</td>
                        </tr>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="total-summary pb-1">
            <div class="single">
                Subtotal : <span>{{ number_format($sale_return->return_product_price_subtotal, 2) }}</span>
            </div>
            <div class="single">
                Adjustment : <span>{{ number_format($sale_return->adjustment, 2) }}</span>
            </div>
            <div class="single">
                Total : <span>{{ number_format($sale_return->return_product_price_total, 2) }}</span>
            </div>
            @if($sale_return->adjust_to_customer_balance)
            <div class="single">
                (Adjusted to customer balance by {{ $sale->operator->name }})<br>
                <small class="text-dark">Customer balance
                    was {{ number_format($sale_return->customer_balance, 2) }}</small>
            </div>
            @else
            <div class="single">
                Paid (by {{ $sale->operator->name }}):
                <span>{{ number_format($sale_return->return_product_price_total, 2) }}</span>
            </div>
            @endif
        </div>
        @empty

        @endforelse
        <div class="notes text-center pt-1">
            {{--                    <strong class="block-heading d-block text-center">Notes</strong>--}}
            <p class="mb-0">Physically damaged product item is not for exchange/refund</p>
            <p class="mb-0">If you find any problem please contact with us</p>
            <p class="mb-0">The product can be replaced within 7 days</p>
        </div>
        <strong class="text-center d-block pt-1">Thank You for Your Business</strong>
    </div>
    <!-- End of the bill paper -->

</div>

</div>
@endsection
