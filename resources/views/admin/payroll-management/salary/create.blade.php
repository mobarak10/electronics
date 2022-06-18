@extends('layouts.admin')

@section('title', $title)
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                <create-salary :records="{{ $records }}"></create-salary>
            </div>
        </div>
    </div>
@endsection
