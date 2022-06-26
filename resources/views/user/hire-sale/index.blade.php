@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center pt-5 pb-4 d-none d-print-block">@lang('contents.company_name') </h1>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">@lang('contents.all_sales')</h5>
                        <span class="d-none d-print-block">{{ date('d-m-Y') }}</span>

                        <div>
                            <!-- for refresh -->
                            <a href="{{ route('hire-sale.index') }}" class="btn btn-primary print-none" title="Refresh">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>

                            <!-- for collaps search -->
                            <button class="btn btn-info" type="button" title="Search product" data-toggle="collapse" data-target="#searchCollapse" aria-expanded="false" aria-controls="collapseSearch">
                                <i class="fa fa-search"></i>
                            </button>

                            <!-- for print -->
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning print-none">
                                <i aria-hidden="true" class="fa fa-print"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card card-body">
                        <div class="align-items-center {{ request()->search ? '' : 'collapse' }}" id="searchCollapse">
                            <form action="{{ route('hire-sale.index') }}" method="GET">
                                <input type="hidden" name="search" value="1">

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="party">@lang('contents.customer_name')</label>
                                        <select name="condition[customer_id]" class="form-control" id="party">
                                            <option value="">Choose Customer</option>
                                            @foreach($customers as $customer)
                                                <option {{ ((request()->condition['customer_id'] ?? '') == $customer->id) ? 'selected' : '' }} value="{{ $customer->id }}">
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="phone">@lang('contents.customer_phone')</label>
                                        <input type="text" name="condition[phone]" value="{{ request()->condition['phone'] ?? '' }}" placeholder="enter number" class="form-control" id="phone">
                                    </div>

                                    <div class="form-group col-md-4 text-right">
                                        <label for="">&nbsp;</label>
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
                                    <th>@lang('contents.invoice_no')</th>
                                    <th>@lang('contents.customer')</th>
                                    <th class="text-right">@lang('contents.total')</th>
                                    <th class="text-right print-none">@lang('contents.action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $total = 0.00;
                                @endphp

                                @forelse($hire_sales as $sale)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}.</td>
                                        <td>{{ $sale->date->format('d F, Y') }}</td>
                                        <td>
                                            {{ $sale->voucher_no }}
                                        </td>
                                        <td>{{ $sale->customer->name ?? '' }}</td>
                                        <td class="text-right">
                                            @php
                                                $total += $sale->subtotal + $sale->added_value;
                                            @endphp

                                            {{ number_format($sale->subtotal + $sale->added_value, 2) }}
                                        </td>
                                        <td class="text-right print-none">
                                            <a href="{{ route('hire-sale.show', $sale->voucher_no) }}" class="btn btn-sm btn-info"
                                               title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('hire-sale.edit', $sale->id) }}" class="btn btn-sm btn-success {{ count($sale->installmentCollection) > 0 || $sale->installment_status ? 'disabled' : '' }}"
                                               title="View Details">
                                                <i class="fa fa-pencil"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No Sale available.</td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <th colspan="4" class="text-right">Total</th>
                                    <th class="text-right">{{ number_format($total, 2) }}</th>
                                    <th colspan="3">&nbsp;</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    {{ $hire_sales->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#party').select2({
                width: 300,
                height: 100
            });
        });
    </script>
@endpush
