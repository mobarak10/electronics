@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
            <h1 class="text-center pt-5 pb-4 d-none d-print-block">জননী বস্ত্রালয়</h1>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.all_bank_transaction')</h5>
                        <span class="d-none d-print-block">10/5/20</span>
                        <div class="action-area print-none" role="group" aria-label="Action area">
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning print-none"><i aria-hidden="true" class="fa fa-print"></i></a>
                            <a href="{{ route('balanceTransfer.create') }}" class="btn btn-primary" title="New transaction">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('contents.date')</th>
                                <th>@lang('contents.from')</th>
                                <th>@lang('contents.to')</th>
                                <th>@lang('contents.cheque_no')</th>
                                <th>@lang('contents.issue_date')</th>
                                <th>@lang('contents.amount')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ $transaction->transfer_date }}</td>
                                    <td>{{ $transaction->transfer_from }}</td>
                                    <td>{{ $transaction->transfer_to }}</td>
                                    <td>{{ $transaction->cheque_no }}</td>
                                    <td>{{ $transaction->cheque_issue_date }}</td>
                                    <td title="{{ $transaction->note }}">{{ $transaction->amount }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No transaction available</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <!-- paginate -->
                        <div class="float-right mx-2">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
