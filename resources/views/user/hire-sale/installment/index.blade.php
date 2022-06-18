@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center pt-5 pb-4 d-none d-print-block">ভাই ভাই ট্রেডার্স </h1>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">All Collection</h5>
                        <span class="d-none d-print-block">{{ date('d-m-Y') }}</span>

                        <div>
                            <a href="{{ route('hire-sale.index') }}" class="btn btn-primary print-none" title="Refresh">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>

                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning print-none">
                                <i aria-hidden="true" class="fa fa-print"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-sm">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>@lang('contents.date')</th>
                                    <th>@lang('contents.invoice_no')</th>
                                    <th>@lang('contents.customer')</th>
                                    <th>Paid By</th>
                                    <th class="text-right">Paid Amount</th>
                                    <th class="text-right print-none">@lang('contents.action')</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($installments as $installment)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}.</td>
                                        <td>{{ $installment->created_at->format('d F, Y') }}</td>
                                        <td>
                                            {{ $installment->hireSale->voucher_no }}
                                        </td>
                                        <td>{{ $installment->party->name }}</td>
                                        <td>{{ $installment->paid_by }}</td>
                                        <td class="text-right">{{ number_format($installment->payment_amount, 2) }}</td>
                                        <td class="text-right print-none">
                                            <a href="{{ route('hire-sale.show', 1) }}" class="btn btn-sm btn-info"
                                               title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No installment available.</td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <th colspan="5" class="text-right">Total</th>
                                    <th colspan="3">&nbsp;</th>
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
