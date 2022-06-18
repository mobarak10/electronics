@extends('layouts.admin')

@section('title', __('contents.supplier'))

@section('content')
    <div class="container">
        <div class="row mt-3">
            <div class="col-sm-10">
                <div class="alert {{ ($total < 0) ? 'alert-danger' : 'alert-success' }}">
                    <strong>@lang('contents.total_balance_bdt') {{ $total }}</strong>
                </div>
            </div>

            <div class="col-sm-2 text-right">
                <div class="btn-group" role="group" aria-label="Action area">
                    <a href="{{ route('admin.supplier.create') }}" class="btn btn-primary d-flex justify-content-center align-items-center p-3" title="Create new supplier." style="height: 3rem;">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row pt-2 pb-5">
            @foreach($suppliers as $key => $supplier)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">{{ $supplier->name }}</div>

                        <div class="card-body">
                            <h5 class="card-title {{ ($supplier->balance < 0) ? 'text-danger' : 'text-primary' }}">@lang('contents.bdt') {{ number_format($supplier->balance, 2) }}</h5>
                            <p class="card-text">
                                <strong title="Phone number">{{ $supplier->phone }}</strong> <br>
                                <strong title="Email address">{{ $supplier->email }}</strong> <br>
                                @lang('contents.last_transaction_at') {{ $supplier->updated_at->format('j M, y') }}
                            </p>

                            <a href="{{ route('admin.supplier.show', $supplier->id) }}" class="btn btn-success text-white" title="Supplier details.">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>

                            <a href="{{ route('admin.supplier.edit', $supplier->id) }}" class="btn btn-success" title="Change supplier information.">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>

                            <a href="{{ route('admin.supplier.index') }}" class="btn btn-danger float-right" title="Trash" onClick="if(confirm('Are you sure, You want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $supplier->id }}').submit();} else {event.preventDefault();}">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>

                            <form action="{{ route('admin.supplier.destroy', $supplier->id) }}" method="post" id="delete-form-{{ $supplier->id }}" style="display: none;">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-md-4">
                <a href="{{ route('admin.supplier.create') }}" class="btn btn-primary d-flex justify-content-center align-items-center" title="Create new supplier." style="height:12.5rem;">
                    <span>
                        <i class="fa fa-plus h2 mb-0"></i> <br>
                        <span class="d-block">@lang('contents.add_new_supplier')</span>
                    </span>
                </a>
            </div>

            <div class="col-md-12">
                <!-- paginate -->
                <div class="float-right mx-2">{{ $suppliers->links() }}</div>
            </div>

        </div>
    </div>
@endsection
