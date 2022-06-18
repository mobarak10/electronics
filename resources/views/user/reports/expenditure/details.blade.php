@extends('layouts.user')

@section('title', __('contents.reports'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 py-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">@lang('contents.expenditure_report')</h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('contents.expenditure_head')</th>
                                <th>@lang('contents.spender')</th>
                                <th>@lang('contents.from')</th>
                                <th class="text-right">@lang('contents.amount') (BDT)</th>
                            </tr>
                            </thead>

                            <tbody>
                            @php 
                            $total = 0.00;
                            @endphp

                            @forelse($records as $key => $record)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>
                                        {{ $record->glAccountHead->name }}
                                    </td>
                                    <td>{{ $record->user->name }}</td>
                                    <td>
                                        @if($record->cash_id != null)
                                            Cash <small>({{ $record->cash->title }})</small>
                                        @elseif($record->bank_id != null)
                                            Bank <small>({{ $record->bankAccount->account_name }})</small>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @php 
                                        $total += $record->amount;
                                        @endphp

                                        {{ number_format($record->amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No Records Available.</td>
                                </tr>
                            @endforelse
                                <tr>
                                    <th colspan="4" class="text-right">@lang('contents.total')</th>
                                    <th class="text-right">{{ number_format($total, 2) }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
