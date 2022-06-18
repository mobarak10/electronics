@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="container">
    	<div class="card">
	        <div class="card-header d-flex justify-content-between align-items-center">
	            <div>Setting</div>

	            <div>
	            	<a href="{{ route('admin.settings.index') }}" class="btn btn-success" title="Refresh.">
	                	<i class="fa fa-refresh"></i>
	                </a>

	                <a href="{{ route('admin.settings.create') }}" class="btn btn-primary" title="create new.">
	                	<i class="fa fa-plus"></i>
	                </a>
	            </div>
	        </div>

	        <div class="card-body">
	            <form action="{{ route('admin.settings.index') }}" method="GET">
	                <input type="hidden" name="search" value="1">
	                <div class="row">
	                    <div class="form-group col-md-6 required">
	                        <select class="form-control" name="business_id">
	                        	<option value="" selected disabled>Choose One</option>
	                        	@foreach($businesses as $business)
	                        		<option value="{{ $business->id }}">{{ $business->name }}</option>
	                        	@endforeach
	                        </select>
	                    </div>

	                    <div class="form-group col-md-2 text-right">
	                        <button type="submit" class="btn btn-primary">
	                            <i class="fa fa-sign-in" aria-hidden="true"></i> &nbsp;
	                            Enter
	                        </button>
	                    </div>
	                </div>
	            </form>
	            <hr>

	            @if(request()->search)
	                @if($settings != null)
	                	<div class="invoice-header">
	                	    <div class="row align-items-center justify-content-between">
	                	        <div class="col-4">
	                	            <div class="logo">
	                	                <img src="{{ asset($settings['thumbnail'] ?? '') }}" class="img-fluid">
	                	            </div>
	                	        </div>
	                	        <div class="col-6">
	                	            <div class="text">
	                	                <strong>Business Name: {{ $busines->name }}</strong><hr>
	                	                <strong>Phone: {{ $settings['phone'] ?? ''}} </strong><hr>
	                	                <strong>Address: {{ $settings['address'] ?? '' }}</strong><hr>
	                	                <strong>Email: {{ $settings['email'] ?? '' }}</strong><hr>
	                	            </div>
	                	        </div>

	                	    </div>
	                	</div>
	                	<div class="text-right">
	                		<a href="{{ route('admin.settings.edit', $busines->id) }}" class="btn btn-success" title="Change business information.">
	                		    <i class="fa fa-pencil" aria-hidden="true"></i>
	                		</a>

	                		<a href="#" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, You want to delete this record?')){event.preventDefault();document.getElementById('delete-form').submit();} else {event.preventDefault();}">
	                		    <i class="fa fa-times" aria-hidden="true"></i>
	                		</a>

	                		<form action="#" method="post" id="delete-form" style="display: none;">
	                		    @csrf
	                		    @method('DELETE')
	                		</form>
	                	</div>
	                @else
	                	<h4>No settings for this business please add.</h4>
	                @endif
	            @endif
	        </div>
        </div>
    </div>
@endsection
