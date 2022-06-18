@extends('layouts.admin')

@section('title', $title)
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Settings</h5>
                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-primary" title="All Bank">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="col-12 py-2">
                            <form action="{{ route('admin.settings.store') }}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-6 required">
                                        <label for="business">Shop</label>
                                        <select name="business_id" id="business" class="form-control" required>
                                            <option value="" selected disabled>Choose one</option>
                                            @foreach($businesses as $business)
                                                <option value="{{ $business->id }}">{{ $business->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 required">
                                        <label for="phone">@lang('contents.phone')</label>
                                        <input type="text" name="phone" required class="form-control" id="phone" placeholder="Phone">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 required">
                                        <label for="email">@lang('contents.email')</label>
                                        <input type="email" name="email" class="form-control" required id="email" placeholder="Email">
                                    </div>
                                    <div class="form-group col-6 required">
                                        <label for="avatar">@lang('contents.photo_media_id')</label>
                                        <input type="number" required name="thumbnail" class="form-control" id="avatar" placeholder="Enter Media Code">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 required">
                                        <label for="address">@lang('contents.address')</label>
                                        <textarea class="form-control" required name="address" id="address" placeholder="Address"></textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="description">@lang('contents.description')</label>
                                        <textarea class="form-control" name="description" id="description" placeholder="Description"></textarea>
                                    </div>
                                </div>

                                <div class="text-right">
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

