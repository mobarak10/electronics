@extends('layouts.admin') 

@section('title', $title)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 py-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center"> 
                    <h5 class="m-0">@lang('contents.add_warehouse')</h5>

                    <div class="btn-group" role="group" aria-label="Action area">
                        <a href="{{ route('admin.warehouse.index') }}" class="btn btn-primary" title="All Bank">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.warehouse.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-row">
                            <div class="form-group col-md-6 required">
                                <label for="title">@lang('contents.title')</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter Warehouse name" required>
                            </div>

                            <div class="form-group col-md-6 required">
                                <label for="employee">@lang('contents.responsible_person')</label>
                                <select name="user_id" class="form-control" id="employee" required>
                                    <option value="" selected disabled>Choose an employee</option>
                                    @foreach($employees as $key => $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                       <div class="form-row">
                            <div class="form-group col-md-6 required">
                                <label>@lang('contents.address')</label>
                                <textarea name="address" class="form-control" placeholder="Enter warehouse full address" required></textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label>@lang('contents.description')</label>
                                <textarea name="description" class="form-control" placeholder="enter additional information for this warehouse (optional)"></textarea>
                            </div>
                       </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">@lang('contents.save')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main-panel ends -->
@endsection
