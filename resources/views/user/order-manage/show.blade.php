@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="card my-2">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="m-0">Order Management</h5>
                <div class="btn-group" role="group" aria-label="Action area">
                    <a href="{{ route('orderManagement.index') }}" class="btn btn-primary" title="All Order">
                        <i class="fa fa-list" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12 text-center">
                        <h5 class="card-title m-0"><strong>Order Number:</strong> {{ $order->order_no }}</h5>
                        <p class="card-text m-0"><strong>Party Name:</strong> {{ $order->customers->name }}</p>
                        <p class="card-text m-0"><strong>{{ ($order->customers->phone) ? 'Party Phone:' : '' }}</strong> {{ ($order->customers->phone) }}</p>
                        <p class="card-text m-0"><strong>SR Name:</strong> {{ $order->users->name }}</p>
                        <p class="card-text m-0"><strong>Date</strong> {{ $order->created_at->format('d Y-M') }}</p>
                        <p class="card-text m-0"><strong>Status:</strong> {{ ($order->status) ? 'Delivered' : 'Pending' }}</p>
                        <hr>
                        <hr>

                        <order-confirm-component :order="{{ $order }}" :total_price="{{ $total_price }}" :users="{{ $users }}"></order-confirm-component>
                    </div>
                </div>
            </div>
    </div>
@endsection
