@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
{{--                <create-product-component :suppliers="{{ $suppliers }}" :units="{{ $units }}" :warehouses="{{ $warehouses }}"></create-product-component>--}}
                <update-product-component :product="{{ $product  }}" :suppliers="{{ $suppliers }}" :units="{{ $units }}" :warehouses="{{ $warehouses }}" :extras="{{ $extras }}"></update-product-component>
            </div>
        </div>
    </div>
@endsection
