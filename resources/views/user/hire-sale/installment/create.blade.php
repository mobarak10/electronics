@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <create-installment-component :customers="{{ $customers }}" :lang="{{ json_encode($lang) }}" :cashes="{{ $cashes }}" :bank-accounts="{{ $bank_accounts }}">
                </create-installment-component>
            </div>
        </div>
    </div>
@endsection
