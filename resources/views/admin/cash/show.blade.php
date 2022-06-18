@extends('layouts.admin')

@section('title', 'Cash')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="m-0">@lang('contents.cash_ledger')</h5>
                            <small></small>
                        </div>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('admin.cash.index') }}" class="btn btn-primary" title="All cash">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <!-- cash ledger -->
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('contents.date')</th>
                                    <th>@lang('contents.description')</th>
                                    <th class="text-right text-success">@lang('contents.debit') (@lang('contents.bdt'))</th>
                                    <th class="text-right text-danger">@lang('contents.credit') (@lang('contents.bdt'))</th>
                                    <th class="text-right">@lang('contents.balance') (@lang('contents.bdt'))</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalDebit = $totalCredit = 0.00;
                                @endphp

                                @foreach ($ledgers as $ledger)
                                    @php
                                        $totalDebit += $ledger->debit;
                                        $totalCredit += $ledger->credit;
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}.</td>
                                        <td>{{ $ledger->date->format('j M, Y') }}</td>
                                        <td title="{{ $ledger->description }}">
                                            <a href="{{ route('admin.cash.ledger-details', $ledger->id) }}" target="_blank">{{ $ledger->description }}</a>
                                        </td>
                                        <td class="text-right text-success">{{ number_format($ledger->debit, 2) }}</td>
                                        <td class="text-right text-danger">{{ number_format($ledger->credit, 2) }}</td>
                                        <td class="text-right">{{ number_format($ledger->balance, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="3" class="text-right">@lang('contents.total') </th>
                                    <th class="text-right text-success">{{ number_format($totalDebit, 2) }}</th>
                                    <th class="text-right text-danger">{{ number_format($totalCredit, 2) }}</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </tbody>
                        </table>

                        <!-- paginate -->
                        <div class="float-right mx-2">
                            {{ $ledgers->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
