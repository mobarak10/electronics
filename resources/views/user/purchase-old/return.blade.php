@extends('layouts.user')

@section('title', __('contents.purchase'))

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="m-0">Purchase Return</h5>
                <div>
                    <a href="{{ route('purchase.index') }}" class="btn btn-primary" title="All cash">
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
                                <td>Voucher No</td>
                                <td>{{ $purchase->voucher_no }}</td>
                            </tr>
                            <tr>
                                <td>@lang('contents.date')</td>
                                <td>{{ $purchase->created_at->format('d F, Y') }}</td>
                            </tr>
                            <tr>
                                <td>Supplier Name</td>
                                <td>{{ $purchase->party->name }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12">
                        <h5 class="text-center">Purchase Summary</h5>
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
                            @foreach($purchase->details as $details)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}.</th>
                                    <td>{{ $details->product->name }}</td>
                                    <td>{{ $details->purchase_total_quantities_in_unit['display'] }}</td>
                                    <td class="text-right">{{ $details->purchase_price }}/{{ array_last($details->product->product_unit_labels) }}</td>
                                    <td class="text-right">{{ $details->line_total }}</td>
                                </tr>
                            @endforeach
                            <tr class="border-top border-dark">
                                <td colspan="4" class="text-right">Subtotal</td>
                                <td colspan="1" class="text-right border-bottom border-dark" style="border-style: dashed !important;">{{ $purchase->subtotal }}</td>
                            </tr>
                            <tr class="border-top border-dark">
                                <td colspan="4" class="text-right">Discount</td>
                                <td colspan="1" class="text-right border-bottom border-dark" style="border-style: dashed !important;">{{ $purchase->discount }}</td>
                            </tr>
                            <tr class="border-top border-dark">
                                <td colspan="4" class="text-right">Total</td>
                                <td colspan="1" class="text-right border-bottom border-dark" style="border-style: dashed !important;">{{ $purchase->subtotal - $purchase->discount }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>

                    @forelse($purchase->purchaseReturns as $purchase_return)
                    <div class="col-md-12">
                        <h5 class="text-center">Return History ({{ $purchase_return->created_at->format('d F, Y h:m:i A') }})</h5>
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
                            @foreach($purchase_return->purchaseReturnProducts as $return_product)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}.</th>
                                    <td>{{ $return_product->product->name }}</td>
                                    <td>{{ $return_product->total_return_product_quantity_in_unit['display'] }}</td>
                                    <td class="text-right">{{ $return_product->return_price }}/{{ array_last($return_product->product->product_unit_labels) }}</td>
                                    <td class="text-right">{{ $return_product->purchase_return_product_line_total }}</td>
                                </tr>
                            @endforeach
                            <tr class="border-top border-dark">
                                <td colspan="4" class="text-right">@lang('contents.subtotal'):</td>
                                <td colspan="1" class="text-right border-bottom border-dark" style="border-style: dashed !important;">{{ $purchase_return->purchase_return_subtotal }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right">Adjustment:</td>
                                <td colspan="1" class="text-right border-bottom border-dark" style="border-style: dashed !important;">{{ $purchase_return->adjustment }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right">@lang('contents.total'):</td>
                                <td colspan="1" class="text-right border-bottom border-dark" style="border-style: dashed !important;">{{ $purchase_return->purchase_return_total }}</td>
                            </tr>
                                <tr>
                                    <td colspan="2" class="text-right">Note: </td>
                                    <td colspan="3">TODO</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                        @empty
                    @endforelse
                    <product-purchase-return-container-component :purchase="{{ $purchase }}"></product-purchase-return-container-component>
                </div>
            </div>
        </div>
    </div>

@endsection
