@extends('layouts.user')

@section('title', $title)

@push('style')
<link href="{{ asset('public/css/pos.css') }}" rel="stylesheet">
@endpush
@section('content')
    <!-- retail sale -->
    <sale-return-component :sale="{{ $sale }}" :cashes="{{ $cashes }}" :bank-accounts="{{ $bank_accounts }}" />
@endsection

