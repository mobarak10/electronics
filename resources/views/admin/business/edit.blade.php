@extends('layouts.admin')

@section('title', __('contents.business'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="m-0">@lang('contents.update_business_record')</h5>
                            <small></small>
                        </div>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('admin.business.index') }}" class="btn btn-primary" title="All business">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.business.update', $business->id) }}" method="post">
                            @csrf
                            @method('PATCH')

                            <div class="form-row">
                                <div class="form-group col-md-6 required">
                                    <label for="name">@lang('contents.name') </label>
                                    <input type="text" name="name" value="{{ $business->name }}" class="form-control" id="name" placeholder="Business name" required>
                                </div>

                                <div class="form-group col-md-6 required">
                                    <label for="phone">@lang('contents.phoneNumber')</label>
                                    <input type="text" name="phone" value="{{ $business->phone }}" required class="form-control" id="phone" placeholder="Phone">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 required">
                                    <label for="email">@lang('contents.email')</label>
                                    <input type="email" value="{{ $business->email }}" name="email" class="form-control" required id="email" placeholder="Email">
                                </div>

                                <div class="form-group col-md-6 required">
                                    <label for="avatar">@lang('contents.photo_media_id')</label>
                                    <input type="number" required name="thumbnail" class="form-control" id="avatar" value="{{ $business->thumbnail }}" placeholder="Enter Media Code">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group col-md-6 required">
                                    <label for="address">@lang('contents.address') </label>
                                    <textarea class="form-control"placeholder="enter address" name="address" id="address">{{ $business->address }}</textarea>
                                </div>

                                 <div class="form-group col-md-6">
                                    <label for="description">@lang('contents.description') (optional) </label>
                                    <textarea name="description" id="description" class="form-control">{{ $business->description }}</textarea>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                                <button type="submit" class="btn btn-primary">@lang('contents.save')</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
