@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="container">
    <div class="row"> 
        <div class="col-md-12 py-3">
        	{{-- get value from GenaralLedgerController edit by $glHead array --}}
            <update-gl-account-head-component :gl-accounts="{{ $glAccounts }}" :gl-statements={{ collect($glStatements) }} :gl-record="{{ $record }}">
            </update-gl-account-head-component>
        </div>
    </div>
</div>
<!-- main-panel ends -->
@endsection
