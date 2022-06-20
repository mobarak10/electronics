@extends('layouts.user')

@section('title', $title)

@push('style')
    <link href="{{ asset('public/css/pos.css') }}" rel="stylesheet">
@endpush
@section('content')
    <!-- pos -->
    <hire-sale-component
        :old-hiresale="{{$old_hiresale}}"
        :warehouses="{{ $warehouses }}"
        :cashes="{{ $cashes }}"
        :bank-accounts="{{ $bank_accounts }}"
        :customers="{{ $customers }}">
    </hire-sale-component>
@endsection

