@extends('layouts.admin')

@section('title', $title)

@push('style')
    <link href="{{ asset('public/css/payment.css') }}" rel="stylesheet">
@endpush
@section('content')
    <!--- Payment -->
    <section class="payment">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h5 class="mb-0">Payment</h5>
                    </div>
                    <div class="col-4 text-right">
                        <a class="btn btn-primary" href="{{ route('admin.pos.create') }}"><i class="fa fa-angle-double-left"></i>Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <pos-checkout-component></pos-checkout-component>
            </div>
        </div>
    </section>
    <!-- End of the payment -->
@endsection
