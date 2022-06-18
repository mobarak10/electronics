@extends('layouts.admin')

@section('title', 'Update Role')

@section('content')
<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Update Role</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.role.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="form-group required">
                            <label for="name">Role name </label>
                            <input type="text" name="name" class="form-control" value="{{ $role->name }}" id="name" placeholder="Role name must in 190 characters" required>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <strong class="d-block">Cash Permissions:</strong>
                            
                                <div>
                                    @foreach($permissions as $permission)
                                        @if($permission->menu->slug == 'cash')
                                            <label>
                                                <input type="checkbox" name="permission[]" value="{{ $permission->id }}" 
                                                @foreach($role->permissions as $role_permission)
                                                    @if($role_permission->id == $permission->id)
                                                        checked
                                                    @endif
                                                @endforeach
                                                >
                                                
                                                <span>{{ $permission->name }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <strong class="d-block">Category Permissions:</strong>
                            
                                <div>
                                    @foreach($permissions as $permission)
                                        @if($permission->menu->slug == 'category')
                                            <label>
                                                <input type="checkbox" name="permission[]" value="{{ $permission->id }}" 
                                                @foreach($role->permissions as $role_permission)
                                                    @if($role_permission->id == $permission->id)
                                                        checked
                                                    @endif
                                                @endforeach
                                                >
                                                
                                                <span>{{ $permission->name }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" cols="50" rows="5" class="form-control" placeholder="Optional">{{ $role->description }}</textarea>
                        </div>

                        <div class="text-right">
                            <button type="reset" class="btn btn-danger" data-dismiss="modal">Reset</button>
                            <button type="submit" class="btn btn-primary">Update Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
</div>
@endsection

