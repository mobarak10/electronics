@extends('layouts.user')

@section('title', $title)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center d-none d-print-block">Shop: {{ config('print.print_details.name') }}</h1>
            <p style="margin-bottom: 0 !important;" class="text-center d-none d-print-block">Phone: {{ config('print.print_details.mobile') }}</p>
            <p class="text-center d-none d-print-block">Address: {{ config('print.print_details.address') }}</p>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Sale Return</h5>
                    <span class="d-none d-print-block">Print Date: {{ date('d-m-Y') }}, {{ date('H:i:s A') }}</span>

                    <div class="print-none">
                        <!-- for refresh -->
                        <a href="{{ route('pos.index') }}" class="btn btn-primary" title="Refresh">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                        </a>

                        <!-- for collaps search -->
                        <button class="btn btn-info" type="button" title="Search product" data-toggle="collapse" data-target="#searchCollapse" aria-expanded="false" aria-controls="collapseSearch">
                            <i class="fa fa-search"></i>
                        </button>

                        <!-- for print -->
                        <a href="#" onclick="window.print();" title="Print" class="btn btn-warning">
                            <i aria-hidden="true" class="fa fa-print"></i>
                        </a>
                    </div>
                </div>

                <div class="card card-body">
                    <div class="{{ request()->search ? '' : 'collapse' }} align-items-center" id="searchCollapse">
                        <form action="{{ route('pos.index') }}" method="GET">
                            <input type="hidden" name="search" value="1">

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="date-from">@lang('contents.date') (@lang('contents.from'))</label>
                                    <input type="date" class="form-control" name="date[from]" value="{{ request()->search ? request()->date['from'] ? request()->date['from'] : '' : date('Y-m-d') }}" id="date-form">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="date-to">@lang('contents.date') (@lang('contents.to'))</label>
                                    <input type="date" class="form-control" name="date[to]" value="{{ request()->search ? request()->date['to'] ? request()->date['to'] : '' : date('Y-m-d') }}" id="date-to">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="phone">@lang('contents.customer_phone')</label>
                                    <input type="text" name="condition[phone]" value="{{ request()->condition['phone'] ?? '' }}" placeholder="enter number" class="form-control" id="phone">
                                </div>

                                <div class="form-group col-md-3 text-right">
                                    <label>&nbsp;</label>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> &nbsp;
                                        @lang('contents.search')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-sm">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>@lang('contents.date')</th>
                                <th>@lang('contents.customer')</th>
                                <th class="text-right">@lang('contents.total')</th>
                                <th class="text-right">@lang('contents.discount')</th>
                                <th class="text-right print-none">@lang('contents.action')</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($sale_returns as $return)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}.</td>
                                    <td>{{ $return->date->format('d M Y') }}</td>
                                    <td>{{ $return->customer->name }}</td>
                                    <td class="text-right">{{ number_format($return->return_grand_total, 2) }}</td>
                                    <td class="text-right">{{ number_format($return->discount, 2) }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('saleReturn.show', $return->id) }}" class="btn btn-sm btn-info" title="View details">
                                            <i class="fa fa-eye"></i>
                                         </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No sale return available.</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="text-right">
                {{ $sale_returns->appends($_GET)->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
