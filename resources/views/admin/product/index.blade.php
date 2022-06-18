@extends('layouts.admin')

@section('title', __('contents.product'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                <h1 class="text-center pt-5 pb-4 d-none d-print-block">জননী বস্ত্রালয়</h1>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.product_records')</h5>
                        @if(Route::currentRouteName() == 'admin.product.search')
                            <small>Found {{ $products->total() }} products according to your search</small>
                        @endif

                        <span class="d-none d-print-block">{{ date('d-m-Y') }}</span>

                        <div class="action-area print-none" aria-label="Action area">
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>
                            <a href="{{ route('admin.product.index') }}" class="btn btn-info" title="Refresh">
                                <i class="fa fa-refresh"></i>
                            </a>
                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#product-search-form">
                                <i class="fa fa-search"></i>
                            </button>
                            <a href="{{ route('admin.product.create') }}" class="btn btn-primary" title="Create new product">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="collapse {{ (Route::currentRouteName() == 'admin.product.search') ? 'show' : '' }}" id="product-search-form">
                            <div class="card-body">
                                <form action="{{ route('admin.product.search') }}" method="GET">
                                    <product-search-company-brand-category 
                                        :suppliers="{{ $suppliers }}"
                                        :categories="{{ $categories }}"
                                        @isset($searched_query)
                                        :searched-query="{{ $searched_query }}"
                                        @endisset
                                    ></product-search-company-brand-category>
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
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('contents.code')</th>
                                    <th>@lang('contents.barcode')</th>
                                    <th>@lang('contents.name')</th>
                                    <th class="text-right">@lang('contents.purchase_price')</th>
                                    <th class="text-right">@lang('contents.wholesale_price')</th>
                                    <th class="text-right">@lang('contents.retail_price')</th>
                                    <th class="text-right print-none">@lang('contents.action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}.</td>
                                        <td>{{ $product->code }}</td>
                                        <td>{{ $product->barcode }}</td>
                                        <td><a href="{{ route('admin.product.show', $product->id) }}" title="{{ $product->name }}" target="_blank">{{ $product->name }}</a></td>
                                        <td class="text-right">{{ $product->purchase_price }}</td>
                                        <td class="text-right">{{ $product->wholesale_price }}</td>
                                        <td class="text-right">{{ $product->retail_price }}</td>
                                        <td class="text-right print-none">
                                            <a href="{{ route('admin.product.show', $product->id) }}" class="btn btn-primary" title="Show product information.">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-primary" title="Change cash information.">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>

                                            <a href="{{ route('admin.product.index') }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, You want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $product->id }}').submit();} else {event.preventDefault();}">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>

                                            <form action="{{ route('admin.product.destroy', $product->id) }}" method="post" id="delete-form-{{ $product->id }}" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No product available</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- paginate -->
                        <div class="float-right mx-2">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
