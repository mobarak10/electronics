@extends('layouts.user')

@section('title', $title)
@section('content')
    <div class="container">
        <div class="accordion" id="product-transfer">
            <div class="col-md-12 py-3">
                <h1 class="text-center pt-5 pb-4 d-none d-print-block">Maxsop</h1>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">All Transfer Product</h5>
                        <span class="d-none d-print-block">{{ date('d/m/Y') }}</span>
                        <div class="action-area print-none">
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>
                            <a href="{{ route('productTransfer.create') }}" class="btn btn-primary" title="Transfer Product">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>From Warehouse</th>
                                <th>Product Name</th>
                                <th>To Warehouse</th>
                                <th>Quantity</th>
                                <th>Operator</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse($transferProduct as $product)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $product->fromWarehouse->title }}</td>
                                <td>{{ $product->product->name }}</td>
                                <td>{{ $product->toWarehouse->title }}</td>
                                <td>
                                    {{ \App\Helpers\Converter::convert($product->quantity, $product->product->unit_code, 'd')['display'] }}
                                </td>
                                <td>{{ $product->operator->name }}</td>
                                <td>{{ $product->created_at->format('d/m/Y') }}</td>

{{--                                <td class="text-right print-none">--}}
{{--                                    <a href="{{ route('damage.edit', $stock->id) }}" class="btn btn-primary" title="add damage product.">--}}
{{--                                        <i class="fa fa-pencil" aria-hidden="true"></i>--}}
{{--                                    </a>--}}

{{--                                </td>--}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No product transfer available</td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>
                <!-- paginate -->
                <div class="float-right mx-2">
                    {{ $transferProduct->links() }}
                </div>

            </div>
        </div>
    </div>
    </div>
@endsection
