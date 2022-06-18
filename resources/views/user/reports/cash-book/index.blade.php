@extends('layouts.user')

@section('title', $title)

@push('style')
    <link href="{{ asset('public/css/stock.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center d-none d-print-block"> {{ config('print.print_details.name') }}</h1>
                <p style="margin-bottom: 0 !important;" class="text-center d-none d-print-block">
                    Phone: {{ config('print.print_details.mobile') }}</p>
                <p class="text-center d-none d-print-block">Address: {{ config('print.print_details.address') }}</p>

                <div class="card current-stock">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        @if(request()->search)
                            <div>
                                <h5 class="m-0">
                                    <span>@lang('contents.cash_book') @lang('contents.report') {{ request()->date }} </span>
                                </h5>
                            </div>
                        @endif

                        <div class="text-right">
                            <span
                                class="d-none d-print-block">@lang('contents.print_date'): {{ date('d-m-Y') }}, {{ date('H:i:s A') }}</span>
                        </div>
                        <div class="action-area print-none" role="group" aria-label="Action area">
                            <a href="{{ route('cashBook.index') }}" class="btn btn-primary" title="Refresh">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning">
                                <i aria-hidden="true" class="fa fa-print"></i>
                            </a>
                        </div>
                    </div>

                    <!-- search form start -->
                    <div class="card-body print-none">
                        <form action="{{ route('cashBook.index') }}" method="GET" class="row">
                            <input type="hidden" name="search" value="1">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-10">
                                        <label for="business">@lang('contents.date')</label>
                                        <input type="date" class="form-control" name="date"
                                               value="{{ (request()->search) ? request()->date : date('Y-m-d') }}"
                                               placeholder="Enter date for search">
                                    </div>
                                    <div class="col-md-2" style="padding-top: 30px">
                                        <button type="submit" class="btn btn-primary" title="search">
                                            <i class="fa fa-search"></i>
                                            @lang('contents.search')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- search form end -->
                    <div class="card-body p-2">
                        <!-- search form start -->
                        <div class="form-row col-md-12 mx-0">

                            <div class="col-sm-6">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>@lang('contents.income') @lang('contents.details')</th>
                                            <th class="text-right">@lang('contents.amount')</th>
                                            <th class="text-right">@lang('contents.paid')</th>
                                            <th class="text-right">@lang('contents.due')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <th colspan="4" style="font-size: larger">@lang('contents.sales'):</th>
                                        </tr>
                                        @php
                                            $sale_grand_total = 0;
                                            $sale_paid = 0;
                                            $sale_due = 0;
                                            $total_income_transaction = 0;
                                            $total_due_receive = 0;
                                        @endphp
                                        @foreach($sales as $sale)
                                            <tr>
                                                <td class="text-wrap">{{ $sale->customer->name ?? '' }}</td>
                                                <td class="text-right">{{ number_format($sale->grand_total, 2) }}</td>
                                                <td class="text-right">{{ number_format($sale->paid, 2) }}</td>
                                                <td class="text-right">{{ number_format($sale->due, 2) }}</td>
                                            </tr>
                                            @php
                                                $sale_grand_total += $sale->grand_total;
                                                $sale_paid += $sale->paid;
                                                $sale_due += $sale->due;
                                            @endphp
                                        @endforeach
                                        <tr class="mb-2">
                                            <th>@lang('contents.total')</th>
                                            <th class="text-right">{{ number_format($sale_grand_total, 2) }}</th>
                                            <th class="text-right">{{ number_format($sale_paid, 2) }}</th>
                                            <th class="text-right">{{ number_format($sale_due, 2) }}</th>
                                        </tr>

                                        <tr>
                                            <th style="font-size: larger">@lang('contents.transaction'):</th>
                                            <th></th>
                                        </tr>

                                        @foreach($transaction_form_bank as $transaction)
                                            <tr>
                                                <td>@lang('contents.cash') @lang('contents.balance')
                                                    ({{ $transaction->code }})
                                                </td>
                                                <td class="text-right">{{ number_format($transaction->amount, 2) }}</td>
                                            </tr>
                                            @php
                                                $total_income_transaction += $transaction->amount;
                                            @endphp
                                        @endforeach

                                        <tr>
                                            <th style="font-size: larger">@lang('contents.total') @lang('contents.purchase') @lang('contents.return')
                                                :
                                            </th>
                                            <td class="text-right">{{ number_format($total_purchase_return,2) }}</td>
                                        </tr>

                                        <tr>
                                            <th style="font-size: larger">@lang('contents.due') @lang('contents.receive'):
                                            </th>
                                            <th></th>
                                        </tr>
                                        @foreach($due_receive as $receive)
                                            <tr>
                                                <td>@lang('contents.party_name'): ({{ $receive->customer->name ?? '' }})
                                                </td>
                                                <td class="text-right">{{ number_format($receive->amount, 2) }}</td>
                                            </tr>
                                            @php
                                                $total_due_receive += $receive->amount;
                                            @endphp
                                        @endforeach

                                        <tr>
                                            <td>@lang('contents.grand_total')</td>
                                            <td class="text-right">
                                                @php
                                                    $total_income = $total_purchase_return
                                                                    + $sale_paid
                                                                    + $total_income_transaction
                                                                    + $total_due_receive;
                                                @endphp
                                                {{ number_format($total_income, 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-sm-6">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>@lang('contents.expense') @lang('contents.details')</th>
                                            <th class="text-right">@lang('contents.amount')</th>
                                            <th class="text-right">@lang('contents.paid')</th>
                                            <th class="text-right">@lang('contents.due')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_salary = 0;
                                            $total_expense = 0;
                                            $total_due_paid = 0;
                                            $total_purchase = 0;
                                            $total_purchase_paid = 0;
                                            $total_purchase_due = 0;
                                            $total_expanse_transaction = 0;
                                        @endphp
                                        @foreach($purchases as $purchase)
                                            <tr>
                                                <td class="text-wrap">{{ $purchase->party->name ?? '' }}(@lang('contents.purchase'))</td>
                                                <td class="text-right">{{ number_format($purchase->grand_total, 2) }}</td>
                                                <td class="text-right">{{ number_format($purchase->paid, 2) }}</td>
                                                <td class="text-right">{{ number_format($purchase->due, 2) }}</td>
                                            </tr>
                                            @php
                                                $total_purchase += $purchase->grand_total;
                                                $total_purchase_paid += $purchase->paid;
                                                $total_purchase_due += $purchase->due;
                                            @endphp
                                        @endforeach

                                        <tr>
                                            <td>@lang('contents.total')</td>
                                            <td class="text-right">{{ number_format($total_purchase, 2) }}</td>
                                            <td class="text-right">{{ number_format($total_purchase_paid, 2) }}</td>
                                            <td class="text-right">{{ number_format($total_purchase_due, 2) }}</td>
                                        </tr>

                                        <tr>
                                            <th style="font-size: larger">@lang('contents.employee_salary'):</th>
                                            <th></th>
                                        </tr>
                                        @foreach($salaries as $salary)
                                            <tr>
                                                <td>{{ $salary->user->name ?? '' }}</td>
                                                <td class="text-right">{{ number_format(($salary->increment_total - $salary->decrement_total), 2) }}</td>
                                            </tr>
                                            @php
                                                $total_salary += ($salary->increment_total - $salary->decrement_total);
                                            @endphp
                                        @endforeach
                                        <tr>
                                            <th style="font-size: larger">@lang('contents.expense'):</th>
                                            <th></th>
                                        </tr>
                                        @foreach($expenses as $expense)
                                            <tr>
                                                <td>{{ $expense->glAccountHead->name }}</td>
                                                <td class="text-right">{{ number_format(($expense->amount), 2) }}</td>
                                            </tr>
                                            @php
                                                $total_expense += $expense->amount
                                            @endphp
                                        @endforeach

                                        <tr>
                                            <th style="font-size: larger">@lang('contents.purchase') @lang('contents.cost')
                                                :
                                            </th>
                                            <th class="text-right">{{ number_format($purchases->sum('purchase_cost'), 2) }}</th>
                                        </tr>

                                        <tr>
                                            <th>@lang('contents.total') @lang('contents.sale') @lang('contents.return'):
                                            </th>
                                            <td class="text-right">{{ number_format($total_sale_return,2) }}</td>
                                        </tr>

                                        <tr>
                                            <th style="font-size: larger">@lang('contents.transaction'):</th>
                                            <th></th>
                                        </tr>
                                        @foreach($transaction_form_cash as $transaction)
                                            @if($transaction->amount != 0)
                                                <tr>
                                                    <td>@lang('contents.bank') @lang('contents.balance')
                                                        ({{ $transaction->code }})
                                                    </td>
                                                    <td class="text-right">{{ number_format(($transaction->amount), 2) }}</td>
                                                </tr>
                                            @endif
                                            @php
                                                $total_expanse_transaction += $transaction->amount;
                                            @endphp
                                        @endforeach

                                        <tr>
                                            <th style="font-size: large">Due payment:</th>
                                            <th></th>
                                        </tr>

                                        @foreach($due_payments as $due_payment)
                                            @continue($due_payment->amount == 0)
                                            <tr>
                                                <td>{{ $due_payment->party->name }}</td>
                                                <td class="text-right">{{ abs($due_payment->amount) }}</td>
                                            </tr>

                                            @php
                                                $total_expense += abs($due_payment->amount);
                                            @endphp
                                        @endforeach

                                        <tr>
                                            <td>@lang('contents.grand_total')</td>
                                            @php
                                                $total_expense = $total_salary
                                                                + $total_sale_return
                                                                + $total_expense
                                                                + $total_expanse_transaction
                                                                + $total_purchase_paid;
                                            @endphp
                                            <td class="text-right">
                                                {{ number_format($total_expense, 2)
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
{{--                            <div class="col-md-12">--}}
{{--                                <table class="table table-bordered table-sm">--}}
{{--                                    <tr>--}}
{{--                                        <td class="text-right">@lang('contents.cash_in_hand'):</td>--}}
{{--                                        @if(request()->search)--}}
{{--                                            <td class="text-right">--}}
{{--                                                {{ number_format($total_income - $total_expense, 2) }}--}}
{{--                                            </td>--}}
{{--                                        @else--}}
{{--                                            <td class="text-right">--}}
{{--                                                {{ $cashes->sum('amount') }}--}}
{{--                                            </td>--}}
{{--                                        @endif--}}
{{--                                    </tr>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-12 print-none">--}}
{{--                                <div class="text-right">--}}

{{--                                    <button type="button" class="btn btn-primary" data-toggle="modal"--}}
{{--                                            data-target="#newCashModal" title="Create new cash">--}}
{{--                                        <span class="d-block">@lang('contents.cash') @lang('contents.close')</span>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <!-- New cash modal start -->
                            <div class="modal fade" id="newCashModal" tabindex="-1" role="dialog"
                                 aria-labelledby="insertModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('cashBook.storeBalance') }}" method="post">
                                            @csrf

                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="insertModalLabel">@lang('contents.closing_balance')</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-group required">
                                                    <label for="date">@lang('contents.date')</label>
                                                    <input type="date" name="date" class="form-control"
                                                           value="{{ date('Y-m-d') }}" id="date" required>
                                                </div>

                                                <div class="form-group required">
                                                    <input type="text" name="amount" readonly class="form-control"
                                                           value="{{ $cashes->sum('amount') }}" id="amount"
                                                           placeholder="0.00" step="any" required>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                        data-dismiss="modal">@lang('contents.close')</button>
                                                <button type="submit"
                                                        class="btn btn-primary">@lang('contents.save')</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- New cash modal end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
