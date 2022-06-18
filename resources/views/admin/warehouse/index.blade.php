@extends('layouts.admin')

@section('title', __('contents.warehouse'))

@section('content')
    <div class="container">
        <div class="row">

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

                            <a href="{{ route('admin.warehouse.status', $warehouse->id) }}" class="btn btn-{{ ($warehouse->status) ? 'danger' : 'success' }}" title="Click here to {{ ($warehouse->status ? 'Inactive' : 'Active') }}">
                                {{ ($warehouse->status) ? 'Inactive' : 'Active' }}
                            </a>

                            <div class="float-right">
                                <a href="{{ route('admin.warehouse.show', $warehouse->id) }}" class="btn btn-primary" title="Show warehouse all information.">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>

                                <a href="{{ route('admin.warehouse.edit', $warehouse->id) }}" class="btn btn-primary" title="Change warehouse information.">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>

                                <a href="{{ route('admin.warehouse.index') }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, You want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $warehouse->id }}').submit();} else {event.preventDefault();}">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>

                                <form action="{{ route('admin.warehouse.destroy', $warehouse->id) }}" method="post" id="delete-form-{{ $warehouse->id }}" style="display: none;">
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
                <a href="{{ route('admin.warehouse.create') }}" class="btn btn-primary d-flex justify-content-center align-items-center" title="Create new supplier." style="height:14rem;">
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
