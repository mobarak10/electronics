@extends('layouts.user')

@section('title', $title)

@section('content')
<div class="container">
    <div class="row"> 
        <div class="col-md-12 py-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center"> 
                    <h5 class="m-0">Edit Damage</h5>

                    <div class="btn-group" role="group" aria-label="Action area">
                        <a href="{{ route('damageStock.index') }}" class="btn btn-primary" title="All Damages">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    
                    <form action="{{ route('damage.update', $damage_stock->id) }}" method="post">
                        @csrf
                            
                            <div class="col-md-12">

                                <h3>{{ $damage_stock->warehouses->title }}</h3>                                    
                                @php
                                $units = \App\Helpers\Converter::convert($damage_stock->quantity, $damage_stock->product->unit_code, 'd');
                                @endphp
                                
                                    @foreach($units['result'] as $key => $value)
                                    <div class="form-group">
                                        <label for="">{{ $units['labels'][$key] }}:</label>
                                        <input type="text" name="quantity[]" value="{{ $value }}" class="form-control" placeholder="minimun value 0">
                                    </div>
                                    @endforeach
                                        

                                 <div class="text-right">
                                    <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                                    <button type="submit" class="btn btn-primary">@lang('contents.save')</button>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main-panel ends -->
@endsection
