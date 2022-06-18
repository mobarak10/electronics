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
                <button class="btn" onclick="window.print()">
                    <i class="fa fa-print"></i>
                </button>

                <a class="btn btn-success" href="{{ route('saleReturn.index') }}" title="Back to POS.">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    &nbsp; Back
                </a>
            </div>
        </div>
        <!-- End of the Print btn -->

        <div class="row">
            <!-- Invoice -->
            <div class="invoice print-none main-invoice">
                <!-- Invoice header -->
                <div class="invoice-header">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-4">
                            <div class="logo">
                                <img src="{{ asset($business->media->real_path ?? '') }}" class="img-fluid">
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="text">
                                <span>{{ $business->name ?? '' }}</span>
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
                        <div class="col-4">
                            <div class="single">
                                <div class="title">Billed to:</div>
                                <span>{{ $purchase_return->party->name }}</span>
                                <span>{{ $purchase_return->party->phone }}</span>
                            </div>
                        </div>

                        <div class="col-4 pl-4">
                            <div class="single">
                                <div class="title">Return number:</div>
                                <span>{{ $purchase_return->return_no }}</span>
                            </div>

                            <div class="single">
                                <div class="title">Date of issue:</div>
                                <span>{{ $purchase_return->date ? $purchase_return->date->format('d F, Y') : $purchase_return->created_at->format('d F, Y') }}</span>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="single text-right">
                                <div class="title">Return total:</div>
                                <div class="total">BDT
                                    <span>{{ number_format($purchase_return->subtotal, 2) }}</span>
                                </div>
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
                            <th class="text-right">Quantity(In Unit)</th>
                            <th class="text-right">Line total</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($purchase_return->purchaseReturnProducts as $purchase_return_product)
                            <tr>
                                <td>
                                    <span class="text-wrap">{{ $purchase_return_product->product->name }}</span>
                                    {{--<p>your product product will go here</p>--}}
                                </td>
                                <td class="text-right">{{ number_format(($purchase_return_product->return_price), 2) }}</td>

                                <td class="text-right">
                                    {{ $purchase_return_product->total_quantity_in_format['display'] }}
                                </td>
                                <td class="text-right">{{ number_format($purchase_return_product->line_total, 2) }}</td>
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
                                    Total <span>{{ number_format($purchase_return->subtotal, 2) }}</span>
                                </div>
                            </div>
                            <!-- End of the total -->

                        </div>
{{--                        <div class="col-md-12 text-center mt-4 print-none">--}}
{{--                            Note: {{ $purchase_return->note }}--}}
{{--                        </div>--}}
                    </div>
                </div>
                <!-- End of the terms and total -->
                <!-- Description -->

            <!-- Footer -->
                <div class="footer">
                    <div class="row d-flex align-items-center">
                        <div class="col-6">
                            <div class="customer-signature">
                                Customer signature
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="signature">
                                Authorized signature
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        Thank you for your business
                    </div>
                </div>
                <!-- End of the footer -->

            </div>
            <!-- End of the invoice -->
        </div>
    </div>
@endsection

