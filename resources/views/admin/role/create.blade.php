@extends('layouts.admin')

@section('title', $title)

@section('content')
<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Create</h5>
                </div>
            
                <div class="card-body">
                    <form action="{{ route('admin.role.store') }}" method="POST">
                        @csrf
                
                        <div class="form-group required">
                            <label for="name">Role name </label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Role name must in 190 characters" required>
                        </div>

                        <div class="form-row required">
                            <label>Permissions </label>
                            @foreach ($menus as $key => $menu)
                                <div class="col-md-3 mb-3">
                                    <strong class="d-block">{{ $menu->name }}</strong>
                                    
                                    @foreach ($menu->permissions as $permission)
                                        <div class="form-check ml-2">
                                            <input class="form-check-input" type="checkbox" name="permission[]" value="{{ $permission->id }}" id="{{ $menu->slug . '-' . $permission->name }}">
                                            <label class="form-check-label mb-1" for="{{ $menu->slug . '-' . $permission->name }}" style="cursor: pointer;">{{ $permission->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            <label for="description">Description </label>
                            <textarea name="description" id="description" class="form-control" placeholder="Optional"></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Role</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
