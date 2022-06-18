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

                <a class="btn btn-success" href="{{ route('pos.index') }}" title="Back to POS.">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    &nbsp; @lang('contents.back')
                </a>
            </div>
        </div>
        <!-- End of the Print btn -->

        <div class="row">
            <!-- Invoice -->
            <div class="invoice main-invoice">
                <!-- Invoice header -->
                <div class="invoice-header">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-4 print-none">
                            <div class="logo">
                                <img src="{{ asset('public/images/new_hope.jpg') }}" class="img-fluid">
                            </div>
                        </div>

                        <div class="col-6 print-none">
                            <div class="text">
                                <h3 style="color: white">{{ $business->name ?? '' }}</h3>
                                <span>@lang('contents.phone'): {{ $business->phone ?? ''}} </span>
                                <span>@lang('contents.address'): {{ $business->address ?? '' }}</span>
                                <span>@lang('contents.email'): {{ $business->email ?? '' }}</span>
                            </div>
                        </div>
                        <div class="col-12 d-none d-print-block text-center">
                            <span>{{ $business->name ?? '' }}</span><br>
                            <span>Phone: {{ $business->phone ?? ''}} </span><br>
                            <span>Address: {{ $business->address ?? '' }}</span><br>
                            <span>Email: {{ $business->email ?? '' }}</span>
                        </div>
                    </div>
                </div>
                <!-- End of the invoice header -->

                <!-- Client details -->
                <div class="client-details">
                    <div class="row">
                        <div class="col-4">
                            <div class="single">
                                <div class="title">@lang('contents.billed_to'):</div>
                                <span>{{ $sale->customer->name }}</span>
                                <span>{{ $sale->customer->phone }}</span>
                                <span>{{ $sale->customer->division->name_bn ?? '' }}</span>
                                <span>{{ $sale->customer->district->name_bn ?? '' }}</span>
                                <span>{{ $sale->customer->upazila->name_bn ?? '' }}</span>
                            </div>
                        </div>

                        <div class="col-4 pl-4">
                            <div class="single">
                                <div class="title">@lang('contents.invoice_number'):</div>
                                <span>{{ $sale->invoice_no }}</span>
                            </div>

                            <div class="single">
                                <div class="title">@lang('contents.date_of_issue'):</div>
                                <span>{{ $sale->date->format('d F, Y') }}</span>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="single text-right">
                                <div class="title">@lang('contents.total') @lang('contents.invoice'):</div>
                                <div class="total">৳
                                    <span>{{ number_format($sale->grand_total, 2) }}</span>
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
                            <th>@lang('contents.description')</th>
                            <th class="text-right">@lang('contents.unit_price')</th>
                            <th class="text-right">@lang('contents.quantity') (@lang('contents.in_unit'))</th>
                            <th class="text-right">@lang('contents.line_total') </th>
                        </tr>
                        </thead>

                        <tbody>
                        @php
                            $total_kg = 0;
                        @endphp
                        @foreach($sale->saleDetails as $details)
                            @php
                                $total_kg += $details->product->unit->unit_length > 1 ? $details->quantity : 0;
                            @endphp
                            <tr>
                                <td>
                                    <span class="text-wrap">{{ $details->product->name }}</span>
                                    {{--<p>your product details will go here</p>--}}
                                </td>
                                <td class="text-right">{{ number_format(($details->sale_price), 2) }}</td>
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
                                    @lang('contents.total_kg') <span>{{ $total_kg }} @lang('contents.kg')</span>
                                </div>

                                <div class="single">
                                    @lang('contents.subtotal') <span>{{ number_format($sale->subtotal, 2) }}</span>
                                </div>

                                <div class="single">
                                    @lang('contents.discount') <span>{{ number_format($sale->total_discount, 2) }}</span>
                                </div>

                                @if($sale->labour_cost > 0)
                                    <div class="single">
                                        @lang('contents.labour_cost') <span>{{ number_format($sale->labour_cost, 2) }}</span>
                                    </div>
                                @endif

                                @if($sale->transport_cost > 0)
                                    <div class="single">
                                        @lang('contents.transport_cost') <span>{{ number_format($sale->transport_cost, 2) }}</span>
                                    </div>
                                @endif

                                @php
                                $today_total = $sale->subtotal
                                                + $sale->labour_cost
                                                + $sale->transport_cost
                                                - $sale->total_discount;
                                @endphp

                                <div class="single">
                                    Today Total <span>{{ number_format($today_total, 2) }}</span>
                                </div>

                                <div class="single">
                                    @lang('contents.previous_balance') <span>{{ number_format($sale->previous_balance, 2) }}</span>
                                </div>

                                <div class="single">
                                    @lang('contents.grand_total') <span>{{ number_format($sale->grand_total + $sale->previous_balance, 2) }}</span>
                                </div>

                                <div class="single">
                                    @lang('contents.paid') <span>{{ number_format($sale->paid, 2) }}</span>
                                </div>

                                @php
                                $today_due = $today_total - $sale->paid;

                                if($today_due < 0){
                                    $today_due = 0;
                                }
                                @endphp

                                <div class="single">
                                    Today Due <span>{{ number_format($today_due, 2) }}</span>
                                </div>

                                @if($sale->due)
                                    <div class="single">
                                        @lang('contents.due') <span>{{ number_format($sale->due , 2) }}</span>
                                    </div>
                                @endif

{{--                                <div class="single">--}}
{{--                                    @lang('contents.previous_balance')--}}
{{--                                    <span>{{ number_format($sale->previous_balance, 2) }}</span>--}}
{{--                                </div>--}}

{{--                                <div class="single">--}}
{{--                                    @lang('contents.current_balance') <span> {{ number_format($sale->customer_balance, 2) }} </span>--}}
{{--                                </div>--}}
                            </div>
                            <!-- End of the total -->

                        </div>
                        <div class="col-md-12 text-center mt-4 print-none">
                            @lang('contents.note'): {{ $sale->note }}
                        </div>
                    </div>
                </div>
                <!-- End of the terms and total -->
                <!-- Description -->

            <!-- Footer -->
                <div class="footer">
                    <div class="row d-flex align-items-center">
                        <div class="col-6">
                            <div class="signature" style="margin-left: -5px">
                                @lang('contents.customer_signature')
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="signature">
                                @lang('contents.authorized_signature')
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        @lang('contents.thank_you_for_your_business')
                    </div>
                </div>
                <!-- End of the footer -->

            </div>
            <!-- End of the invoice -->

        </div>

    </div>
@endsection

