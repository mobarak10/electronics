@extends('layouts.user')
@section('title', $title)
@section('content')
    <!-- purchase create -->
    <purchase-create-component
        :warehouses="{{ $warehouses }}"
        :products="{{ $products }}"
        :cashes="{{ $cashes }}"
        :lang="{{ json_encode($lang) }}"
        :parties="{{ $parties }}"
        :bank-accounts="{{ $bank_accounts }}"
        :old-purchase="{{ $old_purchase }}"
    >
    </purchase-create-component>
@endsection
