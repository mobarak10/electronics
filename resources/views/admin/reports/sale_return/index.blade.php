@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12 py-3">
                <h1 class="text-center pt-5 pb-4 d-none d-print-block">জননী বস্ত্রালয়</h1>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">

                        <h5 class="m-0">Sale Return List </h5>
                        <span class="d-none d-print-block">{{ date('d-m-Y') }}</span>
                        <div>
                            <a href="{{ route('admin.saleReturnReport') }}" class="btn btn-info print-none" title="Refesh list.">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>

                            <a href="#" onclick="window.print();" class="btn btn-warning print-none" title="Print.">
                                <i class="fa fa-print" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0 print-none">
                        <div class="card card-body">
                            <form action="{{ route('admin.saleReturnReport') }}" method="GET">
                                <input type="hidden" name="search" value="1">

                                <div class="form-row col-md-12">
                                    <div class="form-group col-md-4 required">
                                        <label for="business">Business</label>
                                        <select name="businessId" id="business" class="form-control" required>
                                            <option selected disabled value="">Choose one</option>
                                            @foreach($businesses as $business)
                                                <option value="{{ $business->id }}">{{ $business->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3 required">
                                        <label for="from-date">From date</label>
                                        <input type="date" class="form-control" name="from" value="{{ date('Y-m-d') }}" placeholder="From date" id="from-date" required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="to-date">To date</label>
                                        <input type="date" class="form-control" name="to" value="{{ date('Y-m-d') }}" placeholder="To date" id="to-date">
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

                    @if($records != null)
                        {{-- report header --}}
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="m-0">{{ date('j F, Y', strtotime($from)) }} To {{ date('j F, Y', strtotime($to)) }} Dates Sale Return Reports</h5>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>@lang('contents.invoice_no')</th>
                                    <th>Operator</th>
                                    <th class="text-right">Total Return</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php
                                    $totalAmount = 0.00;
                                @endphp
                                @forelse($records as $saleReturn)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}.</td>
                                        <td>
                                            <a href="{{ route('admin.invoice.generate', $saleReturn->sale->invoice_no) }}"
                                               title="View Invoice" target="_blank">
                                                {{ $saleReturn->sale->invoice_no }}
                                            </a>
                                        </td>
                                        <td>{{ $saleReturn->user->name }}</td>
                                        @php
                                            $totalAmount += $saleReturn->return_product_price_total;
                                        @endphp
                                        <td class="text-right">{{ number_format($saleReturn->return_product_price_total, 2) }}</td>

                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center">No Sale return available.</td></tr>
                                @endforelse
                                <tr>
                                    <th colspan="3" class="text-right">Total </th>
                                    <th class="text-right">{{ number_format($totalAmount, 2) }}</th>
                                </tr>

                                </tbody>
                            </table>

                            <!-- paginate -->
                            <div class="float-right m-3">{{ $records->appends(Request::except('page'))->links() }}
                            </div>

                            @endif
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
