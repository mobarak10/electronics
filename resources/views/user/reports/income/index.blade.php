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

                        <h5 class="m-0">Income Report </h5>
                        <span class="d-none d-print-block">{{ date('d-m-Y') }}</span>
                        <div>
                            <a href="{{ route('incomeReport.index') }}" class="btn btn-info print-none" title="Refesh list.">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>

                            <a href="#" onclick="window.print();" class="btn btn-warning print-none" title="Print.">
                                <i class="fa fa-print" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-2 print-none">
                        <form action="{{ route('incomeReport.index') }}" method="GET">
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

                        <div class="card-body p-3">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Sector</th>
                                        @foreach(config('coderill.single_months') as $key => $month)
                                        <th class="text-right">{{ $month }}</th>
                                        @endforeach
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @php
                                    $grand_total = 0;
                                @endphp
                                @forelse($income_sectors as $sector)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $sector->sector_name }}</td>
                                        @php
                                            $line_total = 0;
                                        @endphp
                                        @foreach(config('coderill.single_months') as $key => $month)
                                            <td class="text-right">{{ number_format($sector->sum_of_each_month[$month] ?? 0, 2) ?? 0 }}</td>
                                            @php
                                                $line_total += $sector->sum_of_each_month[$month] ?? 0;
                                                $grand_total += $sector->sum_of_each_month[$month] ?? 0;
                                            @endphp
                                        @endforeach
                                        <td class="text-right">{{ number_format($line_total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100" class="text-center">No record found</td>
                                    </tr>
                                @endforelse

                                <tr>
                                    <td colspan="14" class="text-right">Total</td>
                                    @foreach(config('coderill.single_months') as $key => $month)
                                        @php
                                            $grand_total += $income_sectors['sum_of_each_month']['$month'] ?? 0;
                                        @endphp
                                    @endforeach
                                    <td class="text-right">{{ number_format($grand_total, 2) }}</td>
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
