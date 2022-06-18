@extends('layouts.user')

@section('title', 'Product Details')

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
                        <p class="card-text m-0"><strong>@lang('contents.model'):</strong> {{ $product->model }}</p>
                        <p class="card-text m-0"><strong>@lang('contents.unit'):</strong> {{ $product->unit->description }}</p>
                        <p class="card-text m-0"><strong>@lang('contents.stock_alert'):</strong> {{ (int) $product->stock_alert }}</p>
                        <p class="card-text m-0"><strong>@lang('contents.description'): </strong>{{ $product->description }}</p>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header text-center">
                                Sale Information
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Total Product Sale: {{ \App\Helpers\Converter::convert($product->saleDetailsWarehouse->sum('quantity'), $product->unit_code, 'd')['display'] }} </li>
                                    <li class="list-group-item">Total Sale Price: {{ $product->saleDetails->sum('line_total') }} (BDT)</li>
                                    <li class="list-group-item">
                                        Invoice Number:
                                        @foreach($salesDetailsWarehouses as $saleDetails)
                                            <a href="{{ route('invoice.generate', $saleDetails->sale->invoice_no) }}">{{ $saleDetails->sale->invoice_no }}</a>
                                        @endforeach
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header text-center">
                                Sale Return Information
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Total Product Return: {{ \App\Helpers\Converter::convert($product->saleReturnQuantity->sum('quantity'), $product->unit_code, 'd')['display'] }}</li>
                                    <li class="list-group-item">Total Return Price: {{ ($product->retail_price) * ($product->saleReturnQuantity->sum('quantity')) }} (BDT)</li>
                                    <li class="list-group-item">
                                        Invoice Number:
                                        @foreach($saleReturnQuantity as $saleReturn)
                                            <a href="{{ route('invoice.generate', $saleReturn->sale->invoice_no) }}">{{ $saleReturn->sale->invoice_no }}</a>
                                        @endforeach
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header text-center">
                                Purchase Information
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
{{--                                    @forelse($product->warehouses as $warehouse)--}}
{{--                                        <li class="list-group-item">{{ $warehouse->title }} : {{ $warehouse->display_quantity }}</li>--}}
{{--                                    @empty--}}
{{--                                        <li class="list-group-item text-center">@lang('contents.not_stored')</li>--}}
{{--                                    @endforelse--}}
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header text-center">
                                Purchase Return Information
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    {{--                                    @forelse($product->warehouses as $warehouse)--}}
                                    {{--                                        <li class="list-group-item">{{ $warehouse->title }} : {{ $warehouse->display_quantity }}</li>--}}
                                    {{--                                    @empty--}}
                                    {{--                                        <li class="list-group-item text-center">@lang('contents.not_stored')</li>--}}
                                    {{--                                    @endforelse--}}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

