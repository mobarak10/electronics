@extends('layouts.user')
@section('title', $title)
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                <!-- vue component -->
                <supplier-customer-transaction :customers="{{ $customers }}" :suppliers="{{ $suppliers }}">
                </supplier-customer-transaction>
            </div>
        </div>
    </div>
@endsection
