@extends('layouts.user')
@section('title', $title)
@push('style')
<link href="{{ asset('public/css/pos.css') }}" rel="stylesheet">
@endpush
@section('content')
<!-- retail sale -->
<retail-sale
    :warehouses="{{ $warehouses }}"
    :lang="{{ json_encode($lang) }}"
    :cashes="{{ $cashes }}"
    :customers="{{ $customers }}"
    :bank-accounts="{{ $bank_accounts }}"
    :customer-type="{{ json_encode($customer_type) }}"
/>
@endsection
