@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                <h1 class="text-center d-none d-print-block">Shop: {{ config('print.print_details.name') }}</h1>
                <p style="margin-bottom: 0 !important;" class="text-center d-none d-print-block">Phone: {{ config('print.print_details.mobile') }}</p>
                <p class="text-center d-none d-print-block">Address: {{ config('print.print_details.address') }}</p>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">

                        <h5 class="m-0">Monthly Report </h5>
                        <span class="d-none d-print-block">Print Date: {{ date('d-m-Y') }}, {{ date('H:i:s A') }}</span>
                        <div>
                            <a href="{{ route('monthlyReport.index') }}" class="btn btn-info print-none" title="Refesh list.">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>

                            <a href="#" onclick="window.print();" class="btn btn-warning print-none" title="Print.">
                                <i class="fa fa-print" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-2 print-none">
                        <form action="{{ route('monthlyReport.index') }}" method="GET">
                            <input type="hidden" name="search" value="1">

                            <div class="form-row col-md-12">
                                <div class="form-group col-md-9 required">
                                    <label for="year">Select Year</label>
                                    <select name="year" class="form-control" required>
                                        <option value="">Choose One</option>
                                        @for ($i=2020; $i <= date('Y') ; $i++)
                                            <option {{ (request()->year == $i) ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                {{-- <div class="form-group col-md-5">
                                    <label for="month">Select Month</label>
                                    <select name="month" class="form-control">
                                        <option value="">Coose One</option>
                                        @foreach(config('coderill.months') as $value => $month)
                                            <option {{ (request()->month == $value) ? 'selected' : '' }} value="{{ $value }}">{{ $month }}</option>}
                                        @endforeach
                                    </select>
                                </div> --}}

                                <div class="form-group col-md-3" style="margin-top: 30px">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> &nbsp;
                                        Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if(request()->search)
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="m-0">{{ request()->year }} Reports</h5>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Month</th>
                                    <th class="text-right">Purchase</th>
                                    <th class="text-right">Sales</th>
                                    <th class="text-right">Expense</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach(config('coderill.months') as $key => $month)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}.
                                        </td>
                                        <td>
                                            {{ $month }}
                                        </td>

                                        <td class="text-right">
                                            {{ $purchases[$month] ?? number_format(0, 2) }}
                                        </td>
                                        <td class="text-right">
                                            {{ $sales[$month] ?? number_format(0, 2) }}
                                        </td>
                                        <td class="text-right">
                                            {{ $expenses[$month] ?? number_format(0, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="2">Total</td>
                                    <td class="text-right">{{ number_format($total_purchases->sum('grand_total'), 2) }}</td>
                                    <td class="text-right">{{ number_format($total_sales->sum('grand_total'), 2) }}</td>
                                    <td class="text-right">{{ number_format($total_expense->sum('amount'), 2) }}</td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
