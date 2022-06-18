@extends('layouts.admin')

@section('title', $title)
@section('content')
<div class="container">
    <div class="accordion" id="damages">
        <div class="col-md-12 py-3">
            <h1 class="text-center pt-5 pb-4 d-none d-print-block">জননী বস্ত্রালয়</h1>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">All Damage Product</h5>
                    <span class="d-none d-print-block">{{ date('d/m/Y') }}</span>
                    <div class="action-area print-none">
                        <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>
                        <a href="{{ route('admin.stock.index') }}" class="btn btn-primary" title="All Stock">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Warehouse Name</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Operator</th>
                                <th>Create date</th>
                                <th class="text-right print-none">Action</th> 
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($damage_stocks as $stock)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $stock->warehouses->title }}</td>
                                    <td>{{ $stock->product->name }}</td>
                                    <td>
                                        {{ \App\Helpers\Converter::convert($stock->quantity, $stock->product->unit_code, 'd')['display'] }}
                                    </td>
                                    <td>{{ $stock->operator->name }}</td>
                                    <td>{{ $stock->created_at->format('d/m/Y') }}</td>

                                    <td class="text-right print-none">
                                        <a href="{{ route('admin.damage.edit', $stock->id) }}" class="btn btn-primary" title="add damage product.">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                         
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No damage available</td>
                                </tr>
                            @endforelse
                               
                        </tbody>
                    </table>
                </div>
                <!-- paginate -->
                <div class="float-right mx-2">
                    {{ $damage_stocks->links() }}
                </div>
                
            </div>          
        </div>      
    </div>
</div>
@endsection