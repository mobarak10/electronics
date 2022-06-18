@extends('layouts.admin')

@section('title', $title)

@section('content')
<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Role Details</h5>
                </div>

                <div class="card-body">
                    <h4>{{ $role->name }}</h4>
                    <p class="ml-3">{{ $role->description }}</p>

                    <h4>Permissions</h4>
                    <div class="row">
                        @foreach ($permissions as $key => $permission)
                            <div class="col-md-3">
                                <strong class="d-block">{{ $permission[0]['menu'] }}</strong>
                                
                                <ul class="ml-3">
                                    @foreach ($permission as $row)
                                        <li>{{ $row['name'] }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

