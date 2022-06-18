@extends('layouts.user')

@section('title', __('contents.supplier'))

@section('content')
    <div class="container">
        <div class="row mt-3">
            <div class="col-sm-8">
                <div class="alert {{ ($total < 0) ? 'alert-danger' : 'alert-success' }}">
                    <strong>@lang('contents.total_balance_bdt') {{ $total }}</strong>
                </div>
            </div>

            <div class="col-sm-4 text-right">
                <div class="btn-group" aria-label="Action area">
                    <a href="{{ route('supplier.index') }}" class="btn mr-1 btn-success d-flex justify-content-center align-items-center p-3" title="Refresh." style="height: 3rem;">
                        <i class="fa fa-refresh"></i>
                    </a>

                    <button class="btn btn-info d-flex mr-1 justify-content-center align-items-center p-3" type="button" data-toggle="collapse" data-target="#supplier-search" style="height: 3rem;">
                        <i class="fa fa-search"></i>
                    </button>

                    <a href="{{ route('supplier.import') }}" class="btn btn-primary mr-1 d-flex justify-content-center align-items-center p-3" title="Create new supplier." style="height: 3rem;">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('supplier.create') }}" class="btn btn-primary mr-1 d-flex justify-content-center align-items-center p-3" title="Create new supplier." style="height: 3rem;">
                        <i class="fa fa-plus"></i>
                    </a>

                    <a href="{{ route('supplier.viewTrashed') }}" title="view trashed" class="btn btn-danger mr-1 d-flex justify-content-center align-items-center p-3">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row pt-2">
            <div class="collapse col-md-12 pb-2" id="supplier-search">
                <div class="card card-body">
                    <form action="{{ route('supplier.index') }}" method="GET">
                        <input type="hidden" name="search" value="1">

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="condition[name]" placeholder="enter name" id="name">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" name="condition[phone]" placeholder="enter phone number" id="phone">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="division">Division</label>
                                <input type="text" class="form-control" name="condition[division]" placeholder="enter division" id="division">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="zila">Zila</label>
                                <input type="text" class="form-control" name="condition[zila]" placeholder="enter zila number" id="zila">
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label for=""></label>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> &nbsp;
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

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

                            <a href="{{ route('supplier.show', $supplier->id) }}" class="btn btn-success text-white" title="Supplier details.">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>

                            <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-success" title="Change supplier information.">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>

                            <a href="{{ route('supplier.index') }}" class="btn btn-danger float-right" title="Trash" onClick="if(confirm('Are you sure, You want to move trashed this record?')){event.preventDefault();document.getElementById('delete-form-{{ $supplier->id }}').submit();} else {event.preventDefault();}">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>

                            <form action="{{ route('supplier.destroy', $supplier->id) }}" method="post" id="delete-form-{{ $supplier->id }}" style="display: none;">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-md-4">
                <a href="{{ route('supplier.create') }}" class="btn btn-primary d-flex justify-content-center align-items-center" title="Create new supplier." style="height:15.5rem;">
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
