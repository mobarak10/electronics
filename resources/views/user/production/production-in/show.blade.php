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
                <a href="{{ route('productionIn.index') }}" class="btn btn-primary" title="All cash">
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
                        <div class="col-6 pl-4">
                            <div class="single">
                                <div class="title">Invoice number</div>
                                <span>{{ $production_in->voucher_no }}</span>
                            </div>
                        </div>
                        <div class="col-6 pl-4 text-right">
                            <div class="single">
                                <div class="title">Date of issue</div>
                                <span>{{ $production_in->date->format('j F, Y h:i:m a') }}</span>
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
                            <th class="text-right">Quantity</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($production_in->details as $details)
                            <tr>
                                <td>
                                    <span>{{ $details->product->name }}</span>
                                </td>

                                <td class="text-right">
                                    {{ $details->production_in_total_quantities_in_unit['display'] }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- End of the description -->
            </div>
        </div>
    </div>
@endsection
