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
                    <h5 class="mb-0">@lang('contents.all_sales')</h5>
                    <span class="d-none d-print-block">Print Date: {{ date('d-m-Y') }}, {{ date('H:i:s A') }}</span>

                    <div class="print-none">
                        <!-- for refresh -->
                        <a href="{{ route('pos.export') }}" class="btn btn-success" title="Export Excel">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        </a>

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
                                <div class="form-group col-md-2">
                                    <label for="date-from">@lang('contents.date') (@lang('contents.from'))</label>
                                    <input type="date" class="form-control" name="date[from]" value="{{ request()->search ? request()->date['from'] ? request()->date['from'] : '' : date('Y-m-d') }}" id="date-form">
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="date-to">@lang('contents.date') (@lang('contents.to'))</label>
                                    <input type="date" class="form-control" name="date[to]" value="{{ request()->search ? request()->date['to'] ? request()->date['to'] : '' : date('Y-m-d') }}" id="date-to">
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="phone">@lang('contents.customer_phone')</label>
                                    <input type="text" name="condition[phone]" value="{{ request()->condition['phone'] ?? '' }}" placeholder="enter number" class="form-control" id="phone">
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="invoice_no">@lang('contents.invoice_no')</label>
                                    <input type="text" class="form-control" name="condition[invoice_no]" value="{{ request()->condition['invoice_no'] ?? '' }}" placeholder="xxxxxxxx" id="invoice_no">
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="user">@lang('contents.sales_man')</label>
                                    <select name="condition[salesman_id]" class="form-control">
                                        <option value="" selected disabled>Choose one</option>
                                        @foreach($employees as $employee)
                                            <option {{ ((request()->condition['salesman_id'] ?? '') == $employee->id) ? 'selected' : '' }} value="{{ $employee->id }}">
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2 text-right">
                                    <label>&nbsp;</label>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> &nbsp;
                                        @lang('contents.search')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @include('user.exports.invoices', $sales)
                </div>
            </div>

            <div class="text-right">
                {{ $sales->appends($_GET)->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
