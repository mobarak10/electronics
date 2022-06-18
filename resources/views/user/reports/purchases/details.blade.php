@extends('layouts.user')

@section('title', __('contents.reports'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 py-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">@lang('contents.purchase_report')</h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('contents.voucher_no')</th>
                                <th>@lang('contents.supplier')</th>
                                <th class="text-right">@lang('contents.grand_total')</th>
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
                                        <a href="{{ route('purchase.show', $record->id) }}" target="_blank">
                                            {{ $record->voucher_no }}
                                        </a>
                                    </td>
                                    <td>{{ $record->party->name }}</td>
                                    <td class="text-right">
                                        @php 
                                        $total += $record->getGrandTotalAttribute();
                                        @endphp

                                        {{ number_format($record->getGrandTotalAttribute(), 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No Records Available.</td>
                                </tr>
                            @endforelse
                                <tr>
                                    <th colspan="3" class="text-right">@lang('contents.total')</th>
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
