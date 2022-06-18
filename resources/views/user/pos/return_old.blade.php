@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="card">
            {{-- header --}}
            <div class="card-header text-center d-flex justify-content-between">
                @lang('contents.sale_return')
                <div>
                    <a href="{{ route('pos.index') }}" class="btn btn-primary" title="Back to sale list">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i> &nbsp; Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <table class="table table-borderless table-sm">
                            <tbody>
                            <tr>
                                <td>@lang('contents.invoice_no')</td>
                                <td>{{ $sale->invoice_no }}</td>
                            </tr>
                            <tr>
                                <td>@lang('contents.date')</td>
                                <td>{{ $sale->created_at->format('d F, Y') }}</td>
                            </tr>
                            <tr>
                                <td>@lang('contents.customer_name')</td>
                                <td>{{ $sale->customer->name }} ({{ $sale->customer->phone ?? 'No Phone Number' }})</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <h5 class="text-center">@lang('contents.order_summary')</h5>
                        <table class="table table-borderless table-sm">
                            <thead>
                            <tr class="border-top border-bottom border-dark">
                                <th scope="col">#</th>
                                <th scope="col">@lang('contents.name')</th>
                                <th scope="col">@lang('contents.quantity')</th>
                                <th scope="col" class="text-right">@lang('contents.price')</th>
                                <th scope="col" class="text-right">@lang('contents.line_total')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sale->saleDetails as $details)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}.</th>
                                    <td>{{ $details->product->name }}</td>
                                    <td>{{ $details->total_quantity_in_format['display'] }}</td>
                                    <td class="text-right">{{ $details->sale_price }} /
                                        {{ array_last($details->total_quantity_in_format['labels']) }}</td>
                                    <td class="text-right">{{ number_format($details->line_total, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="border-top border-dark">
                                <td colspan="4" class="text-right">@lang('contents.subtotal'):</td>
                                <td colspan="1" class="text-right border-bottom border-dark"
                                    style="border-style: dashed !important;">{{ $sale->subtotal }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right">@lang('contents.vat')({{ $sale->vat }}%):</td>
                                <td colspan="1" class="text-right border-bottom border-dark"
                                    style="border-style: dashed !important;">
                                    {{ number_format($calculated_amount['vat'], 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right">@lang('contents.total'):</td>
                                <td colspan="1" class="text-right border-bottom border-dark"
                                    style="border-style: dashed !important;">
                                    {{ number_format($calculated_amount['total'], 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right">@lang('contents.discount'):</td>
                                <td colspan="1" class="text-right border-bottom border-dark"
                                    style="border-style: dashed !important;">
                                    {{ number_format($calculated_amount['discount'], 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right">@lang('contents.grand_total'):</td>
                                <td colspan="1" class="text-right border-bottom border-dark"
                                    style="border-style: dashed !important;">
                                    {{ number_format($calculated_amount['grand_total'], 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right">@lang('contents.paid'):</td>
                                <td colspan="1" class="text-right border-bottom border-dark"
                                    style="border-style: dashed !important;">
                                    {{ number_format($calculated_amount['paid'], 2) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    @forelse($sale->saleReturns as $sale_return)
                        <div class="col-md-12">
                            <h5 class="text-center">Return History
                                ({{ $sale_return->created_at->format('d F, Y h:i:m a') }})
                            </h5>
                            <table class="table table-borderless table-sm">
                                <thead>
                                <tr class="border-top border-bottom border-dark">
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('contents.name')</th>
                                    <th scope="col">@lang('contents.quantity')</th>
                                    <th scope="col" class="text-right">@lang('contents.price')</th>
                                    <th scope="col" class="text-right">@lang('contents.line_total')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sale_return->returnProducts as $return_product)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}.</th>
                                        <td>{{ $return_product->product->name }}</td>
                                        <td>{{ $return_product->total_return_quantity_in_unit['display'] }}</td>
                                        <td class="text-right">{{ $return_product->return_price }} /
                                            {{ array_last($return_product->product->product_unit_labels) }}</td>
                                        <td class="text-right">
                                            {{ number_format($return_product->return_product_price_total, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="border-top border-dark">
                                    <td colspan="4" class="text-right">@lang('contents.subtotal'):</td>
                                    <td colspan="1" class="text-right border-bottom border-dark"
                                        style="border-style: dashed !important;">
                                        {{ number_format($sale_return->return_product_price_subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right">Adjustment:</td>
                                    <td colspan="1" class="text-right border-bottom border-dark"
                                        style="border-style: dashed !important;">
                                        {{ number_format($sale_return->adjustment, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right">@lang('contents.total'):</td>
                                    <td colspan="1" class="text-right border-bottom border-dark"
                                        style="border-style: dashed !important;">
                                        {{ number_format($sale_return->return_product_price_total, 2) }}</td>
                                </tr>
                                @if($sale_return->adjust_to_customer_balance)
                                    <tr>
                                        <td colspan="5" class="text-right">
                                            Adjusted to customer balance<br>
                                            <span class="small">(Customer balance was BDT{{ number_format($sale_return->customer_balance, 2) }})</span>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="4" class="text-right">
                                            @lang('contents.paid'): <br>
                                            <span class="small">(From
                                        {{ ($sale_return->paid_from === 'cash') ? $sale_return->cash->title : 'Bank' }}
                                        by {{ $sale_return->user->name }})</span>
                                        </td>
                                        <td colspan="1" class="text-right border-bottom border-dark"
                                            style="border-style: dashed !important;">
                                            {{ number_format($sale_return->return_product_price_total, 2) }}</td>
                                    </tr>
                                @endif


                                @if($sale_return->note)
                                    <tr>
                                        <td colspan="2" class="text-right">Note:</td>
                                        <td colspan="3">{{ $sale_return->note }}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    @empty
                    @endforelse
                    <sale-return-component :sale="{{ $sale }}" :extras="{{ $extras }}"></sale-return-component>
                </div>
            </div>
        </div>
    </div>
@endsection
