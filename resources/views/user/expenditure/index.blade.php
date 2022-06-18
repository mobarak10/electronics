@extends('layouts.user')

@section('title', __('contents.expenditure'))

@section('content')
<div class="container">
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
                    <div>
                        <h5 class="m-0">@lang('contents.expenditure_list') </h5>
                        <small>
                            @if(request()->search)
                                {{ request()->from }} to {{ request()->to }} expenture list
                            @else
                                @lang('contents.last_15_days_expense_record').
                            @endif
                        </small>
                    </div>
                    <span class="d-none d-print-block">{{ date('d/m/Y') }}</span>
					<div class="action-area print-none" role="group" aria-level="Action area">
                        <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>
                        <a href="{{ route('expenditure.index') }}" class="btn btn-info" title="Refesh list.">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                        </a>

                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
                            <i class="fa fa-search"></i>
                        </button>

                        <a href="{{ route('expenditure.create') }}" class="btn btn-success" title="Add new expense">
							<i class="fa fa-plus"></i>
                        </a>
					</div>
				</div>

				<div class="card-body p-0">
                    <div class="collapse" id="collapseSearch">
                        <div class="card card-body">
                                <form action="{{ route('expenditure.index') }}" method="GET">
                                    <input type="hidden" name="search" value="1">

                                    <div class="row">
                                        <div class="form-group col-md-5 required">
                                            <label for="from-date">From Date</label>
                                            <input type="date" class="form-control" name="from" value="{{ date('Y-m-d') }}" placeholder="From date" id="from-date" required>
                                        </div>

                                        <div class="form-group col-md-5">
                                            <label for="to-date">To Date</label>
                                            <input type="date" class="form-control" name="to" value="{{ date('Y-m-d') }}" placeholder="To date" id="to-date">
                                        </div>

                                        <div class="form-group col-md-2 text-right" style="padding-top: 8px">
                                            <label for=""></label>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i> &nbsp;
                                                Search
                                            </button>
                                        </div>
                                    </div>
                                </form>
                        </div>
                    </div>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>@lang('contents.date')</th>
                                <th class="text-right">@lang('contents.amount') (BDT)</th>
                                <th class="text-right print-none">@lang('contents.action')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $totalAmount = 0.00;
                            @endphp

                            @forelse($subtotal as $date => $value)
                                @php
                                    $totalAmount += $value;
                                @endphp

                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}.</td>
                                    <td>
                                        <a href="{{ route('expenditure.show', $date) }}" target="_blank">
                                            {{ date('j F, Y', strtotime($date)) }}
                                        </a>
                                    </td>
                                    <td class="text-right">{{ number_format($value, 2) }}</td>
                                    <td class="text-right print-none">
                                        <a class="btn btn-primary" href="{{ route('expenditure.show', $date) }}" target="_blank" title="Show all expense in this date.">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No expense available.</td></tr>
                            @endforelse

                            <tr>
                                <th colspan="2" class="text-right">Total </th>
                                <th class="text-right">{{ number_format($totalAmount, 2) }}</th>
                                <th class="print-none">&nbsp;</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
			</div>
        </div>

	</div>
</div>
@endsection
