@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 py-3">
                <h1 class="text-center d-none d-print-block">Shop: {{ config('print.print_details.name') }}</h1>
                <p style="margin-bottom: 0 !important;" class="text-center d-none d-print-block">Phone: {{ config('print.print_details.mobile') }}</p>
                <p class="text-center d-none d-print-block">Address: {{ config('print.print_details.address') }}</p>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Invoice Report </h5>
                        <span class="d-none d-print-block">Print Date: {{ date('d-m-Y') }}, {{ date('H:i:s A') }}</span>
                        <div class="btn-group print-none" role="group" aria-level="Action area">
                            <a href="{{ route('invoiceReport') }}" class="btn btn-info" title="Refesh list.">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>
                            <a href="#" onclick="window.print();" title="Print" class="ml-1 btn btn-warning">
                                <i aria-hidden="true" class="fa fa-print"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0 print-none">
                        <div class="card card-body">
                            <form action="{{ route('invoiceReport') }}" method="GET">
                                <input type="hidden" name="search" value="1">
                                <div class="form-row col-md-12">
                                    <div class="form-group col-md-5 required">
                                        <label for="from-date">From date</label>
                                        <input type="date" class="form-control" name="from_date" value="{{ date(request()->from_date) ?? '' }}" placeholder="From date" id="from-date" required>
                                    </div>

                                    <div class="form-group col-md-5">
                                        <label for="to-date">To date</label>
                                        <input type="date" class="form-control" name="to_date" value="{{ date(request()->to_date) ?? '' }}" placeholder="To date" id="to-date">
                                    </div>

                                    <div class="form-group col-md-2" style="margin-top: 30px">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> &nbsp;
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if(request()->search)
                        {{-- report header --}}
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="m-0">{{ date('j F, Y', strtotime(request()->from_date)) }} To {{ date('j F, Y', strtotime(request()->to_date)) }} Dates Invoice Reports</h5>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-sm table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center">Sl</th>
                                    <th>Customer Name</th>
                                    <th>Address</th>
                                    <th>Invoice No</th>
                                    <th class="text-right">P.Price</th>
                                    <th class="text-right">S.Price</th>
                                    <th class="text-right">Discount</th>
                                    <th class="text-right">Profit</th>
                                    <th class="text-right">Loss</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_purchase_price = 0;
                                        $total_sale_price = 0;
                                        $total_discount = 0;
                                        $total_profit = 0;
                                        $total_loss = 0;
                                    @endphp
                                    @forelse($sales as $sale)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $sale->customer->name }}</td>
                                            <td>{{ $sale->customer->address }}</td>
                                            <td>{{ $sale->invoice_no }}</td>
                                            <td class="text-right">{{ number_format($sale->saleDetails->sum('sale_product_purchase_price_total'), 2) }}</td>
                                            <td class="text-right">{{ number_format($sale->saleDetails->sum('sale_product_sale_price_total'), 2) }}</td>
                                            <td class="text-right">{{ number_format($sale->discount, 2) }}</td>
                                            <td class="text-right">
                                                {{
                                                    $sale->saleDetails->sum('sale_product_sale_price_total')
                                                    -
                                                    ($sale->saleDetails->sum('sale_product_purchase_price_total')
                                                    + $sale->discount) >= 0
                                                    ? number_format($sale->saleDetails->sum('sale_product_sale_price_total')
                                                    - ($sale->saleDetails->sum('sale_product_purchase_price_total')
                                                    + $sale->discount), 2) : number_format(0, 2)
                                                }}
                                            </td>
                                            <td class="text-right">
                                                {{
                                                    $sale->saleDetails->sum('sale_product_sale_price_total')
                                                    -
                                                    ($sale->saleDetails->sum('sale_product_purchase_price_total')
                                                    + $sale->discount) < 0
                                                    ? number_format(abs($sale->saleDetails->sum('sale_product_sale_price_total')
                                                    - ($sale->saleDetails->sum('sale_product_purchase_price_total')
                                                    + $sale->discount)), 2) : number_format(0, 2)
                                                }}
                                            </td>
                                            @php
                                                $total_purchase_price += $sale->saleDetails->sum('sale_product_purchase_price_total');
                                                $total_sale_price += $sale->saleDetails->sum('sale_product_sale_price_total');
                                                $total_discount += $sale->discount;
                                                $total_profit += $sale->saleDetails->sum('sale_product_sale_price_total')
                                                                -
                                                                ($sale->saleDetails->sum('sale_product_purchase_price_total')
                                                                + $sale->discount) >= 0
                                                                ? $sale->saleDetails->sum('sale_product_sale_price_total')
                                                                - ($sale->saleDetails->sum('sale_product_purchase_price_total')
                                                                + $sale->discount) : 0;
                                                $total_loss += $sale->saleDetails->sum('sale_product_sale_price_total')
                                                                -
                                                                ($sale->saleDetails->sum('sale_product_purchase_price_total')
                                                                + $sale->discount) < 0
                                                                ? abs($sale->saleDetails->sum('sale_product_sale_price_total')
                                                                - ($sale->saleDetails->sum('sale_product_purchase_price_total')
                                                                + $sale->discount)) : 0;
                                            @endphp
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="15" class="text-center">No Invoice Available</td>
                                        </tr>
                                    @endforelse
                                    <tr>
                                        <td colspan="4" class="text-right">Total</td>
                                        <td class="text-right">{{ number_format($total_purchase_price, 2) }}</td>
                                        <td class="text-right">{{ number_format($total_sale_price, 2) }}</td>
                                        <td class="text-right">{{ number_format($total_discount, 2) }}</td>
                                        <td class="text-right">{{ number_format($total_profit, 2) }}</td>
                                        <td class="text-right">{{ number_format($total_loss, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
