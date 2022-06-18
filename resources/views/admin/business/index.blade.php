@extends('layouts.admin')

@section('title', __('contents.business'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3">
            </div>
        </div>

        <div class="row pt-2 pb-5">
            @foreach($businesses as $key => $business)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">{{ $business->name }}</div>

                        <div class="card-body">
                            <h5 class="card-title">{{ $business->address }}</h5>
                            <p class="card-text">@lang('contents.last_updated_at') {{ $business->updated_at->format('j F, Y') }}</p>

                            <a href="{{ route('admin.business.show', $business->id) }}" class="btn btn-success text-white" title="Cash details.">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>

                            <a href="{{ route('admin.business.edit', $business->id) }}" class="btn btn-success" title="Change business information.">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>

                            <a href="{{ route('admin.business.index') }}" class="btn btn-danger float-right" title="Trash" onClick="if(confirm('Are you sure, You want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $business->id }}').submit();} else {event.preventDefault();}">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>

                            <form action="{{ route('admin.business.destroy', $business->id) }}" method="post" id="delete-form-{{ $business->id }}" style="display: none;">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-md-4">
                <a href="{{ route('admin.business.create') }}" class="btn btn-block btn-primary d-flex flex-column justify-content-center" title="Add new business information." style="height:12.5rem;">
                    <i class="fa fa-plus h2 mb-0" aria-hidden="true"></i>
                    <span class="d-block">@lang('contents.add_new_business')</span>
                </a>
            </div>

        </div>
    </div>
@endsection


