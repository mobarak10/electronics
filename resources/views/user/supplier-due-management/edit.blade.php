@extends('layouts.user')
@section('title', $title)
@section('content')
    <div class="container">
        <div class="row">
            <supplier-due-manage-edit :due-manage="{{ $due_manage }}" :suppliers="{{ $suppliers }}" :cashes="{{ $cashes }}" :banks="{{ $banks }}"></supplier-due-manage-edit>
        </div>
    </div>
@endsection
