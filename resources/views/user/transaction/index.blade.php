@extends('layouts.user')

@section('title', 'Transaction')

@section('content')
    <div class="container">
        {{-- <div class="row">
            <div class="col-md-12">
                <div class="alert {{ ($total_balance < 0) ? 'alert-danger' : 'alert-success' }}">
                    <strong>@lang('contents.total_cash_bdt') {{ $total_balance }}</strong>
                </div>
            </div>
        </div> --}}

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
                        <h5 class="m-0">All Transaction</h5>
                        <div class="action-area print-none">
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-sm btn-warning"><i aria-hidden="true" class="fa fa-print"></i>
                            </a>

                            <a href="{{ route('transaction.index') }}" class="btn btn-sm btn-success" title="Refresh.">
                                <i class="fa fa-refresh"></i>
                            </a>

                            <button class="btn btn-sm btn-info" type="button" data-toggle="collapse" data-target="#transaction-search">
                                <i class="fa fa-search"></i>
                            </button>

                            <a href="{{ route('transaction.create') }}" class="btn btn-sm btn-primary" title="Create new transaction">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="collapse col-md-12 print-none" id="transaction-search">
                            <form action="{{ route('transaction.index') }}" method="GET">
                                <input type="hidden" name="search" value="1">

                                <div class="row">
                                    <div class="form-group col-md-5">
                                        <label for="from_date">From Date</label>
                                        <input type="date" class="form-control" name="from_date" value="{{ date('Y-m-d') }}" id="from_date">
                                    </div>

                                    <div class="form-group col-md-5">
                                        <label for="to_date">To Date</label>
                                        <input type="date" class="form-control" name="to_date" value="{{ date('Y-m-d') }}" id="to_date">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="">&nbsp;</label>
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fa fa-search"></i> &nbsp;
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <table class="table table-sm table-bordered">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Transaction Type</th>
                                <th>Date</th>
                                <th>Customer Name</th>
                                <th>Supplier Name</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right print-none">@lang('contents.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ ($transaction->transaction_from == 'supplier') ? 'Supplier To Customer' : 'Customer To Supplier' }}</td>
                                    <td>{{ $transaction->date->format('d F Y') }}</td>
                                    <td>{{ $transaction->customer->name }}</td>
                                    <td>{{ $transaction->party->name }}</td>
                                    <td class="text-right">{{ number_format($transaction->amount, 2) }}</td>
                                    <td class="text-right print-none">
                                        <a href="{{ route('transaction.show', $transaction->id) }}" class="btn btn-sm btn-primary" title="Transaction details.">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('transaction.edit', $transaction->id) }}" class="btn btn-sm btn-success" title="Change transaction information.">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('transaction.index') }}" class="btn btn-sm btn-danger" title="Trash" onClick="if(confirm('Are you sure, You want to move trashed this record?')){event.preventDefault();document.getElementById('delete-form-{{ $transaction->id }}').submit();} else {event.preventDefault();}">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>

                                        <form action="{{ route('transaction.destroy', $transaction->id) }}" method="post" id="delete-form-{{ $transaction->id }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="15" class="text-center">No transaction available</td>
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
