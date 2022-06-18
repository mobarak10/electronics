@extends('layouts.user')
@section('title', $title)
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center d-none d-print-block">Shop: {{ config('print.print_details.name') }}</h1>
                <p style="margin-bottom: 0 !important;" class="text-center d-none d-print-block">
                    Phone: {{ config('print.print_details.mobile') }}</p>
                <p class="text-center d-none d-print-block">Address: {{ config('print.print_details.address') }}</p>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pre Orders</h5>
                        <span class="d-none d-print-block">Print Date: {{ date('d-m-Y') }}, {{ date('H:i:s A') }}</span>

                        <div class="print-none">
                            <!-- for refresh -->
                            <a href="{{ route('pos.index') }}" class="btn btn-primary" title="Refresh">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>

                            <!-- for collapse search -->
                            <button class="btn btn-info" type="button" title="Search pre-order" data-toggle="collapse"
                                    data-target="#searchCollapse" aria-expanded="false" aria-controls="collapseSearch">
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
                                        <input type="date" class="form-control" name="date[from]"
                                               value="{{ request()->search ? request()->date['from'] ? request()->date['from'] : '' : date('Y-m-d') }}"
                                               id="date-form">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="date-to">@lang('contents.date') (@lang('contents.to'))</label>
                                        <input type="date" class="form-control" name="date[to]"
                                               value="{{ request()->search ? request()->date['to'] ? request()->date['to'] : '' : date('Y-m-d') }}"
                                               id="date-to">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="phone">@lang('contents.customer_phone')</label>
                                        <input type="text" name="condition[phone]"
                                               value="{{ request()->condition['phone'] ?? '' }}"
                                               placeholder="enter number" class="form-control" id="phone">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="order_no">Order Number</label>
                                        <input type="text" class="form-control" name="condition[order_no]"
                                               value="{{ request()->condition['order_no'] ?? '' }}"
                                               placeholder="Enter order number" id="order_no">
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
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-sm">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>@lang('contents.date')</th>
                                    <th>Order Number</th>
                                    <th>@lang('contents.customer')</th>
                                    <th class="text-right">@lang('contents.total')</th>
                                    <th class="text-right">@lang('contents.discount')</th>
                                    <th class="text-right print-none">@lang('contents.action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($pre_orders as $order)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}.</td>
                                        <td>{{ $order->created_at->format('d F, Y') }}</td>
                                        <td>
                                            <a href="{{ route('preOrder.show', $order->id) }}" title="View Invoice"
                                               target="_blank">
                                                {{ $order->order_no }}
                                            </a>
                                        </td>
                                        <td>{{ $order->customer->name ?? '' }}</td>

                                        <td class="text-right">
                                            {{ number_format($order->pre_order_grand_total, 2) }}
                                        </td>

                                        <td class="text-right">
                                            {{ number_format($order->discount, 2) }}
                                        </td>

                                        <td class="text-right print-none">
                                            <a href="{{ route('preOrder.show', $order->id) }}"
                                               class="btn btn-sm btn-info" title="View Invoice"
                                               target="_blank">
                                                <i class="fa fa-eye"></i>
                                            </a>

                                            <a href="{{ route('preOrder.edit', $order->id) }}"
                                               class="btn btn-sm btn-success" title="Update sale">
                                                <i class="fa fa-pencil"></i>
                                            </a>

                                            <a href="{{ route('preOrder.delivery', $order->id) }}"
                                               class="btn btn-sm btn-secondary" title="Deliver product">
                                                <i class="fa fa-cart-plus" aria-hidden="true"></i>
                                            </a>

                                            <a href="{{ route('preOrder.destroy', $order->id) }}"
                                               class="btn btn-sm btn-danger" title="Delete Pre Order"
                                               onclick="event.preventDefault();
                                                   if(confirm('Are you sure want to delete?')){ document.getElementById('delete-form-{{ $order->id }}').submit(); }">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form id="delete-form-{{ $order->id }}"
                                                  action="{{ route('preOrder.destroy', $order->id) }}" method="POST"
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="15" class="text-center">No pre-order available.</td>
                                    </tr>
                                @endforelse
                                {{--                                    <tr>--}}
                                {{--                                        <th colspan="4" class="text-right">Total</th>--}}
                                {{--                                        <th class="text-right">{{ number_format($total, 2) }}</th>--}}
                                {{--                                        <th class="text-right">{{ number_format($total_discount, 2) }}</th>--}}
                                {{--                                        <th class="print-none">&nbsp;</th>--}}
                                {{--                                    </tr>--}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    {{ $pre_orders->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
