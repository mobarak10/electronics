@extends('layouts.admin')

@section('title', $title)

@section('content')
<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">Permission Details</h5>

                    <div class="btn-group" role="group" aria-level="Action area">
                        <a href="{{ route('admin.permission.index') }}" class="btn btn-primary" title="Back">Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <h4>{{ $permissions[0]->menu->name }}</h4>
                    <p>{{ $permissions[0]->menu->description }}</p>

                    <strong>Permissions: </strong>
                    <ul class="ml-3">
                        @foreach ($permissions as $permission)
                            <li class="mb-1">
                                <span class="d-inline-block" style="width: 60px;">{{ $permission->name }}</span>
                                <span>{{ $permission->slug }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

