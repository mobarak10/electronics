@extends('layouts.user')
@section('title', 'Hire Invoice')

@push('style')
    <link href="{{ asset('public/css/invoice.css') }}" rel="stylesheet">
    <link href="{{ asset('public/fonts/font.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Invoice</h5>
                <div class="text-center">
                    <span class="d-none d-print-block font-weight-bold">ভাই ভাই ট্রেডার্স</span>
                    <span class="d-none d-print-block">বেখৈরহাটি বাজার, কেন্দুয়া, নেত্রকোণা</span>
                    <span class="d-none d-print-block">01617-022197</span>
                </div>
                <div>
                    <a href="#" onclick="window.print();" title="Print" class="btn btn-warning print-none">
                        <i aria-hidden="true" class="fa fa-print"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group rounded-0">
                            <li class="list-group-item bg-transparent border-0 py-1  pl-0">
                                <strong class="d-inline-block w-25"> Voucher No </strong>
                                <span> : {{ $hire_sale->voucher_no }} </span>
                            </li>

                            <li class="list-group-item bg-transparent border-0 py-1  pl-0">
                                <strong class="d-inline-block w-25"> Name </strong>
                                <span> : {{ $hire_sale->customer->name ?? '' }} </span>
                            </li>

                            <li class="list-group-item bg-transparent border-0 py-1  pl-0">
                                <strong class="d-inline-block w-25"> Address </strong>
                                <span> : {{ $hire_sale->customer->address ?? '' }} </span>
                            </li>

                            <li class="list-group-item bg-transparent border-0 py-1  pl-0">
                                <strong class="d-inline-block w-25"> 1<sup>st</sup> Guarantor Name </strong>
                                <span> : @foreach ($hire_sale->customer->metas as $meta)
                                        {{ $meta->meta_key == 'first_guarantor_name' ? $meta->meta_value : '' }}
                                    @endforeach
                                </span>
                            </li>

                            <li class="list-group-item bg-transparent border-0 py-1  pl-0">
                                <strong class="d-inline-block w-25"> 1<sup>st</sup> Guarantor Mobile </strong>
                                <span> : @foreach ($hire_sale->customer->metas as $meta)
                                        {{ $meta->meta_key == 'first_guarantor_mobile' ? $meta->meta_value : '' }}
                                    @endforeach
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group rounded-0">
                            <li class="list-group-item bg-transparent border-0 py-1  pl-0">
                                <strong class="d-inline-block w-25"> Mobile </strong>
                                <span> : {{ $hire_sale->customer->phone ?? '' }} </span>
                            </li>

                            <li class="list-group-item bg-transparent border-0 py-1  pl-0">
                                <strong class="d-inline-block w-25"> Sale Date </strong>
                                <span> : {{ $hire_sale->date->format('Y-m-d') }} </span>
                            </li>

                            <li class="list-group-item bg-transparent d-none d-print-block border-0 py-1  pl-0">
                                <strong class="d-inline-block w-25"> Print Time </strong>
                                <span> : {{ date('Y-m-d h:i:s A') }} </span>
                            </li>

                            <li class="list-group-item bg-transparent border-0 py-1  pl-0">
                                <strong class="d-inline-block w-25"> 2<sup>st</sup> Guarantor Name </strong>
                                <span> :
                                    @foreach ($hire_sale->customer->metas as $meta)
                                        {{ $meta->meta_key == 'second_guarantor_name' ? $meta->meta_value : '' }}
                                    @endforeach
                                </span>
                            </li>

                            <li class="list-group-item bg-transparent border-0 py-1  pl-0">
                                <strong class="d-inline-block w-25"> 2<sup>st</sup> Guarantor Mobile </strong>
                                <span> :
                                    @foreach ($hire_sale->customer->metas as $meta)
                                        {{ $meta->meta_key == 'second_guarantor_mobile' ? $meta->meta_value : '' }}
                                    @endforeach
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 mt-5">
                        <table class="table table-striped table-bordered table-sm">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Product Name</th>
                                <th>Model</th>
                                <th>Serial</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Total (TK)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($hire_sale->hireSaleProducts as $sale_product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sale_product->product->name }}</td>
                                    <td>{{ $sale_product->product->model }}</td>
                                    <td>{{ $sale_product->product_serial }}</td>
                                    <td class="text-right">{{ $sale_product->quantity }}</td>
                                    <td class="text-right">{{ number_format($sale_product->sale_price, 2) }}</td>
                                    <td class="text-right">{{ number_format($sale_product->line_total, 2) }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="4" class="text-center"><strong>Total Quantity</strong></td>
                                <td class="text-right">{{ $hire_sale->hireSaleProducts->sum('quantity') }}</td>
                                <td><strong>HMRP Value</strong></td>
                                <td class="text-right">{{ number_format($hire_sale->hireSaleProducts->sum('line_total'), 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" rowspan="4"></td>
                                <td><strong>Total Hire Price</strong></td>
                                <td class="text-right">{{ number_format($hire_sale->subtotal + $hire_sale->added_value, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Previous Balance</strong></td>
                                <td class="text-right">{{ number_format(abs($hire_sale->previous_balance), 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Down Payment</strong></td>
                                <td class="text-right">{{ number_format($hire_sale->down_payment, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Hire Outstanding</strong></td>
                                <td class="text-right">{{ number_format(($hire_sale->subtotal + $hire_sale->added_value + abs($hire_sale->previous_balance)) - ($hire_sale->down_payment), 2) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row ml-1 w-100">
                        <div class="col-6 mt-2">
                            <table class="table table-striped table-bordered table-sm">
                                <tbody>
                                <tr>
                                    <td rowspan="2">INSTALLMENT <br> NUMBER</td>
                                    <td colspan="3" class="text-center">SCHEDULE</td>
                                </tr>
                                <tr>
                                    <td>INSTALLMENT DATE</td>
                                    <td>HIRE PRICE FOR....MONTHS</td>
                                </tr>
                                @foreach($hire_sale->hireSaleInstallments as $installment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $installment->installment_date->format('d F Y') }}</td>
                                        <td class="text-right">{{ number_format($installment->installment_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="col-6 mt-2">
                            <table class="table table-striped table-bordered table-sm">
                                <tbody>
                                <tr>
                                    <td colspan="3" class="text-center">REPAYMENT</td>
                                </tr>
                                <tr>
                                    <td>REPAYMENT DATE</td>
                                    <td class="text-right">COLLECTED AMOUNT</td>
                                    <td class="text-right">DUE</td>
                                </tr>
                                @php
                                    $total_paid = 0;
                                @endphp
                                @foreach($hire_sale->installmentCollection as $installment)
                                    <tr>
                                        <td>{{ $installment->date->format('d F Y') }}</td>
                                        @php
                                            $total_paid += $installment->total_paid;
                                        @endphp
                                        <td class="text-right">{{ number_format($installment->total_paid, 2) }}</td>
                                        <td class="text-right">{{ number_format((ceil($hire_sale->hireSaleInstallments->sum('installment_amount') - $total_paid)), 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-6 text-center mt-5">
                        <p class="d-inline-block border-top border-dark px-2">Signature of Customer</p>
                    </div>
                    <div class="col-6 text-center mt-5">
                        <p class="d-inline-block border-top border-dark px-5">ভাই ভাই ট্রেডার্স</p>
                    </div>
                    <strong class="col-12 d-none text-center d-print-block">Developed By MAXSOP</strong>
                </div>
            </div>
        </div>

    </div>
@endsection
