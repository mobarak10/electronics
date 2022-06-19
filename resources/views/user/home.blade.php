@extends('layouts.user')

@section('title', __('contents.dashboard'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <a href="{{ route('cash.index') }}" class="item-box color-1">
                    <div class="content-box">
                        <i class="fa fa-usd" aria-hidden="true"></i>
                        <div class="content">
                            <p class="title">@lang('contents.total_cash_bdt')</p>
                            <p style="padding-right: 5px;" class="number">{{ number_format($cash->sum('amount'), 2) }}</p>
                            <span>@lang('contents.sum_all_cash_balance')</span>
                        </div>
                    </div>
                    <div>
                        <svg width="249" height="50">
                            <g>
                                <path d="M0,25Q17.983333333333334,19.291666666666664,20.75,19.374999999999996C24.9,19.499999999999996,37.35,25.0625,41.5,26.25S58.1,30.875,62.25,31.25S78.85000000000001,30.625,83,30S99.59999999999998,24.25,103.74999999999999,25S120.35000000000001,35.625,124.5,37.5S141.09999999999997,43.75,145.24999999999997,43.75S161.85,38.4375,166,37.5S182.6,35.3125,186.75,34.375S203.34999999999997,27.8125,207.49999999999997,28.125S224.1,37.8125,228.25,37.5Q231.01666666666668,37.291666666666664,249,25L249,50Q231.01666666666668,50,228.25,50C224.1,50,211.64999999999998,50,207.49999999999997,50S190.9,50,186.75,50S170.15,50,166,50S149.39999999999998,50,145.24999999999997,50S128.65,50,124.5,50S107.89999999999999,50,103.74999999999999,50S87.14999999999999,50,83,50S66.4,50,62.25,50S45.65,50,41.5,50S24.9,50,20.75,50Q17.983333333333334,50,0,50Z" class="area" fill="rgba(255,255,255,0.5)"></path>
                            </g>
                        </svg>
                    </div>
                </a>
            </div>

            <div class="col-lg-3">
                <a href="{{ route('bankAccount.index') }}" class="item-box color-2">
                    <div class="content-box">
                        <i class="fa fa-credit-card" aria-hidden="true"></i>
                        <div class="content">
                            <p class="title">@lang('contents.total_bank_balance')</p>
                            <p class="number">{{ number_format($account->sum('balance'), 2) }}</p>
                            <span>@lang('contents.sum_all_bank_balance')</span>
                        </div>
                    </div>

                    <div>
                        <svg width="249" height="50">
                            <g>
                                <path d="M0,25Q17.983333333333334,21.458333333333332,20.75,21.875C24.9,22.5,37.35,30.9375,41.5,31.25S58.1,26.25,62.25,25S78.85000000000001,18.75,83,18.75S99.59999999999998,23.125,103.74999999999999,25S120.35000000000001,35.625,124.5,37.5S141.09999999999997,43.75,145.24999999999997,43.75S161.85,38.4375,166,37.5S182.6,35.3125,186.75,34.375S203.34999999999997,27.8125,207.49999999999997,28.125S224.1,37.8125,228.25,37.5Q231.01666666666668,37.291666666666664,249,25L249,50Q231.01666666666668,50,228.25,50C224.1,50,211.64999999999998,50,207.49999999999997,50S190.9,50,186.75,50S170.15,50,166,50S149.39999999999998,50,145.24999999999997,50S128.65,50,124.5,50S107.89999999999999,50,103.74999999999999,50S87.14999999999999,50,83,50S66.4,50,62.25,50S45.65,50,41.5,50S24.9,50,20.75,50Q17.983333333333334,50,0,50Z" class="area" fill="rgba(255,255,255,0.5)"></path>
                            </g>
                        </svg>
                    </div>
                </a>
            </div>

            <div class="col-lg-3">
                <a href="{{ route('stock.index') }}" class="item-box color-3">
                    <div class="content-box">
                        <i class="fa fa-stack-exchange" aria-hidden="true"></i>
                        <div class="content">
                            <p class="title">@lang('contents.total_stock')</p>

                            @php
                                $total_stocks = 0;
                            @endphp

                            @foreach($total_stock as $total)
                                @php
                                    $total_stocks += $total->stock->sum('quantity') * $total->purchase_price;
                                @endphp
                            @endforeach
                            <p class="number">
                                {{ number_format($total_stocks, 2) }}
                            </p>

                            <span>@lang('contents.sum_all_product')</span>
                        </div>
                    </div>
                    <div>
                        <svg width="249" height="50">
                            <g>
                                <path d="M0,25Q17.983333333333334,40.541666666666664,20.75,40.625C24.9,40.75,37.35,27.8125,41.5,26.25S58.1,24.625,62.25,25S78.85000000000001,30.625,83,30S99.59999999999998,20.8125,103.74999999999999,18.75S120.35000000000001,10.625,124.5,9.375S141.09999999999997,5,145.24999999999997,6.25S161.85,20.9375,166,21.875S182.6,16.5625,186.75,15.625S203.34999999999997,12.1875,207.49999999999997,12.5S224.1,17.5,228.25,18.75Q231.01666666666668,19.583333333333332,249,25L249,50Q231.01666666666668,50,228.25,50C224.1,50,211.64999999999998,50,207.49999999999997,50S190.9,50,186.75,50S170.15,50,166,50S149.39999999999998,50,145.24999999999997,50S128.65,50,124.5,50S107.89999999999999,50,103.74999999999999,50S87.14999999999999,50,83,50S66.4,50,62.25,50S45.65,50,41.5,50S24.9,50,20.75,50Q17.983333333333334,50,0,50Z" class="area" fill="rgba(255,255,255,0.5)"></path>
                            </g>
                        </svg>
                    </div>
                </a>
            </div>

            <div class="col-lg-3">
                <a href="{{ route('loanAccount.index') }}" class="item-box color-4">
                    <div class="content-box">
                        <i class="fa fa-credit-card" aria-hidden="true"></i>
                        <div class="content">
                            <p class="title">Total Loan</p>

                            <p class="number">
                                {{ number_format($loan_accounts->sum('total_loan'), 2) }}
                            </p>

                            <span>Total Amount of Loan</span>
                        </div>
                    </div>
                    <div>
                        <svg width="249" height="50">
                            <g>
                                <path d="M0,25Q17.983333333333334,40.541666666666664,20.75,40.625C24.9,40.75,37.35,27.8125,41.5,26.25S58.1,24.625,62.25,25S78.85000000000001,30.625,83,30S99.59999999999998,20.8125,103.74999999999999,18.75S120.35000000000001,10.625,124.5,9.375S141.09999999999997,5,145.24999999999997,6.25S161.85,20.9375,166,21.875S182.6,16.5625,186.75,15.625S203.34999999999997,12.1875,207.49999999999997,12.5S224.1,17.5,228.25,18.75Q231.01666666666668,19.583333333333332,249,25L249,50Q231.01666666666668,50,228.25,50C224.1,50,211.64999999999998,50,207.49999999999997,50S190.9,50,186.75,50S170.15,50,166,50S149.39999999999998,50,145.24999999999997,50S128.65,50,124.5,50S107.89999999999999,50,103.74999999999999,50S87.14999999999999,50,83,50S66.4,50,62.25,50S45.65,50,41.5,50S24.9,50,20.75,50Q17.983333333333334,50,0,50Z" class="area" fill="rgba(255,255,255,0.5)"></path>
                            </g>
                        </svg>
                    </div>
                </a>
            </div>

            <div class="col-md-12">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
            </div>

            <div class="col-md-12">
                {{-- <h1>@lang('contents.welcome_to_dashboard')</h1> --}}
                {{-- <pre>{{ print_r(Auth::user()->toArray(), 1) }}</pre> --}}

                <!-- Card wrapper -->
                <div class="statistics-wrapper">
                    <div class="row">

                        {{-- today's sell --}}
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-5">
                            <div class="card shadow-sm">
                                <a href="{{ route('pos.index') }}" class="card-header card-header-warning card-header-icon">
                                    <div class="card-icon float-left shadow-sm">
                                        <i class="fa fa-sellsy" aria-hidden="true"></i>
                                    </div>
                                    <p class="card-category">@lang('contents.sales')</p>
                                    <h3 class="card-title text-dark">{{ number_format($sales->sum("grand_total"),2) }}</h3>

                                </a>
                                <div class="footer">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span>@lang('contents.sale_sum')</span>
                                </div>
                            </div>
                        </div>

                        {{-- today's sell return --}}
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-5 mb-lg-0">
                            <div class="card shadow-sm">
                                <div class="card-header card-header-warning card-header-icon">
                                    <div class="card-icon float-left shadow-sm">
                                        <i class="fa fa-undo" aria-hidden="true"></i>
                                    </div>
                                    <p class="card-category">@lang('contents.sale_returns')</p>
                                    <h3 class="card-title">{{ number_format($total_sale_return, 2) }}</h3>

                                </div>
                                <div class="footer">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span>@lang('contents.return_sum')</span>
                                </div>
                            </div>
                        </div>

                        {{-- today's due --}}
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-5 mb-lg-0">
                            <div class="card shadow-sm">
                                <a href="{{ route('customer.index') }}" class="card-header card-header-warning card-header-icon">
                                    <div class="card-icon float-left shadow-sm">
                                        <i class="fa fa-eur" aria-hidden="true"></i>
                                    </div>
                                    <p class="card-category">@lang('contents.customer_due')</p>
                                    <h3 class="card-title text-dark">{{ number_format($customer_balance->sum('balance'), 2) }}</h3>

                                </a>
                                <div class="footer">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span>@lang('contents.sum_customer_due')</span>
                                </div>
                            </div>
                        </div>

                        {{-- expense --}}
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-5 mb-md-0">
                            <!-- Card -->
                            <div class="card shadow-sm">
                                <a href="{{ route('expenditure.index') }}" class="card-header card-header-warning card-header-icon">
                                    <div class="card-icon float-left shadow-sm">
                                        <i class="fa fa-cc-mastercard" aria-hidden="true"></i>
                                    </div>
                                    <p class="card-category">@lang('contents.expense')</p>
                                    <h3 class="card-title text-dark">{{ number_format($expense->sum('amount'), 2) }}</h3>

                                </a>
                                <div class="footer">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span>@lang('contents.sum_todays_expense')</span>
                                </div>
                            </div>
                            <!-- End of Card -->
                        </div>

                        {{-- purchase --}}
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <!-- Card -->
                            <div class="card shadow-sm">
                                <a href="{{ route('purchase.index') }}" class="card-header card-header-warning card-header-icon">
                                    <div class="card-icon float-left shadow-sm">
                                        <i class="fa fa-building"></i>
                                    </div>
                                    <p class="card-category">@lang('contents.purchases')</p>
                                    <h3 class="card-title text-dark">{{ number_format($total_purchase->sum('grand_total'), 2) }}</h3>

                                </a>
                                <div class="footer">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span>@lang('contents.sum_todays_purchase')</span>
                                </div>
                            </div>
                            <!-- End of Card -->
                        </div>

                        {{-- supplier due --}}
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <!-- Card -->
                            <div class="card shadow-sm">
                                <a href="{{ route('supplier.index') }}" class="card-header card-header-warning card-header-icon">
                                    <div class="card-icon float-left shadow-sm">
                                        <i class="fa fa-eur" aria-hidden="true"></i>
                                    </div>
                                    <p class="card-category">@lang('contents.supplier_due')</p>
                                    @php
                                        $total_due = 0;
                                    @endphp
                                    @foreach($suppliers as $supplier)
                                        @php
                                            if ($supplier->balance <= 0) {
                                                $total_due += $supplier->balance;
                                            }
                                        @endphp
                                    @endforeach
                                    <h3 class="card-title text-dark">{{ number_format($total_due, 2) }}</h3>
                                </a>
                                <div class="footer">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span>@lang('contents.sum_supplier_due')</span>
                                </div>
                            </div>
                            <!-- End of Card -->
                        </div>
                    </div>
                </div>
                <!-- End of Card wrapper -->

                <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="m-0 text-center">Today Installment's List</h5>
                                </div>

                                <div class="card-body p-0">

                                    <table class="table table-hover table-bordered">
                                        <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>@lang('contents.customer_name')</th>
                                            <th>@lang('contents.phone')</th>
                                            <th>Address</th>
                                            <th class="text-right">Amount</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($installments as $installment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $installment->hireSale->customer->name ?? '' }}</td>
                                                <td>{{ $installment->hireSale->customer->phone ?? '' }}</td>
                                                <td>{{ $installment->hireSale->customer->address ?? 'N/A' }}</td>
                                                <td class="text-right">{{ number_format($installment->installment_amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="6" class="text-center">No Installment</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                <!-- Chart wrapper -->
                <div class="chart-wrapper">
                    <div class="row">
                        <!-- Purchase Chart -->
                        <div class="col-lg-6 mb-4">
                            <div class="chart shadow rounded card">
                                <div class="card-header">@lang('contents.purchase') </div>

                                <div class="card-body">
                                    <canvas id="purchaseChart" width="300" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- End of purchase chart -->

                        <div class="col-lg-6 mb-4">
                            <!-- Chart -->
                            <div class="chart shadow rounded card">
                                <div class="card-header">@lang('contents.expense') </div>
                                <div class="card-body">
                                    <canvas id="expenseChart" width="300" height="200"></canvas>
                                </div>
                            </div>
                            <!-- End of chart -->
                        </div>

                        <div class="col-lg-12 mb-4">
                            <!-- Chart -->
                            <div class="chart shadow rounded card">
                                <div class="card-header">@lang('contents.daily_chart')</div>

                                <div class="card-body">
                                    <canvas id="dailyChart" width="500" height="200"></canvas>
                                </div>
                            </div>
                            <!-- End of chart -->
                        </div>
                    </div>
                </div>
                <!-- End of chart wrapper -->

            </div>

        </div>
    </div>
    <!-- body content end -->
@endsection

@push('script')
<script>
    // Weekly chart for Purchase
    new Chart($("#purchaseChart"), {
        type: 'bar',
        data: {
            labels: {!! json_encode($getWeeklyPurchase['labels']) !!},
            datasets: [{
                backgroundColor: '#0078D7',
                data: {!! json_encode($getWeeklyPurchase['data']) !!},
            }]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            tooltips: {
                enabled: true,
                mode: 'single',
                backgroundColor: "#f8f8f8",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                caretPadding: 10,
            }
        }
    });
    // End of the Weekly chart for Purchase

    // Weekly chart for Expense
    new Chart($("#expenseChart"), {
        type: 'bar',
        data:{
            labels: {!! json_encode($getWeeklyExpense['labels']) !!},
            datasets: [{
                backgroundColor:'#0078D7',
                data: {!! json_encode($getWeeklyExpense['data']) !!},
            }]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            tooltips: {
                enabled: true,
                mode: 'single',
                backgroundColor: "#f8f8f8",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                caretPadding: 10,
            }
        }
    });
    // End of weekly chart for Expense

    // Line chart for daily sale
    new Chart($('#dailyChart'), {
        type: 'line',
        data:{
            labels: {!! json_encode($getDailySale['labels']) !!},
            datasets: [{
                backgroundColor: '#fff',
                pointBorderColor: "white",
                fill: false,
                borderColor: "#0078D7",
                borderJoinStyle:'miter',
                pointRadius:4,
                pointHoverRadius:6,
                pointBorderColor: "#0078D7",
                borderCapStyle: 'butt',
                lineTension: .4,
                data: {!! json_encode($getDailySale['data']) !!},
            },
            {
                backgroundColor: '#fff',
                pointBorderColor: "white",
                fill: false,
                borderColor: "red",
                borderJoinStyle:'miter',
                pointRadius:4,
                pointHoverRadius:6,
                pointBorderColor: "red",
                borderCapStyle: 'butt',
                lineTension: .4,
                data: {!! json_encode($getDailyReturn['data']) !!},
            }]
        },
        options: {
            legend: {
                display: false
            },
            tooltips: {
                enabled: true,
                mode: 'single',
                backgroundColor: "#f8f8f8",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                caretPadding: 20,
            }
        }
    });
    // End of line chart

</script>
@endpush

@push('style')
<style>
.statistics-wrapper {
    padding: 2.5rem 0;
}
 .statistics-wrapper .card {
    border: 1px solid #e3e6f0;
}
 .statistics-wrapper .card .card-header {
    text-align: right;
    margin: 0 15px;
    padding: 0;
    border-bottom: 0;
    background: none;
}
 .statistics-wrapper .card .card-header .card-icon {
    padding: 15px;
    background: #6f42c1;
    text-align: center;
    margin-top: -20px;
    margin-right: 15px;
    border-radius: 3px;
}
 .statistics-wrapper .card .card-header .card-icon i {
    font-size: 36px;
    color: #fff;
    line-height: 56px;
    width: 56px;
    height: 56px;
}
 .statistics-wrapper .card .card-header .card-category {
    padding-top: 10px;
    font-size: 14px;
    margin: 0;
    color: #858796;
}
 .statistics-wrapper .card .card-header .card-title {
    font-size: 25px;
    font-weight: 300;
    line-height: 1.4em;
    margin-bottom: 0;
}
 .statistics-wrapper .card .footer {
    border-top: 1px solid #eee;
    margin: 20px 15px 10px;
    font-size: 12px;
    padding-top: 10px;
}
 .statistics-wrapper .card .footer a {
    vertical-align: middle;
}
 .statistics-wrapper .card .footer span {
    vertical-align: middle;
}
 .statistics-wrapper .card .footer i {
    vertical-align: middle;
    color: #6e707e;
    margin: 0 3px;
}
 @media (max-width: 576px) {
    .statistics-wrapper .card .card-header .card-icon i {
        width: 40px;
        height: 40px;
        line-height: 40px;
    }
    .statistics-wrapper .card .footer {
        margin: 0px 15px 10px;
    }
}
</style>
@endpush

