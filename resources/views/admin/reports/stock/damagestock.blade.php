@extends('layouts.admin')

@section('title', $title)

@push('style')
    <link href="{{ asset('public/css/stock.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card damage-stock">
                <div class="card-header d-flex justify-content-between align-items-center print-none">
                    <h5 class="m-0">Damage Stock</h5>

                    <div class="btn-group" role="group" aria-label="Action area">
                        <a href="{{ route('admin.damageStockReport.damageStock') }}" class="btn btn-primary" title="Refresh">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <!-- search form start -->
                <div class="card-body p-2 print-none">
                    <form action="#" method="GET" class="row">
                        <input type="hidden" name="search" value="1">

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-10">
                                    <business-warehouse-component :businesses="{{ $businesses }}"></business-warehouse-component>
                                </div>
                                <div class="input-group col-md-2" style="padding-top: 30px">
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="m-0">Damage Stock Reports</h5>
                            <span class="d-none d-print-block">{{ date('d/m/Y') }}</span>
                            <div class="btn-group print-none" role="group" aria-label="Action area">
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
                                        <th>Code</th>
                                        <th>Warehouse Name</th>
                                        <th>Damage Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($damage_stock as $stock)

                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}.
                                        </td>

                                        <td>
                                            {{ $stock->product->name }}
                                        </td>

                                        <td>
                                            {{ \App\Helpers\Converter::convert($stock->quantity, $stock->product->getUnitCodeAttribute(), 'd')['display'] }}
                                        </td>

                                        <td>
                                            {{ $stock->product->code }}
                                        </td>

                                        <td>
                                            {{ $stock->warehouses->title }}
                                        </td>

                                        <td>
                                            {{ $stock->created_at->format('d-m-Y') }}
                                        </td>
                                    </tr>

                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No damage product available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        <!-- paginate -->
                        <div class="float-right m-3">{{ $damage_stock->appends(Request::except('page'))->links() }}</div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
