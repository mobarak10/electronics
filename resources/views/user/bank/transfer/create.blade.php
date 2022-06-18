@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <!-- vue component -->
                <bank-transaction-component :cashes="{{ $cashes }}" :banks="{{ $banks }}"></bank-transaction-component>

            </div>
        </div>
    </div>
@endsection
