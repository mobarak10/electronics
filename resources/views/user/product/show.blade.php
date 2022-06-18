@extends('layouts.user')

@section('title', __('contents.product'))

@section('content')
    <div class="container">
        <div class="card my-2">
            <h5 class="card-header text-center">
                @lang('contents.product_details')
            </h5>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-2">
                        <img src="https://via.placeholder.com/200x200" class="card-img img-thumbnail" alt="..." >
                    </div>
                    <div class="col-md-10">
                        <h5 class="card-title m-0">{{ $product->name }}</h5>
                        <p class="card-text m-0"><strong>@lang('contents.unit'):</strong> {{ $product->unit->description }}</p>
                        <p class="card-text m-0"><strong>@lang('contents.stock_alert'):</strong> {{ (int) $product->stock_alert }}</p>
                        <p class="card-text m-0"><strong>@lang('contents.description'): </strong>{{ $product->description }}</p>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header text-center">
                                @lang('contents.basic_information')
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">@lang('contents.brand_name'): {{ $product->brand->name }}</li>
                                    <li class="list-group-item">@lang('contents.category_name'): {{ $product->category->name }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header text-center">
                                @lang('contents.price_information')
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">@lang('contents.purchase_price'): {{ $product->purchase_price }}</li>
                                    <li class="list-group-item">@lang('contents.sale_price'): {{ $product->sale_price }}</li>
                                    <li class="list-group-item">@lang('contents.wholesale_price'): {{ $product->wholesale_price }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header text-center">
                                @lang('contents.quantity')
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    @forelse($product->warehouses as $warehouse)
                                    <li class="list-group-item">{{ $warehouse->title }} : {{ $warehouse->display_quantity }}</li>
                                    @empty
                                    <li class="list-group-item text-center">@lang('contents.not_stored')</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <small class="text-muted">@lang('contents.last') @lang('contents.update'): {{ $product->updated_at->diffForHumans() }}</small>
            </div>
    </div>
@endsection
