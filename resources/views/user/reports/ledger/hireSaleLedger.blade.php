@extends('layouts.user')

@section('title', 'Customer Ledger')

@push('style')
    <link href="{{ asset('public/css/stock.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12">
                <div class="card current-stock">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        @if(request()->search)
                            <div>
                                <h5 class="m-0"><span>Ledger {{ request()->from_date }} to {{ request()->to_date }} </span></h5>
                                <h5 class="m-0 d-none d-print-block"><span>User: {{ Illuminate\Support\Facades\Auth::user()->name }} </span></h5>
                                <h5 class="m-0 d-none d-print-block">Party Name: {{ $party->name }}</h5>
                            </div>
                        @endif
                        <div>
                            <div class="d-none mt-2 text-center d-print-block">
                                <h5 class="mb-0 center" style="font-size: 25px"> <strong>{{ config('print.print_details.name') }}</strong> </h5>
                                <p class="mb-0 font-12">{{ config('print.print_details.address') }}</p>
                                <span class="mb-0 font-12">{{ config('print.print_details.mobile') }}</span>
                                <p class="mb-0" style="font-size: 15px">{{ Carbon\Carbon::now()->format('j F, Y h:i:s a') }}</p>
                            </div>
                            <h5 class="text-center pb-4 d-none d-print-block">Ledger Report</h5>
                            <hr>
                        </div>
                        <div>
                            <h5 class="m-0 d-none d-print-block"><span>Print Time: {{ date("h:i:sa") }} </span></h5>
                            <h5 class="m-0 d-none d-print-block"><span>Print Date: {{ date("Y-M-d") }} </span></h5>
                        </div>
                        <div class="action-area print-none" role="group" aria-label="Action area">
                            <a href="{{ route('report.customerLedger') }}" class="btn btn-primary" title="Refresh">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning">
                                <i aria-hidden="true" class="fa fa-print"></i>
                            </a>
                        </div>
                    </div>

                    <!-- search form start -->
                    <div class="card-body print-none">
                        <form action="{{ route('report.customerLedger') }}" method="GET" class="row">
                            <input type="hidden" name="search" value="1">
                            <div class="form-row col-md-12">
                                <div class="form-group col-md-3">
                                    <label for="from_date">@lang('contents.date_from')</label>
                                    <input type="date" name="from_date" id="from_date" value="{{ request('from_date', '') }}" class="form-control">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="to_date">@lang('contents.date_to')</label>
                                    <input type="date" name="to_date" id="to_date" value="{{ request('to_date', '') }}" class="form-control">
                                </div>

                                <div class="form-group col-md-4 required">
                                    <label for="car_id">@lang('contents.customer')</label>
                                    <select name="party_id" class="form-control" id="js-example-basic-single" required>
                                        <option value="">@lang('contents.choose_one')</option>
                                        @foreach($parties as $party)
                                            <option {{ (request()->party_id == $party->id) ? 'selected' : '' }} value="{{ $party->id }}">{{ $party->name.' ('. $party->address.')' }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2 text-right" style="margin-top: 30px">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i>
                                        @lang('contents.search')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- search form end -->
                    @if(request()->search)
                        <div class="card-body p-2">
                            <!-- search form start -->
                            <div class="form-row col-md-12 mx-0">

                                <div class="col-sm-12">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                        <tr>
                                            <th>@lang('contents.sl')</th>
                                            <th>@lang('contents.date')</th>
                                            <th>@lang('contents.particular')</th>
                                            <th class="text-right">@lang('contents.debit')</th>
                                            <th class="text-right">@lang('contents.credit')</th>
                                            <th class="text-right">@lang('contents.balance')</th>
                                            <th class="text-right print-none">@lang('contents.action')</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr>
                                            <td>1.</td>
                                            <td></td>
                                            <td>@lang('contents.opening_balance')</td>
                                            <td colspan="3" class="text-right">
                                                @php
                                                    $opening_balance = 0;
                                                    $balance = 0;
                                                @endphp
                                                @if($total_debit > $total_credit)
                                                    @php
                                                        $opening_balance = $party_balance - ($total_debit - $total_credit) ;
                                                        $balance = $opening_balance;
                                                    @endphp
                                                @else
                                                    @php
                                                        $opening_balance = $party_balance + ($total_credit - $total_debit) ;
                                                        $balance = $opening_balance;
                                                    @endphp
                                                @endif

                                                {{ number_format(abs($opening_balance), 2) }} {{ $opening_balance >= 0 ? 'Rec' : 'Pay' }}
                                            </td>
                                        </tr>
                                        @forelse($party_ledgers as $ledger)
                                            <tr>
                                                <td>{{ $loop->iteration + 1 }}</td>

                                                <td>{{ $ledger->date->format('d-M-Y') }}</td>

                                                <td style="max-width: 200px" class="text-wrap">
                                                    @if ($ledger->type === 'sale')
                                                        <p>@lang('contents.product') @lang('contents.sale')</p>
                                                    @elseif ($ledger->type === 'sale_return')
                                                        <p class="text-danger">@lang('contents.product') @lang('contents.return')</p>
                                                    @elseif($ledger->type === 'hire_sale')
                                                        <p>@lang('contents.hire_sale') (voucher number: {{ $ledger->voucher_no }})</p>
                                                    @else
                                                        <p>@lang('contents.due_management') ({{ $ledger->description }})</p>
                                                    @endif
                                                </td>

                                                <td class="text-right">
                                                    @if ($ledger->type === 'sale')
                                                        {{ number_format($ledger->grand_total, 2) }}
                                                    @elseif ($ledger->type === 'sale_return')
                                                        {{ number_format($ledger->paid, 2) }}
                                                    @elseif($ledger->type === 'hire_sale')
                                                        {{ number_format($ledger->hire_sale_grand_total, 2) }}
                                                    @elseif ($ledger->type === 'due_manage')
                                                        <p>{{ (($ledger->amount <= 0) ? number_format($ledger->amount, 2) : number_format(0, 2)) }}</p>
                                                    @endif
                                                </td>

                                                <td class="text-right">
                                                    @if ($ledger->type === 'sale')
                                                        {{ number_format($ledger->paid, 2) }}
                                                    @elseif ($ledger->type === 'sale_return')
                                                        {{ number_format($ledger->return_grand_total, 2) }}
                                                    @elseif($ledger->type === 'hire_sale')
                                                        {{ number_format($ledger->total_pay + $ledger->down_payment, 2) }}
                                                    @elseif ($ledger->type === 'due_manage')
                                                        <p>{{ (($ledger->amount > 0) ? number_format($ledger->amount, 2) : number_format(0, 2)) }}</p>
                                                    @endif
                                                </td>

                                                <td class="text-right">
                                                    @php
                                                        if ($ledger->type === 'sale') {
                                                            $balance += ($ledger->grand_total - $ledger->paid);
                                                        }
                                                        elseif ($ledger->type === 'sale_return') {
                                                            $balance += ($ledger->paid - $ledger->return_grand_total);
                                                        }
                                                        elseif ($ledger->type === 'hire_sale') {
                                                            $balance += ($ledger->hire_sale_grand_total - ($ledger->total_pay + $ledger->down_payment));
                                                        }
                                                        elseif ($ledger->type === 'due_manage'){
                                                            if ($ledger->amount >= 0) {
                                                                $balance -= $ledger->amount;
                                                            }else{
                                                                $balance += $ledger->amount;
                                                            }
                                                        }
                                                    @endphp
                                                    {{ number_format(abs($balance), 2) }} {{ $balance >= 0 ? 'Rec' : 'Pay' }}
                                                </td>

                                                <td class="text-right print-none">
                                                    @if ($ledger->invoice_no)
                                                        <a href="{{ route('invoice.generate', $ledger->invoice_no) }}" class="btn btn-primary btn-sm" title="View Invoice"
                                                           target="_blank">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @elseif ($ledger->type === 'sale_return')
                                                        <a href="{{ route('saleReturn.show', $ledger->id) }}" class="btn btn-primary btn-sm" title="View return details"
                                                           target="_blank">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @elseif ($ledger->type === 'hire_sale')
                                                        <a href="{{ route('hire-sale.show', $ledger->voucher_no) }}" class="btn btn-primary btn-sm" title="View return details"
                                                           target="_blank">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('customerDueManage.show', $ledger->id) }}" class="btn btn-primary btn-sm" title="View Due Manage"
                                                           target="_blank">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="15" class="text-center">@lang('contents.no_ledger_available')</td>
                                            </tr>
                                        @endforelse
                                        <tr>
                                            <th colspan="3" class="text-right">@lang('contents.total')</th>
                                            <td class="text-right">
                                                {{ number_format($total_debit, 2) }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($total_credit, 2) }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <h5 class="text-right">@lang('contents.closing_balance'): {{ ($party_balance <= 0) ? number_format(abs($party_balance), 2)." Payable" : number_format(abs($party_balance) , 2)." Receivable" }}</h5>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#js-example-basic-single').select2({
                width: 300,
                height: 100
            });
        });
    </script>
@endpush
