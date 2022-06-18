@extends('layouts.admin')

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
                <a href="#" onclick="console.log(window.print())" class="btn btn-warning" title="Print">
                    <i class="fa fa-print" aria-hidden="true"></i>
                </a>
                <a href="{{ route('admin.pos.index') }}" class="btn btn-primary" title="All cash">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i> &nbsp; Back 
                </a>
            </div>
        </div>
        <!-- End of the Print btn -->

        <div class="row">
            <!-- Invoice -->
            <div class="invoice">
                <!-- Invoice header -->
                <div class="invoice-header">
                    <h4>Invoice</h4>
                </div>
                <!-- End of the invoice header -->

                <!-- Client details -->
                <div class="client-details">
                    <div class="row">
                        <div class="col-3">
                            <div class="single">
                                <div class="title">
                                    Billed to
                                </div>
                                <span>{{ $sale->customer->name }}</span>
                                <span>{{ $sale->customer->address }}</span>
                            </div>
                        </div>
                        <div class="col-4 pl-4">
                            <div class="single">
                                <div class="title">
                                    Invoice number
                                </div>
                                <span>{{ $sale->invoice_no }}</span>
                            </div>
                            <div class="single">
                                <div class="title">
                                    Date of issue
                                </div>
                                <span>{{ $sale->created_at->format('j F, Y') }}</span>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="single text-right">
                                <div class="title">
                                    Invoice total
                                </div>
                                <div class="total">BDT <span>{{ number_format($calculated_amount['grand_total'], 2) }}</span></div>
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
                            <th>From Warehouse</th>
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
                            <td>
                                @foreach($details->quantities as $warehouse)
                                    {{ $warehouse->title }}
                                @endforeach
                            </td>
                            <td class="text-right">{{ $details->sale_price }}</td>
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
                                <div class="single">
                                    VAT({{ $sale->vat }}%) <span>{{ number_format($calculated_amount['vat'], 2) }}</span>
                                </div>
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
                                    Paid <span>{{ number_format($calculated_amount['paid'], 2) }}</span>
                                </div>
                                <div class="single">
                                    Due <span>{{ number_format($sale->due, 2) }}</span>
                                </div>
                            </div>
                            <!-- End of the total -->

                        </div>
                    </div>
                </div>
                <!-- End of the terms and total -->
                <!-- Description -->
                @forelse($sale->saleReturns as $sale_return)
                <div class="description">
                    <table class="table">
                        <caption style="caption-side: top;" class="text-center">Return ({{ $sale_return->created_at->format('d F, Y h:m:i A') }})</caption>
                        <thead>
                        <tr>
                            <th>Description</th>
                            <th>To Warehouse</th>
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
                                <td>
                                    @foreach($return_product->quantities as $warehouse)
                                        {{ $warehouse->title }}
                                    @endforeach
                                </td>
                                <td class="text-right">{{ $return_product->return_price }}</td>
                                <td class="text-right">{{ $return_product->total_return_quantity_in_unit['display'] }}</td>
                                <td class="text-right">{{ number_format($return_product->return_product_price_total, 2) }}</td>
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
                                Subtotal <span>{{ number_format($sale_return->return_product_price_subtotal, 2) }}</span>
                            </div>
                            <div class="single">
                                Adjustment <span>{{ number_format($sale_return->adjustment, 2) }}</span>
                            </div>
                            <div class="single">
                                Total <span>{{ number_format($sale_return->return_product_price_total, 2) }}</span>
                            </div>
                            <div class="single">
                                Total <span>{{ number_format($sale_return->return_product_price_total, 2) }}</span>
                            </div>
                            <div class="single">
                                Paid <span>{{ number_format($sale_return->return_product_price_total, 2) }}</span>
                            </div>
                        </div>
                        <!-- End of the total -->

                    </div>
                </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
@endsection
