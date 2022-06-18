@extends('layouts.user')

@section('title', $title)

@section('content')
<div class="container">
    <div class="row">
        <customer-due-manage-create :customers="{{ $customers }}" :lang="{{ json_encode($lang) }}" :cashes="{{ $cashes }}" :banks="{{ $banks }}"></customer-due-manage-create>
    </div>
</div>
@endsection
