@extends('layouts.user')

@section('title', $title)

@push('style')
    <link href="{{ asset('public/css/pos.css') }}" rel="stylesheet">
@endpush
@section('content')
    <!-- retail sale -->
    <purchase-return-create :warehouses="{{ $warehouses }}"
                            :lang="{{ json_encode($lang) }}"
                            :cashes="{{ $cashes }}" 
                            :customers="{{ $parties }}"
                            :bank-accounts="{{ $bank_accounts }}"/>
@endsection
