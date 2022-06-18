@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">

            <!-- Profile -->
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="{{ asset(($supplier->media) ? $supplier->media->real_path : 'public/images/avatar.jpeg') }}" class="card-img-top" alt="{{ $supplier->name }}">

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
                        <a href="{{ route('admin.supplier.edit', $supplier->id) }}" class="card-link">Change</a>
                        <a href="{{ route('admin.supplier.changeSuppliersStatus', $supplier->id) }}" class="card-link">{{ ($supplier->active) ? 'Inactive' : 'Active' }}</a>
                    </div>
                </div>
            </div>
            <!-- End of the profile -->

            <!-- Details tab -->
		    <div class="col-md-8">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#supplier-brand" role="tab" aria-controls="brand" aria-selected="true">Brand & Category</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#supplier-products" role="tab" aria-controls="Products" aria-selected="false">Products</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#supplier-purchases" role="tab" aria-selected="false">All purchases</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#supplier-payments" role="tab" aria-selected="false">Payments</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#supplier-blank" role="tab" aria-controls="blank" aria-selected="false">Blank</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="supplier-brand" role="tabpanel" aria-labelledby="brand-tab">
                        <div class="accordion" id="brandLists">
                            @forelse($supplier->brands as $brand)
                                <div class="card">
                                    <div class="card-header" id="heading-{{ $loop->iteration }}">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#brand-{{ $loop->iteration }}" aria-expanded="true" aria-controls="collapseOne">
                                                {{ $brand->name }}
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="brand-{{ $loop->iteration }}" class="collapse {{ ($loop->first) ? 'show' : '' }}" aria-labelledby="heading-{{ $loop->iteration }}" data-parent="#brandLists">
                                        <div class="card-body">
                                            <div class="list-group">
                                                @forelse($brand->categories as $category)
                                                    <p class="list-group-item list-group-item-action">
                                                    <span class="d-block {{ ($category->active) ? '' : 'text-danger' }}">{{ $category->name }}</span>
                                                        <small>{{ $category->description }}</small>
                                                    </p>
                                                @empty
                                                    <strong>Nothing to show.</strong>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <strong>Nothing to show.</strong>
                            @endforelse
                        </div>
                    </div>
                    <!-- brand & category tab end -->

                    <!-- product tab start -->
                    <div class="tab-pane fade" id="supplier-products" role="tabpanel" aria-labelledby="blank-tab">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th class="text-right">Purchase Price (BDT)</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($supplier->products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}.</td>
                                        <td title="{{ $product->description }}"><a href="{{ route('admin.product.show', $product->id) }}" target="_blank">{{ $product->name }}</a></td>
                                        <td>{{ $product->code }}</td>
                                        <td class="text-right">{{ number_format($product->purchase_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- product tab end -->

                    <!-- supplier payments tab start -->
                    <div class="tab-pane fade" id="supplier-purchases" role="tabpanel">
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
                                    <td title="Voucher No"><a href="{{ route('admin.purchase.show', $purchase->id) }}" target="_blank">{{ $purchase->voucher_no ?? 'Details' }}</a></td>
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

                    <!-- ledger tab start -->
                    <div class="tab-pane fade" id="supplier-payments" role="tabpanel">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Via</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($supplier->dueManages as $dueManage)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td title="Date">{{ $dueManage->date }}</td>
                                    <td title="Amount">{{ $dueManage->amount }}</td>
                                    <td>{{ ucfirst($dueManage->payment_type) }}</td>
                                    <td>{{ $dueManage->cash_id ? 'Cash' : 'Bank'  }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No payments history available</td>
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
