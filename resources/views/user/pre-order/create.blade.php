@extends('layouts.user')

@section('title', $title)

@push('style')
    <link href="{{ asset('public/css/pos.css') }}" rel="stylesheet">
@endpush
@section('content')
    <!-- retail sale -->
    <pre-order-create
        :warehouses="{{ $warehouses }}"
        :lang="{{ json_encode($lang) }}"
        :customer-type="{{ json_encode($customer_type) }}"
        :cashes="{{ $cashes }}"
        :customers="{{ $customers }}"
        :bank-accounts="{{ $bank_accounts }}"
    />
@endsection
