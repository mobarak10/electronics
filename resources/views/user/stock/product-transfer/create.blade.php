@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <product-transfer-creat-component :warehouses="{{ $warehouses }}"></product-transfer-creat-component>
        </div>
    </div>
@endsection
