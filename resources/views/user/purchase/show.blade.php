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
                                <div class="title">@lang('contents.billed_to')</div>
                                <span>{{ $purchase->party->name }}</span>
                                <span></span>
                            </div>
                        </div>

                        <div class="col-4 pl-4">
                            <div class="single">
                                <div class="title">@lang('contents.invoice') @lang('contents.number')</div>
                                <span>{{ $purchase->voucher_no }}</span>
                            </div>

                            <div class="single">
                                <div class="title">@lang('contents.date_of_issue')</div>
                                <span>{{ $purchase->date->format('j F, Y h:i:m a') }}</span>
                            </div>
                        </div>

                        <div class="col-5">
                            <div class="single text-right">
                                <div class="title">@lang('contents.total') @lang('contents.invoice')</div>
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
                            <th>@lang('contents.description')</th>
                            <th class="text-right">@lang('contents.unit_price')</th>
                            <th class="text-right">@lang('contents.quantity')</th>
                            <th class="text-right">@lang('contents.line_total')</th>
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
                                    {{ \App\Helpers\Converter::convert($details->quantity, $details->product->unit_code, 'd')['display'] }}
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
                                    @lang('contents.subtotal')<span>{{ number_format($purchase->subtotal, 2) }}</span>
                                </div>

                                <div class="single">
                                    @lang('contents.discount') <span>{{ number_format($purchase->discount, 2) }}</span>
                                </div>

                                <div class="single">
                                    Labour Cost <span>{{ number_format($purchase->labour_cost, 2) }}</span>
                                </div>

                                <div class="single">
                                    Transport Cost <span>{{ number_format($purchase->transport_cost, 2) }}</span>
                                </div>

                                <div class="single">
                                    @lang('contents.previous_balance') <span>{{ number_format(abs($purchase->previous_balance), 2) }} {{ $purchase->previous_balance > 0 ? 'Rec' : 'Pay' }}</span>
                                </div>

                                <div class="single">
                                    @lang('contents.grand_total') <span>{{ number_format($purchase->grand_total - $purchase->previous_balance, 2) }}</span>
                                </div>

                                <div class="single">
                                    @lang('contents.paid') <span>{{ number_format($purchase->paid, 2) }}</span>
                                </div>

                                <div class="single">
                                    @lang('contents.due') <span>{{ number_format($purchase->due, 2) }}</span>
                                </div>
                            </div>
                            <!-- End of the total -->
                        </div>
                    </div>
                </div>
                <!-- End of the terms and total -->
            </div>
        </div>
    </div>
@endsection
