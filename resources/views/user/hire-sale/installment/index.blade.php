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
                            <a href="{{ route('installmentCollection.index') }}" class="btn btn-primary print-none" title="Refresh">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>

                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning print-none">
                                <i aria-hidden="true" class="fa fa-print"></i>
                            </a>

                            <a href="{{ route('installmentCollection.create') }}" class="btn btn-primary print-none" title="New Collection">
                                <i class="fa fa-plus" aria-hidden="true"></i>
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
                                    <th style="min-width: 130px">@lang('contents.customer')</th>
                                    <th>@lang('contents.paid_by')</th>
                                    <th class="text-right">@lang('contents.amount')</th>
                                    <th class="text-right print-none">@lang('contents.action')</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($installments as $installment)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}.</td>
                                        <td>{{ $installment->date->format('d F, Y') }}</td>
                                        <td>
                                            {{ $installment->hireSale->voucher_no }}
                                        </td>
                                        <td>{{ $installment->customer->name ?? '' }}</td>
                                        <td>{{ $installment->paid_by }}</td>
                                        <td class="text-right">{{ number_format($installment->payment_amount, 2) }}</td>
                                        <td class="text-right print-none">
                                            <a href="{{ route('hire-sale.show', $installment->hireSale->voucher_no) }}" class="btn btn-sm btn-info"
                                               title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('installmentCollection.edit', $installment->id) }}" class="btn btn-sm btn-warning"
                                               title="View Details">
                                                <i class="fa fa-pencil"></i>
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
                                    <th class="text-right">{{ number_format($installments->sum('payment_amount'), 2) }}</th>
                                    <th>&nbsp;</th>
                                </tr>
                                </tbody>
                            </table>
                            <!-- paginate -->
                            <div class="float-right mx-2">
                                {{ $installments->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
