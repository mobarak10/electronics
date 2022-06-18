@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">

            <!-- Profile -->
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="{{ asset(($media) ? $media->real_path : 'public/images/avatar.jpeg') }}" class="card-img-top" alt="{{ $customer->name }}">

                    <div class="card-body">
                        <h5 class="card-title">{{ $customer->name }}</h5>
                        <p class="card-text">
                            {{ $customer->description }} <br>
                            <small>Account created at <em>{{ $customer->created_at->format('j F, Y') }}</em></small>
                        </p>
                    </div>

                    <li class="list-group-item">
                        <small class="d-block">Email address</small>
                        {{ $customer->email }}
                    </li>

                    <ul class="list-group list-group-flush">
{{--                        <li class="list-group-item">--}}
{{--                            <small class="d-block">Type</small>--}}
{{--                            {{ config('coderill.customer_type')[$customer->type] }}--}}
{{--                        </li>--}}
{{--                        <li class="list-group-item">--}}
{{--                            <small class="d-block">Credit Limit</small>--}}
{{--                            {{ $customer->credit_limit }}--}}
{{--                        </li>--}}
                        @foreach ($customer->metas as $meta)
                            <li class="list-group-item">
                                <small class="d-block">{{ config('coderill.party.customer.meta')[$meta->meta_key] }}</small>
                                {{ $meta->meta_value }}
                            </li>
                        @endforeach

                        <li class="list-group-item">
                            <small class="d-block">Address</small>
                            {{ $customer->address }}
                        </li>
                    </ul>

                    <div class="card-body">
                        <a href="{{ route('customer.edit', $customer->id) }}" class="card-link">Change</a>
                        <a href="{{ route('customer.changeCustomerStatus', $customer->id) }}" class="card-link">
                        {{ ($customer->active) ? 'Inactive' : 'Active' }}
                    </a>
                    </div>
                </div>
            </div>
            <!-- End of the profile -->

            <!-- Details tab -->
		    <div class="col-md-8">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#summary" role="tab" aria-controls="summary" aria-selected="true">Summary</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#total-sale" role="tab" aria-controls="Total Sale" aria-selected="false">Total Sale</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#return" role="tab" aria-controls="Ledger" aria-selected="false">Total Return</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#due-management" role="tab" aria-controls="blank" aria-selected="false">Total Due Manage</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                        <div class="accordion" id="summary">

                        </div>
                    </div>
                    <!-- brand & category tab end -->

                    <!-- product tab start -->
                    <div class="tab-pane fade" id="total-sale" role="tabpanel" aria-labelledby="blank-tab">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>Invoice Number</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-right">Due</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <!-- product tab end -->

                    <!-- ledger tab start -->
                    <div class="tab-pane fade" id="return" role="tabpanel" aria-labelledby="ledger-tab">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Invoice Number</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                    </div>
                    <!-- ledger tab end -->

                    <!-- blank tab start -->
                    <div class="tab-pane fade" id="due-management" role="tabpanel" aria-labelledby="blank-tab">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Payment Type</th>
                                    <th>Date</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <!-- blank tab end -->
                </div>
            </div>
            <!-- Details tab end -->
        </div>
    </div>
@endsection
