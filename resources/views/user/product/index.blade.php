@extends('layouts.user')

@section('title', __('contents.product'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 py-3">
                <div class="card">
                    <div class="d-none mt-2 text-center d-print-block">
                        <h5 class="mb-0 center" style="font-size: 25px"> <strong>{{ config('print.print_details.name') }}</strong> </h5>
                        <p class="mb-0 font-12">{{ config('print.print_details.address') }}</p>
                        <span class="mb-0 font-12">{{ config('print.print_details.mobile') }}</span>
                        <p class="mb-0" style="font-size: 15px">{{ Carbon\Carbon::now()->format('j F, Y h:i:s a') }}</p>
                    </div>
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.product_records')</h5>
                        @if(Route::currentRouteName() == 'product.search')
                            <small>Found {{ $products->total() }} products according to your search</small>
                        @endif

                        <span class="d-none d-print-block">{{ date('F j, Y') }}</span>

                        <div class="action-area print-none" aria-label="Action area">
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning">
                                <i aria-hidden="true" class="fa fa-print"></i>
                            </a>

                            <a href="{{ route('product.index') }}" class="btn btn-info" title="Refresh">
                                <i class="fa fa-refresh"></i>
                            </a>

{{--                            <a href="{{ route('product.export') }}" title="export excel" class="btn btn-success">--}}
{{--                                <i class="fa fa-file-excel-o" aria-hidden="true"></i>--}}
{{--                            </a>--}}

                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#product-search-form">
                                <i class="fa fa-search"></i>
                            </button>

                            <a href="{{ route('product.create') }}" class="btn btn-primary" title="Create new product">
                                <i class="fa fa-plus"></i>
                            </a>

                            <a href="{{ route('product.viewTrashed') }}" title="view trashed" class="btn btn-danger">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="collapse {{ (Route::currentRouteName() == 'product.search') ? 'show' : '' }}" id="product-search-form">
                            <div class="card-body">
                                <form action="{{ route('product.search') }}" method="GET">
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="warehouse">Warehouse</label>
                                            <select name="warehouse_id" id="warehouse" class="form-control">
                                                <option value="">All Warehouses</option>
                                                @foreach($warehouses as $warehouse)
                                                    <option {{ (isset($searched_query['warehouse_id']) AND $searched_query['warehouse_id'] == $warehouse->id) ? 'selected' : '' }} value="{{ $warehouse->id }}">{{ $warehouse->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="warehouse">Category</label>
                                            <select name="category_id" id="category-search" class="form-control">
                                                <option value="">All Category</option>
                                                @foreach($categories as $category)
                                                    <option {{ (isset($searched_query['category_id']) AND $searched_query['category_id'] == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="warehouse">Brand</label>
                                            <select name="brand_id" id="brand-search" class="form-control">
                                                <option value="">All Brand</option>
                                                @foreach($brands as $brand)
                                                    <option {{ (isset($searched_query['brand_id']) AND $searched_query['brand_id'] == $brand->id) ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" class="form-control" id="barcode" value="{{ $searched_query['barcode'] ?? '' }}" placeholder="PRDXXXX">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label>&nbsp;</label>
                                            <button class="btn btn-primary btn-block" type="submit">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                <tr>
                                    <th>@lang('contents.sl')</th>
                                    <th class="print-none">@lang('contents.barcode')</th>
                                    <th>@lang('contents.name')</th>
                                    <th>@lang('contents.brand')</th>
                                    <th>@lang('contents.category')</th>
                                    <th class="text-right">@lang('contents.purchase_price')</th>
                                    <th class="text-right">@lang('contents.sale_price')</th>
                                    <th class="text-right">@lang('contents.wholesale_price')</th>
                                    <th class="text-right print-none">@lang('contents.action')</th>
                                </tr>
                                </thead>

                                <tbody>

                                @php
                                    $serial_number = \App\Helpers\PaginatorHelper::paginatorHelper($products) + 1
                                @endphp

                                @forelse($products as $product)
                                    <tr>
                                        <td>{{ $serial_number++ }}.</td>
                                        <td>{{ $product->barcode }}</td>
                                        <td><a href="{{ route('product.show', $product->id) }}" title="{{ $product->name }}" target="_blank">{{ $product->name }}</a></td>
                                        <td>{{ $product->brand->name }}</td>
                                        <td>{{ $product->category->name }}</td>
                                        <td class="text-right">{{ $product->purchase_price }}</td>
                                        <td class="text-right">{{ $product->sale_price }}</td>
                                        <td class="text-right">{{ $product->wholesale_price }}</td>
                                        <td class="text-right print-none">
                                            <a href="{{ route('product.show', $product->id) }}" class="btn btn-sm btn-primary" title="Show product information.">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-primary" title="Change cash information.">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>

                                            <a href="{{ route('product.index') }}" class="btn btn-sm btn-danger" title="Trash" onClick="if(confirm('Are you sure, You want to move trashed this record?')){event.preventDefault();document.getElementById('delete-form-{{ $product->id }}').submit();} else {event.preventDefault();}">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>

                                            <form action="{{ route('product.destroy', $product->id) }}" method="post" id="delete-form-{{ $product->id }}" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="20" class="text-center">No product available</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- paginate -->
                        <div class="float-right mx-2">
                            {{ $products->appends(Request::except('page'))->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push("script")
    <script type="text/javascript">
        function openWin(url)
        {
            var myWindow=window.open(url,'','width=500,height=500');
        }

        $(document).ready(function() {
            $('#category-search').select2({
                width: 350,
                height: 100
            });
        });

        $(document).ready(function() {
            $('#brand-search').select2({
                width: 350,
                height: 100
            });
        });
    </script>
@endpush

