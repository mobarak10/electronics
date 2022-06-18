@extends('layouts.user')

@section('title', __('contents.purchase'))

@push('style')
    <link href="{{ asset('public/css/invoice.css') }}" rel="stylesheet">
    <link href="{{ asset('public/fonts/font.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">

        <!-- Print btn -->
        <div class="print pb-3">
            <div class="btn-group">
                <a href="{{ route('purchase.index') }}" class="btn btn-primary" title="All cash">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i> &nbsp; Back
                </a>

                <a href="#" onclick="console.log(window.print())" class="btn btn-warning" title="Print">
                    <i class="fa fa-print" aria-hidden="true"></i>
                </a>
            </div>
        </div>
        <!-- End of the Print btn -->

        <div class="row">
            <!-- Invoice -->
            <div class="invoice">
                <!-- Invoice header -->
                <div class="invoice-header">
                    <h4>@lang('contents.invoice')</h4>
                </div>
                <!-- End of the invoice header -->

                <!-- Client details -->
                <div class="client-details">
                    <div class="row">
                        <div class="col-3">
                            <div class="single">
                                <div class="title">Billed to</div>
                                <span>{{ $purchase->party->name }}</span>
                                <span></span>
                            </div>
                        </div>

                        <div class="col-4 pl-4">
                            <div class="single">
                                <div class="title">Invoice number</div>
                                <span>{{ $purchase->voucher_no }}</span>
                            </div>

                            <div class="single">
                                <div class="title">Date of issue</div>
                                <span>{{ $purchase->created_at->format('j F, Y h:i:m a') }}</span>
                            </div>
                        </div>

                        <div class="col-5">
                            <div class="single text-right">
                                <div class="title">Invoice total</div>
                                <div class="total">BDT <span>{{ number_format($purchase->grand_total, 2) }}</span></div>
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
                            @foreach($purchase->details as $details)
                            <tr>
                                <td>
                                    <span>{{ $details->product->name }}</span>
                                </td>

                                <td class="text-right">
                                    {{ $details->purchase_price }}
                                </td>

                                <td class="text-right">
                                    {{ $details->purchase_total_quantities_in_unit['display'] }}
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
                                    Subtotal <span>{{ number_format($purchase->subtotal, 2) }}</span>
                                </div>
                                <div class="single">
                                    Discount <span>{{ number_format($purchase->discount, 2) }}</span>
                                </div>
                                <div class="single">
                                    Grand Total <span>{{ number_format($purchase->grand_total, 2) }}</span>
                                </div>
                                <div class="single">
                                    Paid <span>{{ number_format($purchase->paid, 2) }}</span>
                                </div>
                                <div class="single">
                                    Due <span>{{ number_format(($purchase->grand_total - $purchase->paid), 2) }}</span>
                                </div>
                            </div>
                            <!-- End of the total -->
                        </div>
                    </div>
                </div>
                <!-- End of the terms and total -->

                <!-- Description -->
                @forelse($purchase->purchaseReturns as $purchase_return)
                <div class="description">
                    <table class="table">
                        <caption style="caption-side: top;" class="text-center">Return ({{ $purchase_return->created_at->format('d F, Y h:m:i A') }})</caption>
                        <thead>
                        <tr>
                            <th>Description</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Line total</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase_return->purchaseReturnProducts as $return_product)
                                <tr>
                                    <td>
                                        <span>{{ $return_product->product->name }}</span>
                                    </td>
                                    <td class="text-right">{{ $return_product->return_price }}</td>
                                    <td class="text-right">{{ $return_product->total_return_product_quantity_in_unit['display'] }}</td>
                                    <td class="text-right">{{ number_format($return_product->purchase_return_product_line_total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- End of the description -->

                <!-- Terms and total -->
                <div class="terms-and-total">
                    <div class="col-12">
                        Total
                        <div class="total text-right">
                            <div class="single">
                                Subtotal <span>{{ number_format($purchase_return->purchase_return_subtotal, 2) }}</span>
                            </div>
                            <div class="single">
                                Adjustment
                                <span>
                                    {{ number_format($purchase_return->adjustment, 2) }}
                                </span>
                            </div>
                            <div class="single">
                                Total <span>{{ number_format($purchase_return->purchase_return_total, 2) }}</span>
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
