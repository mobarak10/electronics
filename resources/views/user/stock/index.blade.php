@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="d-none mt-2 text-center d-print-block">
                        <h5 class="mb-0 center" style="font-size: 25px"> <strong>Haat Store</strong> </h5>
                        <p class="mb-0" style="font-size: 18px"><strong>Product Stock</strong></p>
                        <p class="mb-0" style="font-size: 15px">{{ Carbon\Carbon::now()->format('j F, Y h:i:s a') }}</p>
                    </div>
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0 print-none">@lang('contents.test.hi') @lang('contents.stock')</h5>

                        <div class="print-none" role="group" aria-label="Action area">
                            <button class="btn btn-info" type="button" title="Search product" data-toggle="collapse" data-target="#searchCollapse" aria-expanded="false" aria-controls="collapseSearch">
                                <i class="fa fa-search"></i>
                            </button>

                            <a href="{{ route('stock.index') }}" class="btn btn-primary" title="Refresh">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>

                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>

                            <a href="{{ route('damageStock.index') }}" class="btn btn-success" title="Show damages product.">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <!-- search form start -->
                        <div class="p-3 print-none {{ request()->search ? '' : 'collapse' }}" id="searchCollapse">
                            <form action="{{ route('stock.index') }}" method="GET">
                                <input type="hidden" name="search" value="1">

                                <div class="form-row">
                                    <div class="col-md-3 mb-3">
                                        <label for="category">Category</label>
                                        <select name="filter_by[category_id]" class="form-control" id="category">
                                            <option value="" selected>All Categories</option>

                                            @foreach($categories as $category)
                                                <option {{ isset(request()->filter_by['category_id']) ? (request()->filter_by['category_id'] == $category->id) ? 'selected' : '' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="category">Brand</label>
                                        <select name="filter_by[brand_id]" class="form-control" id="brand">
                                            <option value="" selected>All Brands</option>
                                            @foreach($brands as $brand)
                                                <option {{ isset(request()->filter_by['brand_id']) ? (request()->filter_by['brand_id'] == $brand->id) ? 'selected' : '' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="name">Product name</label>
                                        <input type="text" name="filter_by[name]" class="form-control" value="{{ isset(request()->filter_by['name']) ? request()->filter_by['name'] : '' }}" placeholder="Enter product name">
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label for="code">Product Code</label>
                                        <input type="text" name="filter_by[code]" class="form-control" value="{{ isset(request()->filter_by['code']) ? request()->filter_by['code'] : '' }}" placeholder="Enter product code">
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" name="filter_by[barcode]" class="form-control" value="{{ isset(request()->filter_by['barcode']) ? request()->filter_by['barcode'] : '' }}" placeholder="Enter product barcode">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="warehouse">Warehouse</label>
                                        <select name="warehouse_id" id="warehouse" class="form-control">
                                            <option value="">All Warehouses</option>
                                            @foreach($warehouses as $warehouse)
                                                <option {{ (request()->search AND request()->warehouse_id == $warehouse->id) ? 'selected' : '' }} value="{{ $warehouse->id }}">{{ $warehouse->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-3 text-right">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary" id="button-addon" title="search">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- search form end -->

                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th scope="col">SI</th>
                                <th scope="col" style=" max-width: 200px;">Product Name</th>
                                <th scope="col">Category</th>
                                <th scope="col" style=" min-width: 150px;">Brand</th>
                                <th scope="col" class="text-right" style=" min-width: 150px;">Quantity (Unit)</th>
                                <th scope="col" class="text-right">Quantity (KG)</th>
                                <th scope="col" class="text-right">Quantity (Bag)</th>
                                <th scope="col" class="text-right">PPU</th>
                                <th scope="col" class="text-right">PPU Value</th>
                                <th scope="col" class="text-right">SPU</th>
                                <th scope="col" class="text-right">SPU Value</th>
                                <th scope="col" class="text-right print-none">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $total_purchase_price = 0.00;
                                $total_sale_price = 0;
                                $total_bag = 0;
                                $total_kg= 0;
                                $bag = 0;
                            @endphp
                            @forelse($products as $product)
                                <tr>
                                    <td scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $product->name ?? '' }}</td>

                                    <td>
                                        {{ $product->category->name ?? '' }}
                                    </td>

                                    <td>
                                        {{ $product->brand->name ?? '' }}
                                    </td>


                                    <td class="text-right">
                                        {{ ($product->total_product_quantity_warehouse_wise > 0) ? \App\Helpers\Converter::convert($product->total_product_quantity_warehouse_wise, $product->unit->code, 'd')['display'] : '-' }}
                                    </td>

                                    <td class="text-right">
                                        @if($product->category->slug == 'feed')
                                            @php
                                                $unit_relation = explode('/', $product->unit->relation);
                                                $last_relation =  end($unit_relation);
                                                $multiply_number = $last_relation == 20 ? 50 : 25;
                                                $total_kg += $product->total_product_quantity_warehouse_wise * $multiply_number;
                                            @endphp
                                            {{ $product->total_product_quantity_warehouse_wise * $multiply_number. " KG" }}
                                        @elseif($product->category->slug == 'egg')
                                            {{ $product->total_product_quantity_warehouse_wise. " Pcs" }}
                                        @endif
                                    </td>

                                    <td class="text-right">
                                        @if($product->category->slug == 'feed')
                                            @php
                                                $total_bag += $product->total_product_quantity_warehouse_wise;
                                            @endphp
                                            {{ $product->total_product_quantity_warehouse_wise }}
                                        @endif
                                    </td>

{{--                                    <td class="text-right">--}}
{{--                                        @forelse($product->warehouses as $warehouse)--}}
{{--                                            @php--}}
{{--                                                $data = $warehouse->product_quantity_in_unit['relation'];--}}
{{--                                                $relation = explode('/', $data);--}}
{{--                                                $calculated_value = $relation[0] * $relation[1];--}}
{{--                                                $bag += $warehouse->stock->quantity / $calculated_value;--}}
{{--                                            @endphp--}}
{{--                                        @empty--}}
{{--                                        @endforelse--}}
{{--                                        {{ $bag }}--}}
{{--                                        @php--}}
{{--                                            $bag = 0;--}}
{{--                                        @endphp--}}
{{--                                    </td>--}}

                                    <td class="text-right">
                                        {{ $product->purchase_price }}
                                    </td>

                                    <td class="text-right">
                                        @php
                                            $total_purchase_price += $product->total_product_quantity_warehouse_wise * $product->purchase_price
                                        @endphp
                                        {{ $product->total_product_quantity_warehouse_wise * $product->purchase_price }}
                                    </td>

                                    <td class="text-right">
                                        {{ $product->dealer_price }}
                                    </td>

                                    <td class="text-right">
                                        @php
                                            $total_sale_price += $product->total_product_quantity_warehouse_wise * $product->dealer_price
                                        @endphp
                                        {{ $product->total_product_quantity_warehouse_wise * $product->dealer_price }}
                                    </td>

                                    <td class="text-right print-none">
                                        <div class="btn-group" role="group" aria-label="Action area">
                                            <a href="{{ route('stock.edit', $product->id) }}" class="btn btn-sm btn-primary mr-2" title="Edit product quantity.">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>

                                            <a href="{{ route('damageStock.edit', $product->id) }}" target="_blank" class="btn btn-sm btn-success" title="Add damages.">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No stock available</td>
                                </tr>
                            @endforelse
                            <tr>
                                <th colspan="5" class="text-right">@lang('contents.total') </th>
                                <th class="text-right">{{ $total_kg }}</th>
                                <th class="text-right">{{ $total_bag }}</th>
                                <th class="text-right">{{ number_format($total_purchase_price, 2) }}</th>
                                <th></th>
                                <th class="text-right">{{ number_format($total_sale_price, 2) }}</th>
                                <th></th>
                                <th class="print-none">&nbsp;</th>
                            </tr>
                            </tbody>
                        </table>
                        <!-- paginate -->
                        <div class="float-right m-3">{{ $products->links() }}</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
