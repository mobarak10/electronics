@extends('layouts.admin')

@section('title', $title)

@push('style')
    <link href="{{ asset('public/css/stock.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-12">
            <h1 class="text-center pt-5 pb-4 d-none d-print-block">জননী বস্ত্রালয়</h1>
            <div class="card current-stock">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="d-none d-print-block">Cash</h4>
                    <h5 class="m-0"><span class="print-none">Daily Report of</span> {{date("d/m/Y")}}</h5>
                    <div class="action-area print-none" role="group" aria-label="Action area">
                        <a href="{{ route('admin.dailyReport.index') }}" class="btn btn-primary" title="Refresh">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                        </a>
                        <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>
                    </div>
                </div>

                <!-- search form start -->
                <div class="card-body print-none">
                    <form action="{{ route('admin.dailyReport.index') }}" method="GET" class="row">
                        <input type="hidden" name="search" value="1">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-10">
                                    <label for="business">Business</label>
                                    <select name="business_id" id="business" class="form-control">
                                        <option selected disabled>Choose one</option>
                                        @foreach($businesses as $business)
                                            <option value="{{ $business->id }}">{{ $business->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2" style="padding-top: 30px">
                                    <button type="submit" class="btn btn-primary" type="button" title="search">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- search form end -->
                @if(request()->search)
                    <div class="card-body p-2">
                        <!-- search form start -->
                        <div class="form-row col-md-12 mx-0">
                            <h4 class="print-none">Cash</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Title</th>
                                    <th>Amount</th>
                                </tr>
                                @foreach($cash as $key => $value)
                                    <tr>
                                        <td>{{ $value->title }}</td>
                                        <td class="text-right">{{ number_format($value->amount,2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th class="text-right">Total</th>
                                    <td class="text-right">{{ number_format($cash->sum("amount"),2) }}</td>
                                </tr>
                            </table>

                            <h4>Bank Balance</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Account Name</th>
                                    <th>Account Number</th>
                                    <th>Amount</th>
                                </tr>
                                @foreach($account as $key => $value)
                                    <tr>
                                        <td>{{ $value->account_name }}</td>
                                        <td>{{ $value->account_number }}</td>
                                        <td class="text-right">{{ number_format($value->balance,2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="2" class="text-right">Total</th>
                                    <td class="text-right">{{ number_format($account->sum("balance"),2) }}</td>
                                </tr>
                            </table>

                            <h4>Total Damage Stock</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="2" class="text-right">Total Damage Stock</th>
                                    <td class="text-right">{{ number_format($damage_stock->sum("stock_price"),2) }}</td>
                                </tr>
                            </table>

                            <h4>Total Stock</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="2" class="text-right">Total Stock</th>
                                    <td class="text-right">
                                        @php
                                            $total_stocks = 0;
                                        @endphp

                                        @foreach($total_stock as $total)
                                            @php
                                                $total_stocks += $total->stock->sum('quantity') * $total->purchase_price;
                                            @endphp
                                        @endforeach
                                        {{ number_format($total_stocks, 2) }}
                                    </td>
                                </tr>
                            </table>

                            <div class="col-sm-6">
                                <h4 class="text-center">All Cash In</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Purpose</th>
                                        <td>Amount</td>
                                    </tr>
                                    <tr>
                                        <td>Total Sales</td>
                                        <td class="text-right">{{ number_format($sales->sum("grand_total"),2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Due Receive</td>
                                        <td class="text-right">{{ number_format($due_receive->sum("amount"),2) }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-sm-6">
                                <h4 class="text-center">All Cash Out</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Purpose</th>
                                        <td>Amount</td>
                                    </tr>
                                    <tr>
                                        <td>Total Sale Return</td>
                                        <td class="text-right">{{ number_format($total_sale_return,2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Purchase</td>
                                        <td class="text-right">{{ number_format($total_purchase->sum("grand_total"),2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Due Paid</td>
                                        <td class="text-right">{{ number_format($due_paid->sum("amount"),2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Expense</td>
                                        <td class="text-right">{{ number_format($expense->sum("amount"),2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
