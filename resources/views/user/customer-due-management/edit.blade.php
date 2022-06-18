@extends('layouts.user')
@section('title', $title)
@section('content')
    <div class="container">
        <div class="row">
            <customer-due-manage-edit :due-manage="{{ $due_manage }}" :customers="{{ $customers }}" :cashes="{{ $cashes }}" :banks="{{ $banks }}"></customer-due-manage-edit>
        </div>
    </div>
@endsection
