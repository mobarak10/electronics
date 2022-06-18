@extends('layouts.user')

@section('content')
    <div class="container">
        <div class="row">
            <passport-clients></passport-clients> <br>
            <passport-authorized-clients></passport-authorized-clients> <br>
            <passport-personal-access-tokens></passport-personal-access-tokens>
        </div>
    </div>
@endsection
