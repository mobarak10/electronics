@extends('layouts.user')

@section('title', __('contents.warehouse'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.warehouse_records')</h5>

                        <div class="action-area print-none" role="group" aria-label="Action area">
                            <a href="{{ route('warehouse.viewTrashed') }}" title="view trashed" class="btn btn-danger">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            @forelse($warehouses as $warehouse)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">{{ $warehouse->title }}</div>

                        <div class="card-body">
                            <h5 class="card-title">{{ $warehouse->user->name }}</h5>
                            <p class="card-text">
                                <strong title="Warehouse code">{{ $warehouse->code }}</strong> <br>
                                @lang('contents.created_at') {{ $warehouse->created_at->format('j M, y') }}
                            </p>

                            <a href="{{ route('warehouse.status', $warehouse->id) }}" class="btn btn-{{ ($warehouse->status) ? 'danger' : 'success' }}" title="Click here to {{ ($warehouse->status ? 'Inactive' : 'Active') }}">
                                {{ ($warehouse->status) ? 'Inactive' : 'Active' }}
                            </a>

                            <div class="float-right">
                                <a href="{{ route('warehouse.show', $warehouse->id) }}" class="btn btn-primary" title="Show warehouse all information.">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>

                                <a href="{{ route('warehouse.edit', $warehouse->id) }}" class="btn btn-primary" title="Change warehouse information.">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>

                                <a href="{{ route('warehouse.index') }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, You want to move trashed this record?')){event.preventDefault();document.getElementById('delete-form-{{ $warehouse->id }}').submit();} else {event.preventDefault();}">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>

                                <form action="{{ route('warehouse.destroy', $warehouse->id) }}" method="post" id="delete-form-{{ $warehouse->id }}" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- create new -->
            <div class="col-md-4">
                <a href="{{ route('warehouse.create') }}" class="btn btn-primary d-flex justify-content-center align-items-center" title="Create new supplier." style="height:14rem;">
                    <span>
                        <i class="fa fa-plus h2 mb-0"></i> <br>
                        <span class="d-block">@lang('contents.add_warehouse')</span>
                    </span>
                </a>
            </div>

            <!-- paginate -->
            <div class="col-md-12">
                <div class="float-right mx-2">{{ $warehouses->links() }}</div>
            </div>

        </div>
    </div>
@endsection
