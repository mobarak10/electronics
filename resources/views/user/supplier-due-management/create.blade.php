@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <supplier-due-manage-create :suppliers="{{ $suppliers }}" :lang="{{ json_encode($lang) }}" :cashes="{{ $cashes }}" :banks="{{ $banks }}"></supplier-due-manage-create>
        </div>
    </div>
@endsection
