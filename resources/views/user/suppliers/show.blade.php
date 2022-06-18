@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">

            <!-- Profile -->
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="{{ asset($media ? $media->real_path : 'public/images/avatar.jpeg') }}" class="card-img-top" alt="{{ $supplier->name }}">

                    <div class="card-body">
                        <h5 class="card-title">{{ $supplier->name }}</h5>
                        <p class="card-text">
                            {{ $supplier->description }} <br>
                            <small>Account created at <em>{{ $supplier->created_at->format('j F, Y') }}</em></small>
                        </p>
                    </div>

                    <li class="list-group-item">
                        <small class="d-block">Email address</small>
                        {{ $supplier->email }}
                    </li>

                    <li class="list-group-item">
                        <small class="d-block">Division</small>
                        {{ $supplier->division }}
                    </li>

                    <li class="list-group-item">
                        <small class="d-block">Zila</small>
                        {{ $supplier->district }}
                    </li>

                    <li class="list-group-item">
                        <small class="d-block">Upozila</small>
                        {{ $supplier->thana }}
                    </li>

                    <ul class="list-group list-group-flush">
                        @foreach ($supplier->metas as $meta)
                            <li class="list-group-item">
                                <small class="d-block">{{ config('coderill.party.supplier.meta')[$meta->meta_key] }}</small>
                                {{ $meta->meta_value }}
                            </li>
                        @endforeach

                        <li class="list-group-item">
                            <small class="d-block">Address</small>
                            {{ $supplier->address }}
                        </li>
                    </ul>

                    <div class="card-body">
                        <a href="{{ route('supplier.edit', $supplier->id) }}" class="card-link">Change</a>
                        <a href="{{ route('supplier.changeSuppliersStatus', $supplier->id) }}" class="card-link">{{ ($supplier->active) ? 'Inactive' : 'Active' }}</a>
                    </div>
                </div>
            </div>
            <!-- End of the profile -->

            <!-- Details tab -->
		    <div class="col-md-8">
                <ul class="nav nav-tabs" role="tablist">
        
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#supplier-purchases" role="tab" aria-selected="false">All purchases</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#supplier-blank" role="tab" aria-controls="blank" aria-selected="false">Blank</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- supplier payments tab start -->
                    <div class="tab-pane fade show active" id="supplier-purchases" role="tabpanel">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Voucher No</th>
                                <th>Amount</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($supplier->purchases as $purchase)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td title="Date">{{ $purchase->created_at->format('d F, Y') }}</td>
                                    <td title="Voucher No"><a href="{{ route('purchase.show', $purchase->id) }}" target="_blank">{{ $purchase->voucher_no ?? 'Details' }}</a></td>
                                    <td>{{ $purchase->grand_total }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No purchase history available</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- ledger tab end -->

                    <!-- blank tab start -->
                    <div class="tab-pane fade" id="supplier-blank" role="tabpanel" aria-labelledby="blank-tab">
                        Blank
                    </div>
                    <!-- blank tab end -->
                </div>
            </div>
            <!-- Details tab end -->
        </div>
    </div>
@endsection
