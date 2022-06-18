@extends('layouts.user')

@section('title', __('contents.reports'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 py-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">@lang('contents.sale_report')</h5>

                     <span class="d-none d-print-block">Print Date: {{ date('d-m-Y') }}, {{ date('H:i:s A') }}</span>
                        <div>
                            <a href="{{ route('report.sales.total') }}" class="btn btn-info print-none" title="Refesh list.">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>

                            <a href="#" onclick="window.print();" class="btn btn-warning print-none" title="Print.">
                                <i class="fa fa-print" aria-hidden="true"></i>
                            </a>
                        </div>
                </div>

                <div class="card-body p-0">
                    <form action="{{ route('report.sales.total') }}" method="GET">
                            <input type="hidden" name="search" value="1">

                            <div class="form-row col-md-12 print-none">
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
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Brand</th>
                                <th>Quantity</th>
                            </tr>
                            </thead>

                            <tbody>

                                 @forelse ($sale_details as $details)
                                    <tr>
                                        <td class="text-center">{{ $sale_details->firstItem() + $loop->index }}.</td>
                                          <td>{{$details->product->name}}</td>
                                          <td>{{$details->product->brand->name}}</td>
                                          <td>{{$details->quantity}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No sales available.</td>
                                    </tr>
                                @endforelse

                                <tr>
                                    <th colspan="3" class="text-right">Total </th>
                                    <th>{{ $sale_details->sum('quantity') }}</th>
                                </tr>
                            </tbody>
                        </table>

                        <!-- paginate -->
                        <div class="float-right">{{ $sale_details->appends(Request::except('page'))->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
