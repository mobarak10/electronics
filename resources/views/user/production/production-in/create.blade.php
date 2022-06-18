@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <form action="{{ route('productionIn.store') }}" method="POST">
            @csrf
            <production-in-create-component></production-in-create-component>
        </form>
    </div>
@endsection
