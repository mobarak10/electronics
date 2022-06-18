@extends('layouts.user')

@section('title', $title)

@section('content')
<div class="container">   
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">All damages stock</h5>

                    <div class="btn-group" role="group" aria-label="Action area">
                        <button class="btn btn-info" type="button" title="Search product" data-toggle="collapse" data-target="#searchCollapse" aria-expanded="false" aria-controls="collapseSearch">
                            <i class="fa fa-search"></i> 
                        </button>

                        <a href="{{ route('damageStock.index') }}" class="btn btn-primary" title="Refresh">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                        </a>
    
                        <a href="{{ route('stock.index') }}" class="btn btn-success" title="Show all product.">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- accordion start -->
                    <div class="accordion" id="warehouseAccordion">
                        @foreach($damage_stocks as $key => $damage)
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center" id="warehouse-{{ $loop->index }}">
                                    <h5 class="m-0">
                                        <a href="#"
                                            class="btn btn-link" 
                                            data-toggle="collapse" 
                                            data-target="#collapse-{{ $loop->index }}" 
                                            aria-expanded="true" 
                                            aria-controls="collapse-{{ $loop->index }}">
                                            ({{ \App\Helpers\Converter::convert($damage->sum('quantity'), $damage->product->getUnitCodeAttribute(), 'd')['display'] }})

                                            {{-- {{ $damage->product->id }} --}}
                                        </a>
                                    </h5>

                                    <div class="btn-group" role="group" aria-label="Action area">
                                        {{-- <a href="{{ route('damageStock.edit', $damage_stocks->id) }}" class="btn btn-primary" title="Edit product quantity.">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a> --}}

                                        {{-- <a href="{{ route('damageStock.edit', $product->id) }}" target="_blank" class="btn btn-success" title="Add damages.">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </a> --}}
                                        
                                    </div>
                                </div>
            
                                <div id="collapse-{{ $loop->index }}" class="collapse {{ ($loop->first) ? 'show' : '' }}" aria-labelledby="warehouse-{{ $loop->index }}" data-parent="#warehouseAccordion">
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- {{ dd($damage->warehouses->title) }} --}}
                                            @foreach ($damage->product->warehouses as $warehouse)
                                                <div class="col-md-4">
                                                    <div class="card mb-3">
                                                        <div class="card-body">
                                                            <h5 class="card-title">{{ $warehouse->title }}</h5>
                                                            <p class="card-text">
                                                                <small class="d-block">Current stock</small>
                                                                {{ $warehouse->product_quantity_in_unit['display'] }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- accordion end -->

                    <!-- paginate -->
                    <div class="float-right m-3">{{ $damage_stocks->links() }}</div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection