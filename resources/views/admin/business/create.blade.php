@extends('layouts.admin')

@section('title', $title)
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">New Business</h5>
                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('admin.business.index') }}" class="btn btn-primary" title="All Bank">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="col-12 py-2">
                            <form action="{{ route('admin.business.store') }}" method="post">
                                @csrf

                                <div class="form-row">
                                    <div class="form-group col-md-6 required">
                                        <label for="name">@lang('contents.business_name')</label>
                                        <input type="text" name="name" class="form-control" id="name" placeholder="enter business name" required>
                                    </div>

                                    <div class="form-group col-md-6 required">
                                        <label for="phone">@lang('contents.phoneNumber')</label>
                                        <input type="text" name="phone" required class="form-control" id="phone" placeholder="Phone">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6 required">
                                        <label for="email">@lang('contents.email')</label>
                                        <input type="email" name="email" class="form-control" required id="email" placeholder="Email">
                                    </div>

                                    <div class="form-group col-md-6 required">
                                        <label for="avatar">@lang('contents.photo_media_id')</label>
                                        <input type="number" required name="thumbnail" class="form-control" id="avatar" placeholder="Enter Media Code">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 required">
                                        <label for="address">@lang('contents.address') </label>
                                        <textarea class="form-control" placeholder="enter address" name="address" id="address"></textarea>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="description">@lang('contents.description') (optional)</label>
                                        <textarea class="form-control" name="description" id="description"></textarea>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('contents.close')</button>
                                    <button type="submit" class="btn btn-primary">@lang('contents.save')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

