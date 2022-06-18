@extends('layouts.admin')  

@section('title', $title)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 py-3">
            <create-gl-account-head-component :statements="{{ collect($value = config('coderill.statements')) }}" :gl-account="{{ $records }}">
            </create-gl-account-head-component>
        </div>
    </div>
</div>
<!-- main-panel ends -->
@endsection
