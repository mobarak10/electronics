@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">

            <!-- Profile -->
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="{{ asset(($customer->media) ? $customer->media->real_path : 'public/images/avatar.jpeg') }}" class="card-img-top" alt="{{ $customer->name }}">

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
                        <a href="{{ route('admin.customer.edit', $customer->id) }}" class="card-link">Change</a>
                        <a href="{{ route('admin.customer.changeCustomerStatus', $customer->id) }}" class="card-link">
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
                            {{-- decleare variable --}}
                            @php
                                $total_sale = 0;
                                $total_return  = 0;
                            @endphp

                            @forelse($customer->sells as $sale)
                                @php
                                    // sale total with vat
                                    $total_sale += $sale->subtotal+($sale->subtotal*($sale->vat/100));

                                    // substract discount from total
                                    if ($sale->discount_type == 'flat') {
                                        $total_sale -= $sale->discount;
                                    }elseif($sale->discount_type == 'percentage'){
                                        $total_sale -= ($sale->discount / 100)*($sale->subtotal);
                                    }

                                @endphp

                                @forelse($sale->saleReturns as $return)
                                    @php
                                        // get total return price
                                        $total_return += $return->return_product_price_total;
                                    @endphp
                                @empty
                                @endforelse
                            @empty
                                <strong>Nothing to show.</strong>
                            @endforelse

                            <h4 style="margin-top: 10px">Total Sell with VAT: {{ $total_sale }} Taka</h4>
                            <h4>Total Due: {{ ($customer->balance < 0) ? 'Receivable' : 'Payable' }} {{ abs($customer->balance) }} Taka</h4>
                            <h4>Total Return: {{ $total_return }}</h4>
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
                                @php
                                    $sumtotal = 0;
                                    $duetotal = 0;
                                @endphp
                                @forelse($customer->sells as $sale)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}.</td>
                                        <td>{{ $sale->created_at->format('d F, Y') }}</td>
                                        <td>
                                            <a href="{{ route('invoice.generate', $sale->invoice_no) }}"
                                               title="View Invoice" target="_blank">
                                                {{ $sale->invoice_no }}
                                            </a>
                                        </td>
                                        @php
                                            /**
                                            * Calculate Total
                                            */
                                            $vat = $sale->vat;
                                            $subtotal = $sale->subtotal;
                                            $total = $subtotal + (($subtotal * $vat) / 100);

                                            $discount = $sale->discount;
                                            if($sale->discount_type === 'flat'){
                                                $total -= $discount;
                                            }else{
                                                $total -= (($total * $discount) / 100);
                                            }

                                            $sumtotal += $total;
                                            $duetotal += $sale->due;

                                        @endphp
                                        <td class="text-right">
                                            {{ number_format($total, 2) }}
                                        </td>
                                        <td class="text-right">{{ $sale->due }}</td>
                                    </tr>
                                @empty

                                @endforelse
                                <tr>
                                    <td colspan="3" class="text-right">Total:</td>
                                    <td class="text-right">{{ $sumtotal }}</td>
                                    <td class="text-right">{{ $duetotal }}</td>
                                </tr>
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

                                @forelse($saleHasReturns as $sale_return)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('invoice.generate', $sale_return->invoice_no) }}"
                                               title="View Invoice" target="_blank">
                                                {{ $sale_return->invoice_no }}
                                            </a>
                                        </td>
                                        <td>{{ $sale_return->updated_at->format('d F, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No return available</td>
                                    </tr>
                                @endforelse
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
                                @forelse($customer->dueManages as $due)
                                    <tr>
                                        <td>{{ $loop->iteration }}.</td>
                                        <td>{{ ucfirst(trans($due->payment_type)) }}</td>
                                        <td>{{ $due->date }}</td>
                                        <td class="text-right">{{ number_format($due->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No due manage available</td>
                                    </tr>
                                @endforelse
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
