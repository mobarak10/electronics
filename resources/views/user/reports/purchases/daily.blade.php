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
                                <th>@lang('contents.period')</th>
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
                                        <a href="{{ route('report.purchases.details', $key) }}" target="_blank">
                                            {{ $record->first()->created_at->format('d F, Y')  }}
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        @php 
                                        $total += $record->sum('grand_total');
                                        @endphp

                                        {{ number_format($record->sum('grand_total')) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No Records Available.</td>
                                </tr>
                            @endforelse

                                <tr>
                                    <th colspan="2" class="text-right">@lang('contents.total')</th>
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
