@extends('layouts.user')

@section('title', __('contents.purchase'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="d-none mt-2 text-center d-print-block">
                        <h5 class="mb-0 center" style="font-size: 25px"> <strong>{{ config('print.print_details.name') }}</strong> </h5>
                        <p class="mb-0 font-12">{{ config('print.print_details.address') }}</p>
                        <span class="mb-0 font-12">{{ config('print.print_details.mobile') }}</span>
                        <p class="mb-0" style="font-size: 15px">{{ Carbon\Carbon::now()->format('j F, Y h:i:s a') }}</p>
                    </div>

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>@lang('contents.all_purchases')</h5>
                        <span class="d-none d-print-block">{{ Carbon\Carbon::now()->format('j F, Y h:i:s a') }}</span>

                        <div class="print-none">
                            {{-- for refresh --}}
                            <a href="{{ route('purchaseOld.index') }}" class="btn btn-primary print-none" title="Refresh">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>

                            {{-- for collaps search --}}
                            <button class="btn btn-primary print-none" type="button" data-toggle="collapse" data-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
                                <i class="fa fa-search"></i>
                            </button>

                            {{-- for print --}}
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning print-none"><i aria-hidden="true" class="fa fa-print"></i></a>
                        </div>
                    </div>

                    <div class="card card-body">
                        <div class="collapse align-items-center" id="collapseSearch">
                            <form action="{{ route('purchaseOld.index') }}" method="GET">
                                <input type="hidden" name="search" value="1">

                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <label for="date-from">@lang('contents.date') (@lang('contents.from'))</label>
                                        <input type="date" class="form-control" name="date[from]" value="{{ request()->date['from'] ?? '' }}" id="date-form">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="date-to">@lang('contents.date') (@lang('contents.to'))</label>
                                        <input type="date" class="form-control" name="date[to]" value="{{ request()->date['to'] ?? '' }}" id="date-to">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="supplier">@lang('contents.supplier')</label>

                                        <select name="condition[party_id]" class="form-control" id="supplier">
                                            <option selected disabled>@lang('contents.choose_one')</option>
                                            @foreach($parties as $party)
                                                <option value="{{ $party->id }}" {{ ((request()->condition['party_id'] ?? '') == $party->id) ? "selected" : "" }}>
                                                    {{ $party->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="voucher_no">@lang('contents.voucher_no')</label>
                                        <input type="text" class="form-control" name="condition[voucher_no]" value="{{ request()->condition['voucher_no'] ?? '' }}" placeholder="xxxxxxxx" id="voucher_no">
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
                                        <th>@lang('contents.supplier')</th>
                                        <th>@lang('contents.voucher_no')</th>
                                        <th class="text-right">@lang('contents.total')</th>
                                        <th class="text-right">@lang('contents.paid')</th>
                                        <th class="text-right print-none">@lang('contents.action')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $total = 0.00;
                                        $totalPaid = 0.00;
                                    @endphp

                                    @forelse($purchases as $purchase)
                                        @php
                                            $grandTotal = $purchase->subtotal - $purchase->discount;
                                            $total += $grandTotal;
                                            $totalPaid += $purchase->paid;
                                        @endphp

                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}.</td>
                                            <td>{{ $purchase->date->format('d F, Y') }}</td>
                                            <td>{{ $purchase->party->name ?? '' }}</td>
                                            <td>{{ $purchase->voucher_no }}</td>
                                            <td class="text-right">{{ number_format($grandTotal, 2) }}</td> {{--Only for flat discount--}}
                                            <td class="text-right">{{ number_format($purchase->paid, 2) }}</td>
                                            <td class="text-right print-none">
                                                <a href="{{ route('purchaseOld.show', $purchase->id) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <a href="{{ route('purchaseOld.return', $purchase->id) }}" class="btn btn-sm btn-danger" title="Return">
                                                    <i class="fa fa-undo"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No purchase history available</td>
                                        </tr>
                                    @endforelse

                                    <tr>
                                        <th colspan="4" class="text-right">@lang('contents.total') </th>
                                        <th class="text-right">{{ number_format($total, 2) }}</th>
                                        <th class="text-right">{{ number_format($totalPaid, 2) }}</th>
                                        <th class="print-none">&nbsp;</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    {{ $purchases->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
