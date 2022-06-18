@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="container">
	<div class="row">

		<div class="col-md-12 py-3">
            
            <supplier-due-manage-component :supplier="{{ $supplier }}" :cashes="{{ $cashes }}" :banks="{{ $banks }}">
            </supplier-due-manage-component>
		</div>
	</div>
</div>
@endsection