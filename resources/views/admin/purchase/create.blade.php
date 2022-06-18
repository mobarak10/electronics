@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="container">
        <form action="{{ route('admin.purchase.store') }}" method="POST">
            @csrf
            <product-purchase-component></product-purchase-component>
        </form>
    </div>
@endsection
