@extends('layouts.user')

@section('title', 'Retail Due')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 py-3">
                <div class="card">
                    <div class="d-none mt-2 text-center d-print-block">
                        <h5 class="mb-0 center" style="font-size: 25px"> <strong>{{ config('print.print_details.name') }}</strong> </h5>
                        <p class="mb-0 font-12">{{ config('print.print_details.address') }}</p>
                        <span class="mb-0 font-12">{{ config('print.print_details.mobile') }}</span>
                        <p class="mb-0" style="font-size: 15px">{{ Carbon\Carbon::now()->format('j F, Y h:i:s a') }}</p>
                    </div>
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Retail Due Collection</h5>
                        <span class="d-none d-print-block">{{ date('F j, Y') }}</span>

                        <div class="action-area print-none" aria-label="Action area">
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning">
                                <i aria-hidden="true" class="fa fa-print"></i>
                            </a>

                            <a href="{{ route('retailDueCollection.index') }}" class="btn btn-info" title="Refresh">
                                <i class="fa fa-refresh"></i>
                            </a>

{{--                            <a href="{{ route('product.export') }}" title="export excel" class="btn btn-success">--}}
{{--                                <i class="fa fa-file-excel-o" aria-hidden="true"></i>--}}
{{--                            </a>--}}

                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#product-search-form">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="collapse {{ (Route::currentRouteName() == 'product.search') ? 'show' : '' }}" id="product-search-form">
                            <div class="card-body">
                                {{-- <form action="{{ route('product.search') }}" method="GET">
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="warehouse">Warehouse</label>
                                            <select name="warehouse_id" id="warehouse" class="form-control">
                                                <option value="">All Warehouses</option>
                                                @foreach($warehouses as $warehouse)
                                                    <option {{ (isset($searched_query['warehouse_id']) AND $searched_query['warehouse_id'] == $warehouse->id) ? 'selected' : '' }} value="{{ $warehouse->id }}">{{ $warehouse->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" class="form-control" id="barcode" value="{{ $searched_query['barcode'] ?? '' }}" placeholder="PRDXXXX">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label>&nbsp;</label>
                                            <button class="btn btn-primary btn-block" type="submit">Search</button>
                                        </div>
                                    </div>
                                </form> --}}
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>Promise Date</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Address</th>
                                    <th>Invoice</th>
                                    <th class="text-right">Grand Total</th>
                                    <th class="text-right">Paid</th>
                                    <th class="text-right">Due</th>
                                    <th class="text-right print-none">@lang('contents.action')</th>
                                </tr>
                                </thead>

                                <tbody>

                                @forelse($sales as $sale)
                                    <tr>
                                        <td>{{ $loop->iteration }}.</td>
                                        <td>{{ $sale->date->format('d F Y') }}</td>
                                        <td>{{ $sale->promise_date ? $sale->promise_date->format('d F Y') : '' }}</td>
                                        <td>{{ $sale->customer->name }}</td>
                                        <td>{{ $sale->customer->phone }}</td>
                                        <td>{{ $sale->customer->address }}</td>
                                        <td>{{ $sale->invoice_no }}</td>
                                        <td class="text-right">{{ number_format($sale->grand_total, 2) }}</td>
                                        <td class="text-right">{{ number_format($sale->paid, 2) }}</td>
                                        <td class="text-right">{{ number_format($sale->due, 2) }}</td>

                                        <td class="text-right print-none">
                                            <a href="{{ route('createRetailDue', $sale->id) }}" class="btn btn-sm btn-primary" title="collect due.">
                                                Due Collection
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="20" class="text-center">No retail due</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- paginate -->
                        <div class="float-right mx-2">
                            {{ $sales->appends(Request::except('page'))->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push("script")
    <script type="text/javascript">
        function openWin(url)
        {
            var myWindow=window.open(url,'','width=500,height=500');
        }
    </script>
@endpush
