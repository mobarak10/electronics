@extends('layouts.admin')

@section('title', $title)

@push('style')
    <link href="{{ asset('public/css/stock.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card current-stock">
                <div class="card-header d-flex justify-content-between align-items-center print-none">
                    <h5 class="m-0">Stock report</h5>

                    <div class="btn-group" role="group" aria-label="Action area">
                        <a href="{{ route('admin.currentStockReport.currentStock') }}" class="btn btn-primary" title="Refresh">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <!-- search form start -->
                <div class="card-body p-2 print-none">
                    <form action="{{ route('admin.currentStockReport.currentStock') }}" method="GET" class="row">
                        <input type="hidden" name="search" value="1">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-10">
                                    <business-warehouse-component :businesses="{{ $businesses }}"></business-warehouse-component>
                                </div>
                                <div class="col-md-2" style="padding-top: 30px">
                                    <button type="submit" class="btn btn-primary" type="button" title="search">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- search form end -->

                    @if(request()->search)
                        {{-- report header --}}
                        <h1 class="text-center pt-5 pb-4 d-none d-print-block">জননী বস্ত্রালয়</h1>
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center py-print-4">
                                <h5 class="m-0">Total Stock Reports</h5>
                                <span class="d-none d-print-block">{{ date('d/m/Y') }}</span>
                                <div class="btn-group d-print-none" role="group" aria-label="Action area">
                                    <a href="#" onclick="window.print();" class="btn btn-warning" title="Print">
                                        <i class="fa fa-print" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Barcode</th>
                                            <th>Warehouse Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($products as $product)

                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}.
                                            </td>

                                            <td class="{{ ($product->stock_alert >= $product->warehouses->where('business_id', request()->businessId)->first()->stock->quantity) ? 'text-danger' : '' }}">
                                                {{ $product->name }}
                                            </td>

                                            <td>
                                                {{ \App\Helpers\Converter::convert($product->warehouses->where('business_id', request()->businessId)->first()->stock->quantity, $product->unit_code, 'd')['display'] }}
                                            </td>

                                            <td>
                                                {{ $product->barcode }}
                                            </td>

                                            <td>
                                                {{ $product->warehouses->where('business_id', request()->businessId )->first()->title }}
                                            </td>
                                        {{--  @if(request()->partyId)
                                                <td>
                                                    {{ $stock->product->supplier->name }}
                                                </td>
                                            @endif --}}
                                        </tr>

                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No Product available</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            <!-- paginate -->
                            <div class="float-right m-3">{{ $products->appends(Request::except('page'))->links() }}</div>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
