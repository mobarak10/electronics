@extends('layouts.user')

@section('title', 'Stock History')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="m-0">{{ $products->name }}</h5>
                        <small></small>
                    </div>

                    <div class="btn-group" role="group" aria-label="Action area">
                        <a href="{{ route('stock.show', $products->id) }}" class="btn btn-success mr-1" title="Refresh">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                        </a>

                        <a href="{{ route('stock.index') }}" class="btn btn-primary mr-1" title="All Stock">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div class="card col-md-12 mb-3">
                    <div class="card-header">
                        <h5>Sale History</h5>
                    </div>
                    <div class="card-body p-0">
                        <!-- cash ledger -->
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Quantity</th>
                                <th>Operator</th>
                                <th>Invoice</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($salesDetailsWarehouses as $saleDetails)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ \App\Helpers\Converter::convert($saleDetails->quantity, $saleDetails->product->unit_code, 'd')['display'] }}
                                    </td>
                                    <td>{{ $saleDetails->sale->operator->name }}</td>
                                    <td>
                                        <a href="{{ route('invoice.generate', $saleDetails->sale->invoice_no) }}">{{ $saleDetails->sale->invoice_no }}</a>
                                    </td>
                                    <td>{{ $saleDetails->created_at->format('d-M-Y') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- paginate -->
                <div class="float-right mb-2">
                    {{ $salesDetailsWarehouses->links() }}
                </div>

                <div class="card col-md-12 mb-3">
                    <div class="card-header">
                        <h5>Sale Return History</h5>
                    </div>
                    <div class="card-body p-0">
                        <!-- cash ledger -->
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Quantity</th>
                                <th>Operator</th>
                                <th>Invoice</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($saleReturnQuantity as $returnDetails)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ \App\Helpers\Converter::convert($returnDetails->quantity, $returnDetails->product->unit_code, 'd')['display'] }}
                                    </td>
                                    <td>{{ $returnDetails->sale->operator->name }}</td>
                                    <td>
                                        <a href="{{ route('invoice.generate', $returnDetails->sale->invoice_no) }}">{{ $returnDetails->sale->invoice_no }}</a>
                                    </td>
                                    <td>{{ $returnDetails->created_at->format('d-M-Y') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- paginate -->
                <div class="float-right mb-2">
                    {{ $saleReturnQuantity->links() }}
                </div>

                <div class="card col-md-12 mb-3">
                    <div class="card-header">
                        <h5>Purchase History</h5>
                    </div>
                    <div class="card-body p-0">
                        <!-- cash ledger -->
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Quantity</th>
                                {{--                                <th>Operator</th>--}}
                                <th>Voucher NO</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($purchaseQuantity as $purchaseDetails)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ \App\Helpers\Converter::convert($purchaseDetails->quantity, $purchaseDetails->product->unit_code, 'd')['display'] }}
                                    </td>
                                    <td>
                                        <a href="{{ route('purchase.show', $purchaseDetails->purchaseDetails->purchase->id) }}">{{ $purchaseDetails->purchaseDetails->purchase->voucher_no }}</a>
                                    </td>
                                    <td>{{ $purchaseDetails->created_at->format('d-M-Y') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- paginate -->
                <div class="float-right mx-2">
                    {{ $purchaseQuantity->links() }}
                </div>

                <div class="card col-md-12 mb-3">
                    <div class="card-header">
                        <h5>Purchase Return History</h5>
                    </div>
                    <div class="card-body p-0">
                        <!-- cash ledger -->
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Quantity</th>
                                <th>Operator</th>
                                <th>Voucher NO</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($purchaseReturnQuantity as $purchaseReturnDetails)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ \App\Helpers\Converter::convert($purchaseReturnDetails->quantity, $purchaseReturnDetails->product->unit_code, 'd')['display'] }}
                                    </td>
                                    <td>{{ $purchaseReturnDetails->purchaseReturnProduct->purchaseReturn->user->name }}</td>
                                    <td>
                                        <a href="{{ route('purchase.show', $purchaseReturnDetails->purchaseReturnProduct->purchaseReturn->id) }}">{{ $purchaseReturnDetails->purchaseReturnProduct->purchaseReturn->purchase->voucher_no }}</a>
                                    </td>
                                    <td>{{ $purchaseReturnDetails->created_at->format('d-M-Y') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- paginate -->
                <div class="float-right mx-2">
                    {{ $purchaseReturnQuantity->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
