@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="m-0">Update Zone</h5>
                            <small></small>
                        </div>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('zone.index') }}" class="btn btn-primary" title="All cash">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('zone.update', $zone->id) }}" method="post">
                            @csrf
                            @method('PATCH')

                            <div class="form-group required">
                                <label for="name">Name</label>
                                <input type="text" name="name" value="{{ $zone->name }}" class="form-control" id="name" placeholder="Zone name" required>
                            </div>

                            <div class="form-group required">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" id="description" rows="5">{{ $zone->description }}</textarea>
                            </div>

                            <div class="text-right">
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
