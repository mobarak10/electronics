@extends('layouts.user')
@section('title', $title)
@section('content')
    <!-- pre order delivery -->
    <pre-order-delivery
        :pre-order="{{ $pre_order }}"
        :warehouses="{{ $warehouses }}"
    />
@endsection
