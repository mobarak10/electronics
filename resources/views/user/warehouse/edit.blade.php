@extends('layouts.user')

@section('title', $title)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 py-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center"> 
                    <h5 class="m-0">@lang('contents.edit_warehouse')</h5>

                    <div class="btn-group" role="group" aria-label="Action area">
                        <a href="{{ route('warehouse.index') }}" class="btn btn-primary" title="All cash">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('warehouse.update', $warehouse->id) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="form-row">
                            <div class="form-group col-md-6 required">
                                <label for="title">@lang('contents.title')</label>
                                <input type="text" class="form-control" value="{{ $warehouse->title }}" id="title" name="title" placeholder="Enter Warehouse name" required>
                            </div>

                            <div class="form-group col-md-6 required">
                                <label for="employee">@lang('contents.responsible_person')</label>
                                <select name="user_id" class="form-control" id="employee" required>
                                    <option value="" selected disabled>Select one</option>
                                    @foreach($employees as $key => $employee)
                                        <option value="{{ $employee->id }}" {{ $employee->id == $warehouse->user->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 required">
                                <label>@lang('contents.address')</label>
                                <textarea name="address" rows="8" placeholder="Enter Warehouse Address" cols="80" class="form-control" required>{{ $warehouse->address }}</textarea>
                            </div>


                            <div class="form-group col-md-6">
                                <label>@lang('contents.description')</label>
                                <textarea name="description" placeholder="Write something about this warehouse (Optional)" rows="8" cols="80" class="form-control">{{ $warehouse->description }}</textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12 required">
                                <label for="status">@lang('contents.status')</label>
                                <select name="status" class="form-control" id="status" required>
                                    <option value="1" {{ $warehouse->status == 1 ? 'selected' : '' }}>Active</option>

                                    <option value="0" {{ $warehouse->status == 0 ? 'selected' : '' }}>Deactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                            <button type="submit" class="btn btn-primary">@lang('contents.save_changes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main-panel ends -->
@endsection
