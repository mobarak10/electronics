@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="container">   
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">All stock</h5>

                    <div class="btn-group" role="group" aria-label="Action area">
                        <button class="btn btn-info" type="button" title="Search product" data-toggle="collapse" data-target="#searchCollapse" aria-expanded="false" aria-controls="collapseSearch">
                            <i class="fa fa-search"></i> 
                        </button>

                        <a href="{{ route('admin.stock.index') }}" class="btn btn-primary" title="Refresh">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                        </a>
    
                        <a href="{{ route('admin.damageStock.index') }}" class="btn btn-success" title="Show damages product.">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- search form start -->
                    <div class="collapse p-3" id="searchCollapse">
                        <form action="{{ route('admin.stock.index') }}" method="GET" class="row">
                            <input type="hidden" name="search" value="1">

                            <div class="input-group col-md-12">
                                <input type="text" name="productCode" class="form-control" placeholder="Enter product code" aria-label="Enter product code" aria-describedby="button-addon" required>
                                
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary" type="button" id="button-addon" title="search">
                                        <i class="fa fa-search"></i> Search 
                                    </button>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                    <!-- search form end -->

                    <!-- accordion start -->
                    <div class="accordion" id="warehouseAccordion">
                        @foreach($products as $product)
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center" id="warehouse-{{ $loop->index }}">
                                    <h5 class="m-0">
                                        <a href="#"
                                            class="btn btn-link {{ ($product->stock_alert >= $product->stock->sum('quantity')) ? 'text-danger' : '' }}" 
                                            data-toggle="collapse" 
                                            data-target="#collapse-{{ $loop->index }}" 
                                            aria-expanded="true" 
                                            aria-controls="collapse-{{ $loop->index }}">
                                            {{ $product->name }}
                                            ({{ \App\Helpers\Converter::convert($product->stock->sum('quantity'), $product->getUnitCodeAttribute(), 'd')['display'] }})
                                            {{ $product->code }} 
                                        </a>
                                    </h5>

                                    <div class="btn-group" role="group" aria-label="Action area">
                                        <a href="{{ route('admin.stock.edit', $product->id) }}" class="btn btn-primary" title="Edit product quantity.">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('admin.damageStock.edit', $product->id) }}" target="_blank" class="btn btn-success" title="Add damages.">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </a>

                                        {{-- <a href="{{ route('admin.stock.index') }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, Want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $product->id }}').submit();} else {event.preventDefault();}">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>

                                        <form action="{{ route('admin.stock.destroy', $product->id) }}" method="post" id="delete-form-{{ $product->id }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form> --}}
                                    </div>
                                </div>
            
                                <div id="collapse-{{ $loop->index }}" class="collapse {{ ($loop->first) ? 'show' : '' }}" aria-labelledby="warehouse-{{ $loop->index }}" data-parent="#warehouseAccordion">
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach ($product->warehouses as $warehouse)
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
                    <div class="float-right m-3">{{ $products->links() }}</div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection