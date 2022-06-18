@extends('layouts.user')

@section('title', $title)

@section('content')
<div class="container">
    <div class="row">
        <manage-due-create :holders="{{ $holders }}" :cashes="{{ $cashes }}" holder_genus="{{ $holder_genus }}" :banks="{{ $banks }}"></manage-due-create>
    </div>  
</div>
@endsection