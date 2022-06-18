@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="container">
    <div class="row"> 
        <div class="col-md-12 py-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center"> 
                    <h5 class="m-0">Edit Expenditure</h5>

                    <div class="btn-group" role="group" aria-label="Action area">
                        <a href="{{ route('admin.expenditureHead.index') }}" class="btn btn-primary" title="All Unit">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.expenditureHead.update', $expenditure->id) }}" method="post">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="form-group col-md-6 required">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" value="{{ $expenditure->name }}" id="title" name="title" required>
                            </div>

                            <div class="form-group col-md-6 required">
                                <label for="active">Status</label>
                                <select name="active" id="active" class="form-control" required>
                                    <option value="1" {{ $expenditure->active == 1 ? "selected" : '' }}>Active</option>
                                    <option value="0" {{ $expenditure->active == 0 ? "selected" : ''}}>Deactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control">{{ $expenditure->description }}</textarea>
                        </div>

                        <div class="text-right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main-panel ends -->
@endsection
