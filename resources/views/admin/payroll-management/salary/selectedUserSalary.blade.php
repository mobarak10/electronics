@extends('layouts.admin')

@section('title', $title)
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                <selected-user-salary :selected-user="{{ $user }}"></selected-user-salary>
            </div>
        </div>
    </div>
@endsection
